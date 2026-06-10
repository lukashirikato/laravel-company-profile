<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Services\ScheduleExpansionService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function afterSave(): void
    {
        $record = $this->record->fresh(['children', 'packages']);

        if (! $record) {
            return;
        }

        if (! $record->expand_to_month) {
            if ($record->children->isNotEmpty()) {
                DB::transaction(function () use ($record): void {
                    $record->children()->delete();
                    $record->update([
                        'series_id' => null,
                        'parent_schedule_id' => null,
                        'is_series_parent' => false,
                    ]);
                });
            }

            return;
        }

        $service = app(ScheduleExpansionService::class);
        $startDate = $record->schedule_date ? Carbon::parse($record->schedule_date) : Carbon::now();
        $dates = $service->expandToMonth((string) $record->day, $startDate);

        if ($dates->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($record, $dates) {
            $seriesId = $record->series_id ?: $record->id;
            $packageIds = $record->packages()->pluck('packages.id')->all();
            $firstDate = $dates->first();

            $record->update([
                'series_id' => $seriesId,
                'parent_schedule_id' => null,
                'is_series_parent' => true,
                'expand_to_month' => true,
                'schedule_date' => $firstDate?->format('Y-m-d') ?? $record->schedule_date,
            ]);

            $record->children()->delete();

            foreach ($dates->slice(1) as $date) {
                $child = $record->replicate();
                $child->series_id = $seriesId;
                $child->parent_schedule_id = $record->id;
                $child->is_series_parent = false;
                $child->expand_to_month = true;
                $child->day = $date->format('l');
                $child->schedule_date = $date->format('Y-m-d');
                $child->save();

                if (!empty($packageIds)) {
                    $child->packages()->sync($packageIds);
                }
            }
        });

        Notification::make()
            ->success()
            ->title('Series berhasil diperbarui')
            ->body('Semua jadwal turunan sudah disinkronkan ulang.')
            ->send();
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Schedule berhasil diubah')
            ->body('Perubahan data Schedule sudah disimpan.');
    }
}
