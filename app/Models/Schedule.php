<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassModel; // 
use App\Models\Customer;
use App\Models\Package;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'class_id',
        'day',
        'class_time',
        'instructor',
        'show_on_landing',
    ];

    protected $casts = [
        'show_on_landing' => 'boolean',
    ];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function packages()
    {
        return $this->belongsToMany(
            Package::class,
            'package_schedules',
            'schedule_id',
            'package_id'
        );
    }

    public function customers()
    {
        return $this->belongsToMany(
            Customer::class,
            'customer_schedules',
            'schedule_id',
            'customer_id'
        )
        ->withPivot(['order_id', 'status', 'joined_at'])
        ->withTimestamps();
    }
}
