<?php

namespace App\Filament\Resources\CustomerScheduleResource\Pages;

use App\Filament\Resources\CustomerScheduleResource;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditCustomerSchedule extends EditRecord
{
    protected static string $resource = CustomerScheduleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $previousStatus = $record->status;
            $newStatus = $data['status'] ?? $previousStatus;
            $orderId = $record->order_id;

            $shouldRefundClass = $orderId
                && $newStatus === 'cancelled'
                && in_array($previousStatus, ['confirmed', 'attended'], true);

            $shouldConsumeClass = $orderId
                && $previousStatus === 'cancelled'
                && in_array($newStatus, ['confirmed', 'attended'], true);

            /** @var Order|null $order */
            $order = null;

            if ($shouldRefundClass || $shouldConsumeClass) {
                $order = Order::query()
                    ->with('package:id,quota')
                    ->lockForUpdate()
                    ->whereKey($orderId)
                    ->first();

                if ($shouldConsumeClass && $order && (int) ($order->remaining_classes ?? 0) <= 0) {
                    throw ValidationException::withMessages([
                        'data.status' => 'Remaining classes member sudah habis, tidak bisa ubah kembali ke confirmed/attended.',
                    ]);
                }
            }

            $record->update($data);

            if ($shouldRefundClass && $order) {
                    $currentRemaining = (int) ($order->remaining_classes ?? 0);
                    $maxQuota = $order->package?->quota;
                    $newRemaining = $currentRemaining + 1;

                    if (is_numeric($maxQuota)) {
                        $newRemaining = min($newRemaining, (int) $maxQuota);
                    }

                    $order->update([
                        'remaining_classes' => $newRemaining,
                    ]);

                    Notification::make()
                        ->title('Booking dibatalkan, class dikembalikan')
                        ->body("Remaining classes order #{$order->order_code} sekarang {$newRemaining}.")
                        ->success()
                        ->send();
            }

            if ($shouldConsumeClass && $order) {
                $newRemaining = max(((int) ($order->remaining_classes ?? 0)) - 1, 0);

                $order->update([
                    'remaining_classes' => $newRemaining,
                ]);

                Notification::make()
                    ->title('Booking diaktifkan kembali')
                    ->body("Remaining classes order #{$order->order_code} sekarang {$newRemaining}.")
                    ->success()
                    ->send();
            }

            return $record;
        });
    }
}
