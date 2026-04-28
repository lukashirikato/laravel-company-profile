<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Services\ScheduleExpansionService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected array $packageIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['expand_to_month']) && (int) ($data['schedule_preview_confirmed'] ?? 0) !== 1) {
            throw ValidationException::withMessages([
                'schedule_preview_confirmed' => 'Silakan pilih "Ya, lanjutkan simpan jadwal" jika preview sudah sesuai.',
            ]);
        }

        if (isset($data['packages']) && is_array($data['packages'])) {
            $this->packageIds = array_values($data['packages']);
        }

        unset($data['schedule_preview_confirmed']);

        if (empty($data['expand_to_month'])) {
            $data['expand_to_month'] = false;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record->fresh(['packages']);

        if (! $record || ! $record->expand_to_month) {
            return;
        }

        $service = app(ScheduleExpansionService::class);
        $startDate = $record->schedule_date ? Carbon::parse($record->schedule_date) : Carbon::now();
        $dates = $service->expandToMonth((string) $record->day, $startDate);

        if ($dates->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('Preview kosong')
                ->body('Tidak ada tanggal yang cocok untuk jadwal ini.')
                ->send();

            return;
        }

        DB::transaction(function () use ($record, $dates) {
            $seriesId = $record->id;
            $packageIds = $this->packageIds ?: $record->packages()->pluck('packages.id')->all();
            $firstDate = $dates->first();

            $record->update([
                'series_id' => $seriesId,
                'parent_schedule_id' => null,
                'is_series_parent' => true,
                'expand_to_month' => true,
                'schedule_date' => $firstDate?->format('Y-m-d') ?? $record->schedule_date,
            ]);

            $childDates = $dates->slice(1);

            foreach ($childDates as $date) {
                $child = $record->replicate();
                $child->series_id = $seriesId;
                $child->parent_schedule_id = $seriesId;
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
            ->title('Schedule berhasil dibuat')
            ->body('Jadwal otomatis diperluas untuk 1 bulan penuh.')
            ->send();
    }
}
