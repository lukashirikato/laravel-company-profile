<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Attendance;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * === PRIMARY KEY FIX UNTUK FILAMENT ===
     * Filament WAJIB menggunakan kolom 'id' sebagai primary key.
     * Jika sebelumnya kamu menggunakan 'user_id', ubah tabelnya menjadi:
     *   user_id → id (auto increment)
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

   protected $fillable = [
    'name',
    'email',
    'phone_number',
    'program',
    'quota',
    'password',
    'birth_date',
    'goals',
    'kondisi_khusus',
    'referensi',
    'pengalaman',
    'package_id', 
    'is_muslim',
    'voucher_code',
    'is_verified',
    'force_password_change',
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'quota' => 'integer',
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'force_password_change' => 'boolean',
    ];

    // =====================================================
    // ================      RELASI        =================
    // =====================================================

    public function schedules()
    {
        return $this->belongsToMany(
            Schedule::class,
            'customer_schedules',
            'customer_id',
            'schedule_id'
        )->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'customer_id', 'id');
    }
}
