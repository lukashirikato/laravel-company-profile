<?php

namespace App\Filament\Resources\WhatsAppTemplateResource\Pages;

use App\Filament\Resources\WhatsAppTemplateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppTemplate extends EditRecord
{
    protected static string $resource = WhatsAppTemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('secondary')
                ->modalHeading('Preview Pesan')
                ->modalSubheading('Berikut adalah contoh hasil render pesan dengan data sample.')
                ->modalWidth('2xl')
                ->action(function () {
                    // no-op, we use modal content
                })
                ->modalContent(function ($livewire) {
                    $record = $livewire->record;
                    $sampleData = self::getSampleData($record->key);
                    $rendered = self::renderSample($record->message, $sampleData);

                    return view('filament.components.whatsapp-preview-modal', [
                        'rendered' => $rendered,
                    ]);
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public static function getSampleData(string $key): array
    {
        $samples = [
            'payment_success' => [
                'customer_name' => 'Ahmad Fauzi',
                'package_name' => 'Elite Monthly',
                'amount' => '350.000',
                'order_code' => 'INV/20260723/001',
                'package_days' => '30',
            ],
            'booking_confirmation' => [
                'customer_name' => 'Ahmad Fauzi',
                'package_name' => 'Elite Monthly',
                'total_schedules' => '3',
                'schedule_details' => "📅 *Jadwal Kelas Anda:*\n\n1. *Zumba*\n   📆 Senin · ⏰ 07:00\n   👨‍🏫 Sarah\n   📍 STUDIO FTM SOCIETY\n\n2. *Yoga*\n   📆 Rabu · ⏰ 16:00\n   👨‍🏫 Budi\n   📍 STUDIO FTM SOCIETY\n\n3. *Pilates*\n   📆 Jumat · ⏰ 06:30\n   👨‍🏫 Dewi\n   📍 STUDIO FTM SOCIETY",
            ],
            'class_reminder' => [
                'customer_name' => 'Ahmad Fauzi',
                'class_name' => 'Zumba',
                'class_time' => '07:00 - 08:00',
                'location' => 'Studio Utama',
                'instructor_name' => 'Sarah',
            ],
            'check_in_success' => [
                'customer_name' => 'Ahmad Fauzi',
                'package_name' => 'Elite Monthly',
                'program' => 'Zumba',
                'location' => 'Studio Utama',
                'check_in_time' => '06:55 WIB',
                'remaining_quota' => '8',
                'total_quota' => '12',
            ],
            'check_out_success' => [
                'customer_name' => 'Ahmad Fauzi',
                'package_name' => 'Elite Monthly',
                'program' => 'Zumba',
                'location' => 'Studio Utama',
                'check_in_time' => '06:55 WIB',
                'check_out_time' => '07:50 WIB',
                'duration' => '55 menit',
                'remaining_quota' => '8',
                'total_quota' => '12',
            ],
        ];

        return $samples[$key] ?? [];
    }

    public static function renderSample(string $message, array $data): string
    {
        $search = [];
        $replace = [];
        foreach ($data as $key => $value) {
            $search[] = '{' . $key . '}';
            $replace[] = $value;
        }
        return str_replace($search, $replace, $message);
    }
}
