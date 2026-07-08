<?php

namespace App\Filament\Resources\CustomerFollowUpResource\Pages;

use App\Filament\Resources\CustomerFollowUpResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListCustomerFollowUps extends ListRecords
{
    protected static string $resource = CustomerFollowUpResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Follow Up'),
            Actions\Action::make('scan_customers')
                ->label('Scan Member')
                ->icon('heroicon-o-search-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Scan Member Perlu Follow Up')
                ->modalSubheading('Akan mendeteksi member yang belum punya paket aktif atau paketnya sudah expired, lalu membuat Follow Up record baru.')
                ->action(function () {
                    $exitCode = \Artisan::call('customer:check-followup');
                    $output = \Artisan::output();

                    if ($exitCode === 0) {
                        Notification::make()
                            ->title('Scan selesai!')
                            ->body($output)
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Scan gagal')
                            ->body($output)
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
