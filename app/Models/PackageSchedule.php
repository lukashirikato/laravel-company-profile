<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageSchedule extends Model
{
    use HasFactory;

    protected $table = 'package_schedules' ;

    /**
     * Kolom yang boleh diisi (penting untuk create / seed)
     */
    protected $fillable = [
        'package_id',
        'schedule_id',
    ];


    
    /**
     * Relasi ke Schedule
     * package_schedules.schedule_id → schedules.id
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * (Optional tapi rapi)
     * Relasi ke Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
