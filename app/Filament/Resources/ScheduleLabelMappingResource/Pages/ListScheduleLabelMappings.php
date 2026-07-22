<?php

namespace App\Filament\Resources\ScheduleLabelMappingResource\Pages;

use App\Filament\Resources\ScheduleLabelMappingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScheduleLabelMappings extends ListRecords
{
    protected static string $resource = ScheduleLabelMappingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Label'),
        ];
    }
}
