<?php

namespace App\Filament\Resources\CustomerFollowUpResource\Pages;

use App\Filament\Resources\CustomerFollowUpResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerFollowUp extends EditRecord
{
    protected static string $resource = CustomerFollowUpResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
