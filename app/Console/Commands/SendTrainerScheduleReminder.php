<?php

namespace App\Console\Commands;

use App\Jobs\SendTrainerScheduleReminderJob;
use App\Models\Schedule;
use App\Services\TrainerScheduleReminderService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTrainerScheduleReminder extends Command
{
    protected $signature = 'trainers:send-schedule-reminder
                            {--date= : Tanggal jadwal (Y-m-d). Default hari ini.}
                            {--force : Kirim ulang walau sudah pernah terkirim.}
                            {--sync : Kirim langsung tanpa antrean (untuk verifikasi manual).}';

    protected $description = 'Kirim reminder WhatsApp harian ke pelatih berisi jadwal mengajar hari itu.';

    public function handle(TrainerScheduleReminderService $service): int
    {
        $date = $this->option('date')
            ? Carbon::parse((string) $this->option('date'))
            : Carbon::today();

        $force = (bool) $this->option('force');

        if ($this->option('sync')) {
            $stats = $service->sendForDate($date, $force);

            $this->info(sprintf(
                'Reminder pelatih %s: %d terkirim, %d gagal, %d dilewati, %d tanpa nomor WA (dari %d jadwal).',
                $date->toDateString(),
                $stats['sent'],
                $stats['failed'],
                $stats['skipped'],
                $stats['missing_phone'],
                $stats['schedules']
            ));

            return $stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
        }

        $schedules = $service->schedulesForDate($date);

        if ($schedules->isEmpty()) {
            $this->info("Tidak ada jadwal pada {$date->toDateString()}.");

            return self::SUCCESS;
        }

        $dispatched = 0;
        $skipped = 0;

        foreach ($schedules->groupBy(fn (Schedule $schedule) => (string) $schedule->instructor_id) as $instructorId => $group) {
            if ($instructorId === '') {
                // Jadwal tanpa pelatih: catat skipped, tak bisa dikirimi.
                $service->processInstructorGroup(null, $group, $date, $force);
                $skipped += $group->count();

                continue;
            }

            SendTrainerScheduleReminderJob::dispatch((int) $instructorId, $date->toDateString(), $force);
            $dispatched++;
        }

        $this->info(sprintf(
            'Reminder pelatih %s: %d job dikirim ke antrean, %d jadwal tanpa pelatih dilewati (dari %d jadwal).',
            $date->toDateString(),
            $dispatched,
            $skipped,
            $schedules->count()
        ));

        return self::SUCCESS;
    }
}