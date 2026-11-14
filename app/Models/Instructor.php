<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'specialty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'instructor_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(InstructorRating::class, 'instructor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function whatsappNumber(): ?string
    {
        $phone = trim((string) ($this->phone ?: $this->user?->phone));

        return $phone !== '' ? $phone : null;
    }

    public function whatsappUrl(): ?string
    {
        $phone = $this->whatsappNumber();

        if (! $phone) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (! str_starts_with($digits, '62')) {
            $digits = '62' . $digits;
        }

        return 'https://wa.me/' . $digits;
    }
}
