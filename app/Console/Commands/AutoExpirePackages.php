<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoExpirePackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:auto-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire packages that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for expired packages...');

        // Cari order yang statusnya masih 'active' tapi sudah expired
        $expiredOrders = Order::where('status', 'active')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', Carbon::now())
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('✅ No packages to expire.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiredOrders->count()} packages to expire.");

        $progressBar = $this->output->createProgressBar($expiredOrders->count());
        $progressBar->start();

        $expiredCount = 0;

        foreach ($expiredOrders as $order) {
            try {
                // Update status menjadi 'expired'
                $order->update(['status' => 'expired']);

                // Optional: Reset quota customer jika perlu
                // $order->customer->update(['quota' => 0]);

                Log::info('Package auto-expired', [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'package_name' => $order->package->name,
                    'expired_at' => $order->expired_at,
                ]);

                $expiredCount++;
                $progressBar->advance();

            } catch (\Exception $e) {
                Log::error('Error expiring package', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Successfully expired {$expiredCount} packages.");

        return Command::SUCCESS;
    }
}