<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class_models';

    protected $fillable = [
        'class_name',
        'level',
        'class_group_id',
    ];

    protected $casts = [
        'class_name' => 'string',
        'level'      => 'string',
    ];

    /**
     * 1 Class bisa punya banyak jadwal
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    /**
     * 1 Class milik 1 Group (Mix Class 1 / 2 / 3 / 4)
     */
    public function group()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id');
    }

    
    /**
     * Accessor nama lengkap
     */
    public function getFullNameAttribute()
    {
        return trim($this->class_name . ' – ' . $this->level);
    }
}
