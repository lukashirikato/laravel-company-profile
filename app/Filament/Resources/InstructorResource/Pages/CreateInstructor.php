<?php

namespace App\Filament\Resources\InstructorResource\Pages;

use App\Filament\Resources\InstructorResource;
use App\Models\User;
use App\Support\UserRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateInstructor extends CreateRecord
{
    protected static string $resource = InstructorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $email = $data['login_email'] ?? null;
        $password = $data['login_password'] ?? null;

        unset($data['login_email'], $data['login_password']);

        if (empty($data['user_id']) && filled($email)) {
            $data['user_id'] = $this->resolveTrainerUser(
                (string) $email,
                $password,
                $data['name'] ?? null,
                $data['phone'] ?? null
            );
        }

        return $data;
    }

    protected function resolveTrainerUser(string $email, ?string $password, ?string $name, ?string $phone): int
    {
        $existing = User::query()->where('email', $email)->first();

        if ($existing) {
            if (! $existing->isTrainer()) {
                throw ValidationException::withMessages([
                    'data.login_email' => 'Email ini sudah dipakai akun non-pelatih. Gunakan email lain.',
                ]);
            }

            if (filled($password)) {
                $existing->password = $password;
            }

            if (filled($name)) {
                $existing->name = $name;
            }

            if (filled($phone)) {
                $existing->phone = $phone;
            }

            $existing->save();

            return $existing->id;
        }

        if (blank($password)) {
            throw ValidationException::withMessages([
                'data.login_password' => 'Password wajib diisi untuk membuat akun pelatih baru.',
            ]);
        }

        $user = User::query()->create([
            'name' => $name ?: 'Pelatih',
            'email' => $email,
            'phone' => $phone,
            'role' => UserRole::TRAINER,
            'password' => $password,
        ]);

        return $user->id;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}