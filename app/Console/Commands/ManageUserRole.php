<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\UserRole;
use Illuminate\Console\Command;

class ManageUserRole extends Command
{
    protected $signature = 'user:role
        {email : Email akun yang dikelola}
        {role? : Role baru (user|admin|owner). Kosongkan untuk melihat role saat ini}';

    protected $description = 'Melihat atau mengubah role akun panel admin (user, admin, owner)';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Akun dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        $role = $this->argument('role');

        if ($role === null) {
            $this->info(sprintf('%s (%s) saat ini berrole: %s', $user->name, $user->email, $user->roleLabel()));

            return self::SUCCESS;
        }

        $role = strtolower(trim($role));

        if (! UserRole::isValid($role)) {
            $this->error('Role tidak valid. Gunakan: user, admin, atau owner.');

            return self::FAILURE;
        }

        if ($user->role === $role) {
            $this->warn('Role akun tersebut sudah sama, tidak ada perubahan.');

            return self::SUCCESS;
        }

        // Jangan turunkan owner terakhir agar panel tidak kehilangan owner.
        if ($user->isOwner()
            && $role !== UserRole::OWNER
            && User::where('role', UserRole::OWNER)->count() <= 1) {
            $this->error('Tidak bisa menurunkan role owner terakhir. Tambahkan owner lain terlebih dahulu.');

            return self::FAILURE;
        }

        $user->role = $role;
        $user->save();

        $this->info(sprintf(
            'Role %s (%s) berhasil diubah menjadi: %s',
            $user->name,
            $user->email,
            $user->roleLabel()
        ));

        return self::SUCCESS;
    }
}
