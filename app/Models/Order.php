<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\CustomerSchedule;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $customer_name
 * @property int $package_id
 * @property string|null $selected_class_id
 * @property string $order_code
 * @property int $amount
 * @property int $discount
 * @property bool $quota_applied
 * @property string|null $payment_type
 * @property string|null $transaction_id
 * @property string|null $voucher_code
 * @property array|null $schedule_ids
 * @property string $status
 * @property \Carbon\Carbon|null $expired_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'package_id',
        'selected_class_id',
        'order_code',
        'amount',
        'discount',
        'quota_applied',
        'payment_type',
        'transaction_id',
        'voucher_code',
        'schedule_ids',
        'status',
        'expired_at',
        'remaining_quota',
        'remaining_classes',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'schedule_ids'  => 'array',
        'quota_applied' => 'boolean',
        'expired_at'    => 'datetime',  // Tambahkan ini untuk auto cast ke Carbon
    ];

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Relasi ke Transaction
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id');
    }

    /**
     * Cek apakah package sudah expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        // Jika tidak ada expired_at, berarti unlimited/tidak expired
        if (!$this->expired_at) {
            return false;
        }

        return Carbon::now()->gt($this->expired_at);
    }

    /**
     * Cek apakah package masih aktif
     * 
     * @return bool
     */
    public function isActive()
    {
        // Treat several statuses as active/valid for usage
        $activeStatuses = ['paid', 'active', 'settlement', 'success'];
        return in_array($this->status, $activeStatuses) && !$this->isExpired();
    }

    /**
     * Dapatkan sisa hari aktif package
     * 
     * @return int|null
     */
    public function getRemainingDays()
    {
        // Jika expired_at belum di-set tapi package punya duration_days → belum dimulai, return full days
        if (!$this->expired_at && $this->package && $this->package->duration_days) {
            return $this->package->duration_days;
        }

        if (!$this->expired_at || $this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->expired_at, false);
    }

    /**
     * Dapatkan sisa waktu dalam format human readable
     * 
     * @return string|null
     */
    public function getRemainingTime()
    {
        if (!$this->expired_at) {
            // Jika package punya duration_days → belum dimulai (menunggu booking pertama)
            if ($this->package && $this->package->duration_days) {
                // Cek apakah ini exclusive package
                if ($this->package->is_exclusive) {
                    return 'Belum dimulai — aktif dari jadwal pertama';
                }
                return 'Belum dimulai — aktif saat booking pertama';
            }
            return 'Unlimited';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        // Jika expired_at sudah di-set tapi masa aktif belum dimulai (tanggal di masa depan)
        // Khusus exclusive package — tampilkan info kapan mulai aktif
        if ($this->package && $this->package->is_exclusive) {
            $firstScheduleDate = $this->getFirstScheduleDate();
            if ($firstScheduleDate && Carbon::now()->lt($firstScheduleDate)) {
                return 'Aktif mulai ' . $firstScheduleDate->translatedFormat('d M Y');
            }
        }

        return $this->expired_at->diffForHumans();
    }

    /**
     * Check apakah masa aktif order sudah dimulai (expired_at sudah di-set)
     * 
     * @return bool
     */
    public function isActivated()
    {
        return $this->expired_at !== null;
    }

    /**
     * Dapatkan tanggal jadwal pertama untuk exclusive package
     * 
     * @return \Carbon\Carbon|null
     */
    public function getFirstScheduleDate()
    {
        $firstDate = CustomerSchedule::where('order_id', $this->id)
            ->join('schedules', 'customer_schedules.schedule_id', '=', 'schedules.id')
            ->whereNotNull('schedules.schedule_date')
            ->orderBy('schedules.schedule_date', 'asc')
            ->value('schedules.schedule_date');

        return $firstDate ? Carbon::parse($firstDate) : null;
    }

    /**
     * Set expired_at berdasarkan duration package
     * 
     * @return void
     */
    public function setExpiredDate()
    {
        if ($this->package && $this->package->duration_days) {
            $this->expired_at = Carbon::now()->addDays($this->package->duration_days);
            $this->save();
        }
    }

    /**
     * Scope untuk filter order yang masih aktif
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
                $activeStatuses = ['paid', 'active', 'settlement', 'success'];
                return $query->whereIn('status', $activeStatuses)
                                         ->where(function($q) {
                                                 $q->whereNull('expired_at')
                                                     ->orWhere('expired_at', '>', Carbon::now());
                                         });
    }

    /**
     * Scope untuk filter order yang sudah expired
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'paid')
                     ->whereNotNull('expired_at')
                     ->where('expired_at', '<=', Carbon::now());
    }

    /**
     * Scope untuk filter berdasarkan customer
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $customerId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Boot method untuk auto set expired_at & remaining_quota & remaining_classes saat order dibuat/status paid
     * 
     * BEDAKAN:
     * - remaining_classes = jumlah kelas yang bisa di-book (NOT berkurang saat check-in)
     * - remaining_quota = jumlah check-in/checkout yang tersisa (berkurang saat check-in)
     */
    protected static function booted()
    {
        // When order is created
        static::created(function ($order) {
            // Auto set remaining_quota dan remaining_classes dari package quota jika order berada di status aktif
            $activeStatuses = ['paid', 'active', 'settlement', 'success'];
            if (in_array($order->status, $activeStatuses) && $order->package) {
                $order->update([
                    'remaining_quota' => $order->package->quota,
                    'remaining_classes' => $order->package->quota, // Sama dengan quota, tapi digunakan untuk booking
                ]);
            }

            // ❌ TIDAK lagi auto-set expired_at saat order dibuat
            // expired_at akan di-set saat BOOKING PERTAMA di MemberBookingController::store()
        });

        // When order is updated
        static::updating(function ($order) {
            // Auto set remaining_quota dan remaining_classes ketika status berubah menjadi salah satu status aktif
            $activeStatuses = ['paid', 'active', 'settlement', 'success'];
            if ($order->isDirty('status') && in_array($order->status, $activeStatuses) && $order->package) {
                if (!$order->remaining_quota) {
                    $order->remaining_quota = $order->package->quota;
                }
                if (!$order->remaining_classes) {
                    $order->remaining_classes = $order->package->quota; // Set remaining_classes untuk booking
                }
            }

            // ❌ TIDAK lagi auto-set expired_at saat status berubah
            // expired_at akan di-set saat BOOKING PERTAMA di MemberBookingController::store()
        });
    }

    /**
     * Get remaining quota percentage
     * 
     * @return int
     */
    public function getRemainingQuotaPercentage()
    {
        if (!$this->package || !$this->package->quota) {
            return 0;
        }

        return round(($this->remaining_quota / $this->package->quota) * 100);
    }

    /**
     * Check apakah masih ada quota
     * 
     * @return bool
     */
    public function hasRemainingQuota()
    {
        return $this->remaining_quota > 0;
    }

    /**
     * Get quota usage info
     * 
     * @return array
     */
    public function getQuotaInfo()
    {
        return [
            'total' => $this->package->quota ?? 0,
            'remaining' => $this->remaining_quota ?? 0,
            'used' => ($this->package->quota ?? 0) - ($this->remaining_quota ?? 0),
            'percentage' => $this->getRemainingQuotaPercentage(),
            'has_quota' => $this->hasRemainingQuota(),
        ];
    }

}