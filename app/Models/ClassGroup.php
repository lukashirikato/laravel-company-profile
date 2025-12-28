<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;

    protected $table = 'class_groups';

    protected $fillable = [
        'name',
        'level',
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'class_group_id');
    }
}
