<?php

namespace App\Filament\Resources\CustomerScheduleResource\Pages;

use App\Filament\Resources\CustomerScheduleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerSchedule extends CreateRecord
{
    protected static string $resource = CustomerScheduleResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Customer Schedule berhasil dibuat')
            ->body('Data Customer Schedule sudah disimpan dan tersedia di daftar.');
    }
}
