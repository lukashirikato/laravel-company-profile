<?php

namespace App\Observers;

use App\Jobs\SendTrainerScheduleReminderJob;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleObserver
{
    /**
     * Kirim reminder segera saat jadwal hari ini dibuat/diubah SETELAH jam 06:00.
     * Jadwal yang dibuat sebelum 06:00 ditangani cron harian.
     */
    public function saved(Schedule $schedule): void
    {
        if (! $schedule->instructor_id) {
            return;
        }

        if (! $schedule->schedule_date) {
            return;
        }

        $date = Carbon::parse($schedule->schedule_date)->startOfDay();

        if (! $date->isToday()) {
            return;
        }

        if (Carbon::now()->lt(Carbon::today()->setTime(6, 0))) {
            return;
        }

        $changes = array_keys($schedule->getChanges());
        $relevant = $schedule->wasRecentlyCreated
            || array_intersect($changes, ['instructor_id', 'schedule_date', 'class_time', 'capacity']);

        if (! $relevant) {
            return;
        }

        SendTrainerScheduleReminderJob::dispatch(
            (int) $schedule->instructor_id,
            $date->toDateString(),
            false
        )->afterCommit();
    }
}