<?php

namespace App\Casts;

use App\Support\UserRole;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

/**
 * Cast aman untuk kolom `role`.
 * Nilai lama (mis. "member") atau nilai tak dikenal otomatis
 * dinormalisasi menjadi "user" sehingga tidak pernah
 * melempar error saat model di-hydrate.
 */
class UserRoleCast implements CastsAttributes
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        return UserRole::normalize((string) $value);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value) || is_numeric($value)) {
            return UserRole::normalize((string) $value);
        }

        throw new InvalidArgumentException("Format role tidak valid untuk kolom {$key}.");
    }
}
