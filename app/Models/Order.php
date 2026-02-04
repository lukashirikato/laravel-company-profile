<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'package_id',
        'amount',
        'status',
        'voucher_code',
        'discount',
        'selected_class_id',
        'schedule_ids',
        'quota_applied',
        'payment_type',
        'order_code',
        'expired_at',  // Tambahkan ini
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
        return $this->status === 'paid' && !$this->isExpired();
    }

    /**
     * Dapatkan sisa hari aktif package
     * 
     * @return int|null
     */
    public function getRemainingDays()
    {
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
            return 'Unlimited';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        return $this->expired_at->diffForHumans();
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
        return $query->where('status', 'paid')
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
     * Boot method untuk auto set expired_at saat order status berubah jadi paid
     */
    protected static function booted()
    {
        static::updating(function ($order) {
            // Auto set expired_at ketika status berubah dari selain 'paid' ke 'paid'
            if ($order->isDirty('status') && $order->status === 'paid' && !$order->expired_at) {
                $order->setExpiredDate();
            }
        });
    }
}