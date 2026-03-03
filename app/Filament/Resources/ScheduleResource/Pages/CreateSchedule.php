<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    // ℹ️ Filament akan otomatis handle packages relationship sync via CheckboxList
    // Tidak perlu afterCreate untuk pivot sync
}
