<?php

namespace App\Filament\Resources\CustomerFollowUpResource\Pages;

use App\Filament\Resources\CustomerFollowUpResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCustomerFollowUp extends CreateRecord
{
    protected static string $resource = CustomerFollowUpResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['followed_up_by'] = Auth::id();
        return $data;
    }
}
