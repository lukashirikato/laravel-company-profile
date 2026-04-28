<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerFollowUp extends Model
{
    use HasFactory;

    protected $table = 'customer_follow_ups';
    
    protected $fillable = [
        'customer_id',
        'follow_up_type', // 'whatsapp', 'call', 'email', 'visit'
        'template_used', // 'default', 'promotion', 'newclass', 'checkup'
        'message_sent',
        'notes',
        'followed_up_by', // admin user id
        'result', // 'success', 'no_response', 'reopened', 'pending'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Relasi ke Admin/User yang melakukan follow-up
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'followed_up_by', 'id');
    }

    /**
     * Scope: Get follow-ups untuk customer tertentu
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Get follow-ups bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }

    /**
     * Scope: Get follow-ups dengan hasil tertentu
     */
    public function scopeWithResult($query, $result)
    {
        return $query->where('result', $result);
    }
}
