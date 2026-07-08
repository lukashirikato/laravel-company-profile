<?php

namespace App\Filament\Resources\ClassGroupResource\Pages;

use App\Filament\Resources\ClassGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassGroups extends ListRecords
{
    protected static string $resource = ClassGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Group'),
        ];
    }
}
