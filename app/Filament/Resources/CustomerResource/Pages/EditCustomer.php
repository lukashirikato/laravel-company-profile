<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Hapus Customer')
                ->modalSubheading(fn() => "Anda yakin ingin menghapus data {$this->record->name}? Tindakan ini tidak dapat dibatalkan."),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Customer berhasil diupdate')
            ->body('Data customer telah diperbarui.');
    }

    /**
     * ✅ SYNC QUOTA: Handle synchronisasi quota antara customers dan orders
     * Dipanggil sebelum data disimpan ke database
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil nilai dari form state karena field dehydrated(false)
        // Bisa dari dua sumber: direct input atau form data
        $newQuota = $this->data['remaining_quota_to_update'] ?? $data['remaining_quota_to_update'] ?? null;

        // Jika admin mengubah remaining_quota_to_update
        if ($newQuota !== null) {
            $newQuota = (int) $newQuota;
            
            \Illuminate\Support\Facades\Log::info('🔄 Starting quota sync', [
                'customer_id' => $this->record->id,
                'customer_name' => $this->record->name,
                'new_quota_value' => $newQuota,
            ]);

            // ✅ UPDATE 1: Update customers.quota (legacy field)
            $data['quota'] = $newQuota;

            // ✅ UPDATE 2: Update orders.remaining_quota (active order)
            // Cari active order berdasarkan customer_id dengan multiple status checks
            $activeOrder = Order::where('customer_id', $this->record->id)
                ->whereIn('status', ['active', 'completed', 'paid', 'settlement', 'success'])
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
                })
                ->latest()
                ->first();

            // Jika ada active order, update remaining_quota
            if ($activeOrder) {
                try {
                    $oldQuota = $activeOrder->remaining_quota;
                    
                    $activeOrder->update([
                        'remaining_quota' => $newQuota,
                    ]);
                    
                    // Refresh untuk get updated value
                    $activeOrder->refresh();
                    
                    \Illuminate\Support\Facades\Log::info('✅ Quota sync successful', [
                        'customer_id' => $this->record->id,
                        'order_id' => $activeOrder->id,
                        'order_code' => $activeOrder->order_code,
                        'old_quota' => $oldQuota,
                        'new_quota' => $newQuota,
                    ]);

                    Notification::make()
                        ->title('✅ Quota Synchronized')
                        ->body("Remaining quota updated untuk order: {$activeOrder->order_code} ({$oldQuota} → {$newQuota})")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('❌ Quota sync failed', [
                        'customer_id' => $this->record->id,
                        'order_id' => $activeOrder->id ?? null,
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                    
                    Notification::make()
                        ->title('❌ Sync Error')
                        ->body("Gagal mensinkronkan quota: " . $e->getMessage())
                        ->danger()
                        ->send();
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('⚠️ No active order found for quota sync', [
                    'customer_id' => $this->record->id,
                    'customer_name' => $this->record->name,
                ]);
                
                Notification::make()
                    ->title('⚠️ No Active Order')
                    ->body('Tidak ada order aktif untuk disinkronkan. Hanya customer.quota yang diperbarui.')
                    ->warning()
                    ->send();
            }
        }

        return $data;
    }
}