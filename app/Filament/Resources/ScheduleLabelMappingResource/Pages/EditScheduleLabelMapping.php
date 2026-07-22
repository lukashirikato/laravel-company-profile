<?php

namespace App\Filament\Resources\ScheduleLabelMappingResource\Pages;

use App\Filament\Resources\ScheduleLabelMappingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScheduleLabelMapping extends EditRecord
{
    protected static string $resource = ScheduleLabelMappingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
