<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Voucher
 *
 * @property int $id
 * @property string $code
 * @property string $type (percent|nominal)
 * @property float $value
 * @property float|null $max_discount
 * @property int|null $usage_limit
 * @property int $used_count
 * @property \Carbon\Carbon|null $valid_from
 * @property \Carbon\Carbon|null $valid_until
 * @property bool $active
 * @property string $applicable_to (all|specific)
 */
class Voucher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'active',
        'applicable_to',
    ];
    
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'active' => 'boolean',
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Relasi Many-to-Many: Voucher bisa berlaku untuk banyak packages
     */
    public function packages()
    {
        return $this->belongsToMany(
            Package::class,
            'package_voucher',
            'voucher_id',
            'package_id'
        )->withTimestamps();
    }

    /**
     * Relasi ke Orders yang menggunakan voucher ini
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id');
    }

    /**
     * ✅ Cek apakah voucher berlaku untuk package tertentu
     *
     * @param int $packageId
     * @return bool
     */
    public function isApplicableToPackage($packageId): bool
    {
        // Jika berlaku untuk semua package
        if ($this->applicable_to === 'all') {
            return true;
        }

        // Jika berlaku untuk package spesifik, cek di relasi
        return $this->packages()->where('package_id', $packageId)->exists();
    }

    /**
     * ✅ Validasi apakah voucher masih bisa digunakan
     *
     * @param int|null $packageId
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validate($packageId = null): array
    {
        // Cek status active
        if (!$this->active) {
            return [
                'valid' => false,
                'message' => 'Kode voucher tidak valid'
            ];
        }

        // Cek periode berlaku (valid_from)
        if ($this->valid_from && now()->lt($this->valid_from)) {
            return [
                'valid' => false,
                'message' => 'Voucher belum bisa digunakan'
            ];
        }

        // Cek periode berlaku (valid_until)
        if ($this->valid_until && now()->gt($this->valid_until)) {
            return [
                'valid' => false,
                'message' => 'Voucher sudah kadaluarsa'
            ];
        }

        // Cek batas penggunaan
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Voucher sudah mencapai batas pemakaian'
            ];
        }

        // Cek apakah berlaku untuk package ini
        if ($packageId && !$this->isApplicableToPackage($packageId)) {
            return [
                'valid' => false,
                'message' => 'Voucher tidak berlaku untuk paket ini'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Voucher valid'
        ];
    }

    /**
     * ✅ Hitung nilai diskon berdasarkan harga
     *
     * @param float $price
     * @return float
     */
    public function calculateDiscount($price): float
    {
        if ($this->type === 'percent') {
            $discount = ($price * $this->value) / 100;
            
            // Jika ada max_discount, gunakan yang terkecil
            if ($this->max_discount) {
                return min($discount, $this->max_discount);
            }
            
            return $discount;
        }

        // Type nominal
        return min($this->value, $price);
    }

    /**
     * ✅ Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope: Voucher yang sedang aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope: Voucher yang sedang berlaku (dalam periode valid)
     */
    public function scopeCurrentlyValid(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', now());
        })->where(function ($q) {
            $q->whereNull('valid_until')
              ->orWhere('valid_until', '>=', now());
        });
    }

    /**
     * Scope: Voucher yang masih bisa digunakan (belum mencapai limit)
     */
    public function scopeNotExhausted(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereColumn('used_count', '<', 'usage_limit');
        });
    }

    /**
     * Scope: Find by code (case insensitive)
     */
    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->whereRaw('UPPER(code) = ?', [strtoupper(trim($code))]);
    }

    /**
     * Auto uppercase code saat save
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voucher) {
            $voucher->code = strtoupper($voucher->code);
        });

        static::updating(function ($voucher) {
            $voucher->code = strtoupper($voucher->code);
        });
    }
}