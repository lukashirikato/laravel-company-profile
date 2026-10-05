<?php

namespace App\Policies;

use App\Models\User;
use App\Support\UserRole;

/**
 * Otorisasi manajemen akun panel admin.
 *
 * | Aksi            | User | Admin                    | Owner |
 * |-----------------|------|--------------------------|-------|
 * | Lihat daftar    |  -   | ✅                        | ✅    |
 * | Buat akun       |  -   | ✅ (hanya role user)      | ✅    |
 * | Ubah akun       |  -   | ✅ (hanya akun role user) | ✅    |
 * | Hapus akun      |  -   |  -                       | ✅    |
 */
class UserPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null && $user->isOwner();
    }

    public function view(?User $user, User $model): bool
    {
        return $user !== null && $user->isOwner();
    }

    public function create(?User $user): bool
    {
        return $user !== null && $user->isOwner();
    }

    public function update(?User $user, User $model): bool
    {
        if ($user === null || ! $user->isOwner()) {
            return false;
        }

        return true;
    }

    public function delete(?User $user, User $model): bool
    {
        if ($user === null || ! $user->isOwner()) {
            return false;
        }

        // Tidak boleh menghapus akun sendiri.
        if ($user->id === $model->id) {
            return false;
        }

        // Tidak boleh menghapus owner terakhir (mencegah panel terkunci).
        if ($model->isOwner() && User::where('role', UserRole::OWNER)->count() <= 1) {
            return false;
        }

        return true;
    }

    public function deleteAny(?User $user): bool
    {
        return $user !== null && $user->isOwner();
    }
}
