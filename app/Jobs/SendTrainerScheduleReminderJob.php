<?php

namespace App\Jobs;

use App\Services\TrainerScheduleReminderService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTrainerScheduleReminderJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public int $uniqueFor = 3600;

    public function __construct(
        public int $instructorId,
        public string $date,
        public bool $force = false,
    ) {
    }

    public function uniqueId(): string
    {
        return $this->instructorId . ':' . $this->date;
    }

    public function handle(TrainerScheduleReminderService $service): void
    {
        $date = Carbon::parse($this->date)->startOfDay();

        $schedules = $service->schedulesForDate($date)
            ->where('instructor_id', $this->instructorId)
            ->values();

        if ($schedules->isEmpty()) {
            return;
        }

        $instructor = $schedules->first()->instructorProfile;

        $stats = $service->processInstructorGroup($instructor, $schedules, $date, $this->force);

        Log::info('Trainer reminder job processed', [
            'instructor_id' => $this->instructorId,
            'date' => $date->toDateString(),
            'stats' => $stats,
        ]);
    }
}