<?php

namespace App\Models;

use App\Casts\UserRoleCast;
use App\Support\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => UserRoleCast::class,
        'permissions' => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Cek apakah user memiliki hak akses / izin tertentu.
     * Owner otomatis memiliki semua izin.
     * Admin dicek berdasarkan permissions yang diberikan Owner.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isOwner()) {
            return true;
        }

        if (! $this->isAdmin()) {
            return false;
        }

        $perms = $this->permissions;

        if (! is_array($perms)) {
            return false;
        }

        return in_array($permission, $perms, true);
    }

    /**
     * Hanya admin dan owner yang boleh masuk ke panel /admin (Filament).
     */
    public function canAccessFilament(): bool
    {
        return $this->isAdmin();
    }

    public function isOwner(): bool
    {
        return $this->role === UserRole::OWNER;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN || $this->isOwner();
    }

    public function isUser(): bool
    {
        return UserRole::normalize($this->role) === UserRole::USER;
    }

    public function roleLabel(): string
    {
        return UserRole::label($this->role);
    }

    /**
     * Otomatis hash password jika belum di-hash.
     */
    public function setPasswordAttribute($value): void
    {
        if (filled($value)) {
            $this->attributes['password'] = \Illuminate\Support\Facades\Hash::needsRehash($value)
                ? \Illuminate\Support\Facades\Hash::make($value)
                : $value;
        }
    }
}
