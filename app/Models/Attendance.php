<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Schedule;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'schedule_id',
        'program',
        'location',
        'check_in_time',
        'check_in_at',
        'check_out_at',
        'auto_checkout_at',
        'check_in_type',
        'attendance_status',
        'quota_deducted',
        'duration_minutes',
        'checkout_type',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'auto_checkout_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk filter attendance hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_at', Carbon::today());
    }

    /**
     * Scope untuk filter attendance yang sudah check-out
     */
    public function scopeCheckedOut($query)
    {
        return $query->whereNotNull('check_out_at');
    }

    /**
     * Scope untuk filter attendance yang aktif (belum check-out)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('check_out_at');
    }

    /**
     * Scope untuk filter berdasarkan customer
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope untuk filter berdasarkan QR check-in
     */
    public function scopeQRCheckin($query)
    {
        return $query->where('check_in_type', 'qr');
    }

    // ==================== METHODS ====================

    /**
     * Check if customer is currently checked in
     */
    public function isCheckedIn(): bool
    {
        return $this->check_in_at !== null && $this->check_out_at === null;
    }

    /**
     * Get duration of attendance in minutes
     */
    public function getDurationInMinutes(): ?int
    {
        // If stored in DB, use that first
        if ($this->duration_minutes !== null) {
            return (int) $this->duration_minutes;
        }

        if (!$this->check_in_at || !$this->check_out_at) {
            return null;
        }

        return (int) $this->check_out_at->diffInMinutes($this->check_in_at);
    }

    /**
     * Get duration in seconds (more precise)
     */
    public function getDurationInSeconds(): ?int
    {
        if (!$this->check_in_at || !$this->check_out_at) {
            return null;
        }

        return (int) $this->check_out_at->diffInSeconds($this->check_in_at);
    }

    /**
     * Calculate and store duration on check-out
     */
    public function calculateAndStoreDuration(): ?int
    {
        if (!$this->check_in_at || !$this->check_out_at) {
            return null;
        }

        $minutes = (int) $this->check_out_at->diffInMinutes($this->check_in_at);
        $this->update(['duration_minutes' => $minutes]);

        return $minutes;
    }

    /**
     * Format duration untuk display
     */
    public function getFormattedDuration(): string
    {
        $minutes = $this->getDurationInMinutes();

        if ($minutes === null) {
            return 'Belum check-out';
        }

        if ($minutes === 0) {
            // Show seconds for very short sessions
            $seconds = $this->getDurationInSeconds();
            if ($seconds !== null && $seconds > 0) {
                return "< 1 menit ({$seconds}s)";
            }
            return '< 1 menit';
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours} jam {$mins} menit";
        }

        return "{$mins} menit";
    }

    /**
     * Get a short formatted duration (e.g., "1h 30m")
     */
    public function getShortDuration(): string
    {
        $minutes = $this->getDurationInMinutes();

        if ($minutes === null) {
            return '-';
        }

        if ($minutes === 0) {
            return '< 1m';
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }

    // ==================== NEW QR CHECKOUT FEATURES ====================

    /**
     * Check apakah attendance masih dalam status ACTIVE (sudah check-in, belum check-out)
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->check_in_at !== null && $this->check_out_at === null;
    }

    /**
     * Check apakah sudah lewat auto_checkout_at
     */
    public function getIsAutoCheckoutDueAttribute(): bool
    {
        if (!$this->auto_checkout_at || $this->check_out_at !== null) {
            return false;
        }

        return now()->isAfter($this->auto_checkout_at);
    }

    /**
     * Get elapsed minutes dari check_in sampai sekarang
     */
    public function getElapsedMinutesAttribute(): ?int
    {
        if (!$this->check_in_at) {
            return null;
        }

        return (int) now()->diffInMinutes($this->check_in_at);
    }

    /**
     * Check apakah member check-in dalam time window yang valid
     * Window: class_time sampai +30 menit (member boleh check-in mulai kelas dimulai)
     * 
     * @return bool
     */
    public function isWithinTimeWindow(): bool
    {
        if (!$this->schedule) {
            return true; // Jika tidak ada jadwal, allow
        }

        return $this->schedule->isWithinTimeWindow();
    }

    /**
     * Perform auto-checkout ketika sudah 60 menit atau melewati auto_checkout_at
     * 
     * @return bool True jika berhasil auto-checkout
     */
    public function performAutoCheckout(): bool
    {
        if ($this->check_out_at !== null) {
            return false; // Sudah checkout
        }

        try {
            $this->update([
                'check_out_at' => $this->auto_checkout_at ?? now(),
                'checkout_type' => 'auto',
                'duration_minutes' => 60,
            ]);

            \Log::info('✅ Auto-checkout performed', [
                'attendance_id' => $this->id,
                'customer_id' => $this->customer_id,
                'auto_checkout_at' => $this->auto_checkout_at,
                'duration' => 60,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('❌ Error performing auto-checkout', [
                'attendance_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Perform manual checkout (staff/admin checkout sebelum 60 menit)
     * 
     * @return bool True jika berhasil checkout
     */
    public function performManualCheckout(): bool
    {
        if ($this->check_out_at !== null) {
            return false; // Sudah checkout
        }

        try {
            $checkOutTime = now();
            $durationMinutes = (int) $checkOutTime->diffInMinutes($this->check_in_at);

            $this->update([
                'check_out_at' => $checkOutTime,
                'checkout_type' => 'manual',
                'duration_minutes' => $durationMinutes,
            ]);

            \Log::info('✅ Manual checkout performed', [
                'attendance_id' => $this->id,
                'customer_id' => $this->customer_id,
                'duration' => $durationMinutes,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('❌ Error performing manual checkout', [
                'attendance_id' => $this->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check apakah attendance sudah check-in pada schedule yang sama hari ini
     * 
     * @param int $customerId
     * @param int $scheduleId
     * @return bool
     */
    public static function hasCheckedInToday(int $customerId, int $scheduleId): bool
    {
        return self::where('customer_id', $customerId)
            ->where('schedule_id', $scheduleId)
            ->whereDate('check_in_at', Carbon::today())
            ->exists();
    }
}
