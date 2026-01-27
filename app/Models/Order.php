<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
];


    protected $casts = [
        'schedule_ids'  => 'array',
        'quota_applied'=> 'boolean',
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


    
}
