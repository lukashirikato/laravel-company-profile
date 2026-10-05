<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Support\UserRole;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Pengaman server-side saat update:
     * 1. Role akun sendiri tidak bisa diubah (mencegah lockout).
     * 2. Admin dipaksa mempertahankan role target.
     * 3. Owner terakhir tidak bisa diturunkan.
     */
    protected function mutateFormDataBeforeUpdate(array $data): array
    {
        $actor = Auth::user();
        $record = $this->getRecord();
        $newRole = $data['role'] ?? null;

        if ($actor !== null && $actor->id === $record->id) {
            $data['role'] = $record->role;

            return $data;
        }

        if (! $actor?->isOwner()) {
            $data['role'] = UserRole::USER;

            return $data;
        }

        $isLastOwner = $record->isOwner()
            && $newRole !== UserRole::OWNER
            && User::where('role', UserRole::OWNER)->count() <= 1;

        if ($isLastOwner) {
            $data['role'] = UserRole::OWNER;
        }

        if (($data['role'] ?? null) !== UserRole::ADMIN) {
            $data['permissions'] = null;
        }

        return $data;
    }
}
