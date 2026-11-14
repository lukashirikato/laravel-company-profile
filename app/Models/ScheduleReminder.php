<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleReminder extends Model
{
    use HasFactory;

    public const TYPE_H1_DAILY = 'h1_daily';

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'schedule_id',
        'instructor_id',
        'type',
        'channel',
        'phone',
        'status',
        'attempts',
        'message',
        'error',
        'sent_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function markSent(string $message): void
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'attempts' => $this->attempts + 1,
            'message' => $message,
            'error' => null,
            'sent_at' => now(),
        ]);
    }

    public function markFailed(string $error, ?string $message = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'attempts' => $this->attempts + 1,
            'message' => $message ?? $this->message,
            'error' => $error,
        ]);
    }

    public function markSkipped(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_SKIPPED,
            'error' => $reason,
        ]);
    }
}
