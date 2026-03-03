<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackages extends ListRecords
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Package')
                ->icon('heroicon-o-plus'),
        ];
    }

    // Optional: Add stats widgets
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         PackageResource\Widgets\PackageStatsOverview::class,
    //     ];
    // }
}