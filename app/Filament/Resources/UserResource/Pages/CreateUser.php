<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Support\UserRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Pengaman server-side: hanya owner yang boleh membuat akun
     * dengan role admin/owner.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $actor = Auth::user();

        if (! $actor?->isOwner()) {
            $data['role'] = UserRole::USER;
            $data['permissions'] = null;
        }

        if (! array_key_exists('role', $data) || blank($data['role'])) {
            $data['role'] = UserRole::USER;
        }

        if (($data['role'] ?? null) !== UserRole::ADMIN) {
            $data['permissions'] = null;
        }

        return $data;
    }
}
