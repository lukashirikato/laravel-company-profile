<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Package
 *
 * @property int $id
 * @property string $name
 * @property int|float $price
 * @property string|null $slug
 * @property int|null $duration
 * @property string|null $description
 */
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'quota',
        'class_id', // ← Tambahkan ini
        'is_exclusive',
        'requires_schedule',
        'duration_days',
    ];


    public function schedules()
{
    return $this->belongsToMany(
        Schedule::class,
        'package_schedules'
    );
}

    /**
     * Relasi Package -> Order
     * Satu package bisa punya banyak order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Optional: Jika kamu punya relasi ke tabel kelas/program
     * dan ingin mengambil kelas berdasarkan package_id.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'package_id');
    }

        // Tambahkan relasi
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

}
