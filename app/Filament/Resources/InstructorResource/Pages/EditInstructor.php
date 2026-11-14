<?php

namespace App\Filament\Resources\InstructorResource\Pages;

use App\Filament\Resources\InstructorResource;
use App\Models\User;
use App\Support\UserRole;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditInstructor extends EditRecord
{
    protected static string $resource = InstructorResource::class;

    protected array $account = ['email' => null, 'password' => null];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['login_email'] = $this->record->user?->email;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->account = [
            'email' => $data['login_email'] ?? null,
            'password' => $data['login_password'] ?? null,
        ];

        unset($data['login_email'], $data['login_password']);

        // Jika admin mengganti tautan akun lewat dropdown, jangan sentuh kredensial.
        if ((int) ($data['user_id'] ?? 0) !== (int) ($this->record->user_id ?? 0)) {
            $this->account = ['email' => null, 'password' => null];
        }

        $targetUserId = $data['user_id'] ?? $this->record->user_id;

        if (filled($this->account['email'])) {
            $duplicate = User::query()
                ->where('email', $this->account['email'])
                ->when($targetUserId, fn ($query) => $query->whereKeyNot($targetUserId))
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'data.login_email' => 'Email ini sudah dipakai akun lain.',
                ]);
            }
        }

        if (empty($data['user_id']) && filled($this->account['email'])) {
            $data['user_id'] = $this->resolveTrainerUser(
                (string) $this->account['email'],
                $this->account['password'],
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

    protected function afterSave(): void
    {
        $this->record->schedules()->update(['instructor' => $this->record->name]);
        $this->record->ratings()->update(['instructor_name' => $this->record->name]);

        $user = $this->record->user;

        if (! $user) {
            return;
        }

        if (filled($this->account['email'])) {
            $user->email = $this->account['email'];
        }

        if (filled($this->account['password'])) {
            $user->password = $this->account['password'];
        }

        if (filled($this->record->name)) {
            $user->name = $this->record->name;
        }

        if (filled($this->record->phone)) {
            $user->phone = $this->record->phone;
        }

        $user->save();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}