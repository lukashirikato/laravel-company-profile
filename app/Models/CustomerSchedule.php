<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'schedule_id',
        'order_id',      // ✅ IMPORTANT: order_id untuk matching package
        'status',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * ✅ RELASI ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * ✅ RELASI ke Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * ✅ RELASI ke Order (CRITICAL untuk package matching)
     * Ini adalah relasi paling penting untuk menentukan package mana yang dipakai
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope untuk filter hanya schedule yang confirmed
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope untuk filter berdasarkan customer
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}