<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ClassModel;
use App\Models\Customer;
use App\Models\Package;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    // ✅ TAMBAHKAN SEMUA KOLOM YANG ADA DI DATABASE
    protected $fillable = [
        'package_id',        // ✅ TAMBAH INI (ada di tinker output)
        'class_id',
        'schedule_label',    // ✅ TAMBAH INI (ada di tinker output)
        'day',
        'class_time',
        'instructor',
        'show_on_landing',
    ];

    protected $casts = [
        'show_on_landing' => 'boolean',
        'class_time' => 'datetime:H:i:s',  // ✅ TAMBAH INI untuk format waktu
    ];

    // ✅ TAMBAH APPENDS untuk accessor
    protected $appends = ['class_name'];

    /**
     * Accessor untuk class_name
     * Karena Blade template butuh $schedule->class_name
     */
    public function getClassNameAttribute()
    {
        // Priority: schedule_label > classModel->class_name
        return $this->schedule_label 
            ?? ($this->classModel ? $this->classModel->class_name : null) 
            ?? 'N/A';
    }

    /**
     * Relationships
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
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

    /**
     * Scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('show_on_landing', 1);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
}