<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSchedule extends Model
{
    protected $table = 'customer_schedules';

    protected $fillable = [
        'customer_id',
        'schedule_id',
        'order_id',
        'status',
        'joined_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
