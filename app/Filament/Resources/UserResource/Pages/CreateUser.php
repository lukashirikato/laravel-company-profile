<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Instructor;
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

    /**
     * Akun ber-role Pelatih otomatis dibuatkan profil Instructor agar
     * langsung bisa dipakai di portal /pelatih.
     */
    protected function afterCreate(): void
    {
        if (! $this->record->isTrainer()) {
            return;
        }

        Instructor::query()->firstOrCreate(
            ['user_id' => $this->record->id],
            [
                'name' => $this->record->name,
                'phone' => $this->record->phone,
                'is_active' => true,
            ]
        );
    }
}
