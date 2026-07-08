<?php

namespace App\Filament\Resources\ClassModelResource\Pages;

use App\Filament\Resources\ClassModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassModels extends ListRecords
{
    protected static string $resource = ClassModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Kelas'),
        ];
    }
}
