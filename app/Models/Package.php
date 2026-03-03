<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Package
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $price
 * @property int $quota
 * @property int|null $class_id
 * @property bool $is_exclusive
 * @property bool $requires_schedule
 * @property int|null $duration_days
 * @property string|null $description
 * @property string|null $duration
 * @property string|null $schedule_mode
 * @property int|null $default_schedule_id
 * @property bool $auto_apply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'quota',
        'class_id',
        'is_exclusive',
        'requires_schedule',
        'duration_days',
        'description',
        'duration',
        'schedule_mode',
        'default_schedule_id',
        'auto_apply',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_exclusive' => 'boolean',
        'requires_schedule' => 'boolean',
        'auto_apply' => 'boolean',
    ];

    public function schedules()
    {
        return $this->belongsToMany(
            Schedule::class,
            'package_schedules'
        );
    }

    // ✅ TAMBAHKAN INI - Relasi ke Vouchers
    public function vouchers()
    {
        return $this->belongsToMany(
            Voucher::class,
            'package_voucher',
            'package_id',
            'voucher_id'
        )->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'package_id');
    }

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function defaultSchedule()
    {
        return $this->belongsTo(Schedule::class, 'default_schedule_id');
    }

    // ✅ TAMBAHKAN INI - Helper method
    public function getAvailableVouchers()
    {
        return Voucher::where(function ($query) {
            $query->where('applicable_to', 'all')
                  ->orWhereHas('packages', function ($q) {
                      $q->where('package_id', $this->id);
                  });
        })
        ->where('active', 1)
        ->where(function ($q) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>=', now());
        })
        ->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereColumn('used_count', '<', 'usage_limit');
        })
        ->get();
    }
}