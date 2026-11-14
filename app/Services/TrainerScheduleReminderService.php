<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\Instructor;
use App\Models\Schedule;
use App\Models\ScheduleReminder;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrainerScheduleReminderService
{
    public function __construct(private WhatsAppService $whatsApp)
    {
    }

    /**
     * Ambil seluruh jadwal pada tanggal tertentu beserta relasi yang dipakai
     * untuk menyusun pesan reminder.
     */
    public function schedulesForDate(CarbonInterface|string $date): Collection
    {
        $date = Carbon::parse($date)->startOfDay();

        return Schedule::query()
            ->whereDate('schedule_date', $date)
            ->with(['instructorProfile.user', 'packages', 'classModel'])
            ->withCount(['customerSchedules as booked_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->orderBy('class_time')
            ->orderBy('id')
            ->get();
    }

    /**
     * Kirim satu pesan gabungan per pelatih untuk semua sesi pada tanggal tertentu.
     * Metode ini sinkron; untuk pengiriman background pakai SendTrainerScheduleReminderJob.
     *
     * @return array{sent:int,failed:int,skipped:int,missing_phone:int,schedules:int}
     */
    public function sendForDate(CarbonInterface|string $date, bool $force = false): array
    {
        $date = Carbon::parse($date)->startOfDay();

        $schedules = $this->schedulesForDate($date);

        $stats = [
            'sent' => 0,
            'failed' => 0,
            'skipped' => 0,
            'missing_phone' => 0,
            'schedules' => $schedules->count(),
        ];

        if ($schedules->isEmpty()) {
            return $stats;
        }

        foreach ($schedules->groupBy(fn (Schedule $schedule) => (string) $schedule->instructor_id) as $group) {
            $result = $this->processInstructorGroup($group->first()->instructorProfile, $group, $date, $force);

            $stats['sent'] += $result['sent'];
            $stats['failed'] += $result['failed'];
            $stats['skipped'] += $result['skipped'];
            $stats['missing_phone'] += $result['missing_phone'];
        }

        Log::info('Trainer schedule reminders processed', [
            'date' => $date->toDateString(),
            'force' => $force,
            'stats' => $stats,
        ]);

        return $stats;
    }

    /**
     * Proses satu pelatih: buat/ambil baris reminder lalu kirim satu pesan gabungan.
     * Idempoten dan aman dari kirim dobel berkat lock + status SENT.
     *
     * @return array{sent:int,failed:int,skipped:int,missing_phone:int}
     */
    public function processInstructorGroup(?Instructor $instructor, Collection $group, CarbonInterface $date, bool $force = false): array
    {
        $stats = ['sent' => 0, 'failed' => 0, 'skipped' => 0, 'missing_phone' => 0];

        if (! $instructor) {
            $this->recordSkipped($group, 'Jadwal belum terhubung ke data pelatih.', $stats);

            return $stats;
        }

        $phone = $instructor->whatsappNumber();

        if (! $phone) {
            $this->recordSkipped($group, 'Nomor WhatsApp pelatih belum diisi.', $stats);
            $this->notifyMissingPhone($instructor, $group, $date, $stats);

            return $stats;
        }

        $lock = Cache::lock(
            'trainer-reminder:' . $instructor->id . ':' . $date->toDateString(),
            60
        );

        if (! $lock->get()) {
            return $stats;
        }

        try {
            $reminders = $group->mapWithKeys(function (Schedule $schedule) use ($instructor, $phone) {
                $reminder = ScheduleReminder::firstOrNew([
                    'schedule_id' => $schedule->id,
                    'type' => ScheduleReminder::TYPE_H1_DAILY,
                    'phone' => $phone,
                ]);

                if (! $reminder->exists) {
                    $reminder->fill([
                        'instructor_id' => $instructor->id,
                        'channel' => 'whatsapp',
                        'status' => ScheduleReminder::STATUS_PENDING,
                    ])->save();
                }

                return [$schedule->id => $reminder];
            });

            $pending = $group->filter(function (Schedule $schedule) use ($reminders, $force) {
                $reminder = $reminders[$schedule->id];

                return $force || $reminder->status !== ScheduleReminder::STATUS_SENT;
            });

            if ($pending->isEmpty()) {
                $stats['skipped'] += $group->count();

                return $stats;
            }

            $message = $this->buildMessage($instructor, $date, $group);

            try {
                $result = $this->whatsApp->send($phone, $message, ['priority' => 'high']);
            } catch (Throwable $exception) {
                $result = [
                    'success' => false,
                    'message' => $exception->getMessage(),
                ];
            }

            if ($result['success'] ?? false) {
                foreach ($group as $schedule) {
                    $reminders[$schedule->id]->markSent($message);
                }

                $stats['sent'] += $group->count();

                return $stats;
            }

            $error = (string) ($result['message'] ?? 'Gagal mengirim WhatsApp.');

            foreach ($group as $schedule) {
                $reminders[$schedule->id]->markFailed($error, $message);
            }

            $stats['failed'] += $group->count();
            $this->notifySendFailure($instructor, $group, $date, $error);

            return $stats;
        } finally {
            $lock->release();
        }
    }

    /**
     * Kirim reminder untuk satu jadwal tertentu ke pelatihnya.
     *
     * @return array{success:bool,message:string}
     */
    public function sendScheduleReminder(Schedule $schedule, bool $force = false): array
    {
        if (! $schedule->exists) {
            return ['success' => false, 'message' => 'Jadwal tidak ditemukan.'];
        }

        $schedule->loadMissing(['instructorProfile.user', 'classModel', 'packages']);
        $schedule->loadCount(['customerSchedules as booked_count' => fn ($query) => $query->where('status', 'confirmed')]);

        $instructor = $schedule->instructorProfile;

        if (! $instructor) {
            return ['success' => false, 'message' => 'Jadwal belum terhubung ke data pelatih.'];
        }

        $phone = $instructor->whatsappNumber();

        if (! $phone) {
            return ['success' => false, 'message' => 'Nomor WhatsApp pelatih belum diisi.'];
        }

        $date = $schedule->schedule_date ? Carbon::parse($schedule->schedule_date) : Carbon::today();

        $reminder = ScheduleReminder::firstOrNew([
            'schedule_id' => $schedule->id,
            'type' => ScheduleReminder::TYPE_H1_DAILY,
            'phone' => $phone,
        ]);

        if ($reminder->exists && $reminder->status === ScheduleReminder::STATUS_SENT && ! $force) {
            return ['success' => true, 'message' => 'Reminder sudah pernah terkirim untuk jadwal ini.'];
        }

        if (! $reminder->exists) {
            $reminder->fill([
                'instructor_id' => $instructor->id,
                'channel' => 'whatsapp',
                'status' => ScheduleReminder::STATUS_PENDING,
            ])->save();
        }

        $message = $this->buildMessage($instructor, $date, collect([$schedule]));

        try {
            $result = $this->whatsApp->send($phone, $message, ['priority' => 'high']);
        } catch (Throwable $exception) {
            $result = ['success' => false, 'message' => $exception->getMessage()];
        }

        if ($result['success'] ?? false) {
            $reminder->markSent($message);

            return ['success' => true, 'message' => 'Reminder terkirim ke ' . $instructor->name . '.'];
        }

        $error = (string) ($result['message'] ?? 'Gagal mengirim WhatsApp.');
        $reminder->markFailed($error, $message);
        $this->notifySendFailure($instructor, collect([$schedule]), $date, $error);

        return ['success' => false, 'message' => $error];
    }

    public function resendStored(ScheduleReminder $reminder): array
    {
        $reminder->loadMissing(['schedule.classModel', 'schedule.packages', 'instructor']);

        $schedule = $reminder->schedule;
        $phone = trim((string) $reminder->phone);

        if (! $schedule || $phone === '') {
            $reminder->markFailed('Nomor WhatsApp atau jadwal tidak valid.');

            return ['success' => false, 'message' => 'Nomor WhatsApp atau jadwal tidak valid.'];
        }

        $instructor = $reminder->instructor ?: $schedule->instructorProfile;
        $message = $reminder->message ?: $this->buildMessage(
            $instructor,
            Carbon::parse($schedule->schedule_date),
            collect([$schedule])
        );

        try {
            $result = $this->whatsApp->send($phone, $message, ['priority' => 'high']);
        } catch (Throwable $exception) {
            $result = ['success' => false, 'message' => $exception->getMessage()];
        }

        if ($result['success'] ?? false) {
            $reminder->markSent($message);

            return $result;
        }

        $reminder->markFailed((string) ($result['message'] ?? 'Gagal mengirim WhatsApp.'), $message);

        return $result;
    }

    private function buildMessage(?Instructor $instructor, CarbonInterface $date, Collection $schedules): string
    {
        $portalUrl = route('trainer.dashboard');

        $details = $schedules
            ->values()
            ->map(function (Schedule $schedule, int $index): string {
                $className = $schedule->schedule_label ?: ($schedule->classModel?->class_name ?: 'Kelas');
                $time = $schedule->class_time ? substr((string) $schedule->class_time, 0, 5) : '-';
                $booked = (int) ($schedule->booked_count ?? 0);
                $capacity = (int) ($schedule->capacity ?? 0);

                return sprintf(
                    '%d. %s | %s | %d/%s member',
                    $index + 1,
                    $className,
                    $time,
                    $booked,
                    $capacity > 0 ? (string) $capacity : '∞'
                );
            })
            ->implode("\n");

        return $this->whatsApp->trainerScheduleReminderMessage([
            'trainer_name' => $instructor?->name ?: 'Pelatih',
            'date' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'schedule_details' => $details,
            'total_sessions' => $schedules->count(),
            'portal_url' => $portalUrl,
        ]);
    }

    private function recordSkipped(Collection $schedules, string $reason, array &$stats): void
    {
        foreach ($schedules as $schedule) {
            $reminder = ScheduleReminder::firstOrNew([
                'schedule_id' => $schedule->id,
                'type' => ScheduleReminder::TYPE_H1_DAILY,
                'phone' => '',
            ]);

            if (! $reminder->exists) {
                $reminder->fill([
                    'instructor_id' => $schedule->instructor_id,
                    'channel' => 'whatsapp',
                ])->save();
            }

            $reminder->markSkipped($reason);
        }

        $stats['skipped'] += $schedules->count();
    }

    private function notifyMissingPhone(Instructor $instructor, Collection $schedules, CarbonInterface $date, array &$stats): void
    {
        $stats['missing_phone'] += $schedules->count();

        $alreadyNotified = AdminNotification::query()
            ->where('type', 'trainer_reminder_missing_phone')
            ->where('related_id', $instructor->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyNotified) {
            return;
        }

        AdminNotification::create([
            'type' => 'trainer_reminder_missing_phone',
            'icon' => 'heroicon-o-device-mobile',
            'color' => 'warning',
            'title' => 'Nomor WA pelatih belum diisi',
            'message' => sprintf(
                'Reminder jadwal %s untuk %s tidak terkirim karena nomor WhatsApp kosong. Lengkapi di menu Pelatih.',
                $date->locale('id')->isoFormat('D MMMM YYYY'),
                $instructor->name
            ),
            'data' => [
                'instructor_id' => $instructor->id,
                'schedule_ids' => $schedules->pluck('id')->all(),
            ],
            'related_id' => $instructor->id,
            'related_type' => Instructor::class,
        ]);
    }

    private function notifySendFailure(Instructor $instructor, Collection $schedules, CarbonInterface $date, string $error): void
    {
        $alreadyNotified = AdminNotification::query()
            ->where('type', 'trainer_reminder_failed')
            ->where('related_id', $instructor->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyNotified) {
            return;
        }

        AdminNotification::create([
            'type' => 'trainer_reminder_failed',
            'icon' => 'heroicon-o-exclamation-circle',
            'color' => 'danger',
            'title' => 'Reminder WA pelatih gagal terkirim',
            'message' => sprintf(
                'Reminder jadwal %s untuk %s gagal dikirim: %s',
                $date->locale('id')->isoFormat('D MMMM YYYY'),
                $instructor->name,
                $error
            ),
            'data' => [
                'instructor_id' => $instructor->id,
                'schedule_ids' => $schedules->pluck('id')->all(),
            ],
            'related_id' => $instructor->id,
            'related_type' => Instructor::class,
        ]);
    }
}