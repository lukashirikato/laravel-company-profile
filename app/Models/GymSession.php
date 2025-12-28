<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'check_in_at',
        'check_out_at',
        'qr_code',
    ];
}
