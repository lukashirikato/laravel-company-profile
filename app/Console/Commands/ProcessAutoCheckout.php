<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProcessAutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:process-auto-checkout {--dry-run : Run without actually performing checkout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checkout members yang sudah melewati 60 menit (auto_checkout_at)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔄 Processing auto-checkout...');
        
        if ($dryRun) {
            $this->warn('⚠️  DRY-RUN MODE: No actual changes will be made.');
        }

        try {
            // Cari semua attendance yang:
            // 1. Sudah check-in (check_in_at != NULL)
            // 2. Belum check-out (check_out_at == NULL)
            // 3. Sudah lewat auto_checkout_at
            $duepedAttendances = Attendance::where('check_in_at', '!=', null)
                ->whereNull('check_out_at')
                ->whereNotNull('auto_checkout_at')
                ->where('auto_checkout_at', '<=', now())
                ->with(['customer', 'schedule.classModel', 'order'])
                ->get();

            if ($duepedAttendances->isEmpty()) {
                $this->info('✅ Tidak ada attendance yang perlu auto-checkout.');
                return self::SUCCESS;
            }

            $this->info("Found {$duepedAttendances->count()} attendance(s) that need auto-checkout.\n");

            $successCount = 0;
            $errorCount = 0;

            foreach ($duepedAttendances as $attendance) {
                try {
                    DB::beginTransaction();

                    $customer = $attendance->customer;
                    $schedule = $attendance->schedule;
                    $className = $schedule->classModel->name ?? 'Kelas';
                    $elapsedSeconds = (int) now()->diffInSeconds($attendance->check_in_at);
                    $elapsedMinutes = (int) ceil($elapsedSeconds / 60);

                    // Perform auto-checkout
                    if ($dryRun) {
                        $this->line("  [DRY-RUN] Would auto-checkout: {$customer->name} - {$className} ({$elapsedMinutes} menit)");
                    } else {
                        $attendance->performAutoCheckout();
                        $successCount++;

                        $this->line("  ✅ Auto-checkout: {$customer->name} - {$className} ({$elapsedMinutes} menit)");

                        Log::info('✅ Auto-checkout processed by scheduler', [
                            'attendance_id' => $attendance->id,
                            'customer_id' => $customer->id,
                            'customer_name' => $customer->name,
                            'class_name' => $className,
                            'elapsed_minutes' => $elapsedMinutes,
                            'check_in_time' => $attendance->check_in_at->format('Y-m-d H:i:s'),
                            'auto_checkout_time' => $attendance->auto_checkout_at->format('Y-m-d H:i:s'),
                        ]);
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorCount++;

                    $this->error("  ❌ Error processing attendance {$attendance->id}: {$e->getMessage()}");
                    Log::error('❌ Error processing auto-checkout', [
                        'attendance_id' => $attendance->id,
                        'customer_id' => $attendance->customer_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            // Summary
            $this->newLine();
            if ($dryRun) {
                $this->info("📊 DRY-RUN SUMMARY: Would process {$duepedAttendances->count()} attendance(s)");
            } else {
                $this->info("📊 SUMMARY: {$successCount} auto-checkout(s) successful, {$errorCount} error(s)");
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Fatal error: ' . $e->getMessage());
            Log::error('❌ Fatal error in ProcessAutoCheckout command', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }
}
