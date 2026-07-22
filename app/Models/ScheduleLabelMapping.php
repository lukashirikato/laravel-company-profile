<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleLabelMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'class_group_id',
    ];

    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id');
    }
}
