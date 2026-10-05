<?php

namespace App\Support;

/**
 * Daftar role panel admin beserta hierarkinya.
 *
 *   user  -> tidak punya akses panel
 *   admin -> akses panel penuh (tanpa manajemen akun admin)
 *   owner -> akses admin + manajemen akun/role (level tertinggi)
 */
final class UserRole
{
    public const USER = 'user';
    public const ADMIN = 'admin';
    public const OWNER = 'owner';

    public const ALL = [self::USER, self::ADMIN, self::OWNER];

    private const LABELS = [
        self::USER => 'User',
        self::ADMIN => 'Admin',
        self::OWNER => 'Owner',
    ];

    private const BADGE_COLORS = [
        self::USER => 'secondary',
        self::ADMIN => 'warning',
        self::OWNER => 'success',
    ];

    public static function isValid(?string $role): bool
    {
        return in_array($role, self::ALL, true);
    }

    /**
     * Amankan nilai role: nilai tak dikenal (mis. "member" lama) -> user.
     */
    public static function normalize(?string $role): string
    {
        return self::isValid($role) ? $role : self::USER;
    }

    public static function label(?string $role): string
    {
        $role = self::normalize($role);

        return self::LABELS[$role];
    }

    public static function badgeColor(?string $role): string
    {
        $role = self::normalize($role);

        return self::BADGE_COLORS[$role];
    }

    /**
     * Opsi select (value => label) untuk semua role.
     */
    public static function options(): array
    {
        return self::LABELS;
    }

    /**
     * Opsi role yang boleh diberikan oleh $actor.
     * Admin hanya boleh memberikan role user; owner bebas.
     */
    public static function optionsFor(?string $actor): array
    {
        if ($actor === self::OWNER) {
            return self::options();
        }

        return [self::USER => self::LABELS[self::USER]];
    }
}
