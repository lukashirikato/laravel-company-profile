<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Guard name opsional, hanya jika kamu pakai multiple guard
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // jika kamu pakai sistem role admin
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // jika kamu pakai verifikasi email
    ];
}
