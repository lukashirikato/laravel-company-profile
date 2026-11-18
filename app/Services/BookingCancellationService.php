<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CustomerSchedule;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingCancellationService
{
    public function cutoffHours(): int
    {
        return max(0, (int) config('booking.cancellation_cutoff_hours', 4));
    }

    /**
     * Tentukan apakah booking boleh dibatalkan member.
     *
     * @return array{can_cancel: bool, deadline: ?Carbon, reason: ?string, is_exclusive: bool}
     */
    public function evaluate(CustomerSchedule $booking, ?int $cutoffHours = null): array
    {
        $cutoffHours = $cutoffHours ?? $this->cutoffHours();
        $schedule = $booking->schedule;
        $isExclusive = (bool) ($booking->order?->package?->is_exclusive ?? false);
        $deadline = $schedule?->getClassStartTime()?->copy()->subHours($cutoffHours);

        $state = [
            'can_cancel' => false,
            'deadline' => $deadline,
            'reason' => null,
            'is_exclusive' => $isExclusive,
        ];

        if ($booking->status !== 'confirmed') {
            $state['reason'] = 'Booking ini sudah tidak aktif.';
            return $state;
        }

        if (!$schedule || !$schedule->getClassStartTime()) {
            $state['reason'] = 'Jadwal kelas tidak lengkap. Hubungi admin.';
            return $state;
        }

        if ($this->hasAttended($booking)) {
            $state['reason'] = 'Anda sudah check-in di kelas ini. Hubungi admin untuk bantuan.';
            return $state;
        }

        if (!$deadline || now()->greaterThanOrEqualTo($deadline)) {
            $state['reason'] = "Batas pembatalan mandiri H-{$cutoffHours} jam sebelum kelas. Hubungi admin.";
            return $state;
        }

        $state['can_cancel'] = true;

        return $state;
    }

    /**
     * Batalkan booking dan kembalikan kredit kelas bila paket non-exclusive.
     *
     * @return array{booking: CustomerSchedule, order: ?Order, credit_returned: bool, remaining_classes: ?int, is_exclusive: bool}
     *
     * @throws ValidationException
     */
    public function cancel(CustomerSchedule $booking, ?string $reason = null): array
    {
        return DB::transaction(function () use ($booking, $reason) {
            /** @var CustomerSchedule $booking */
            $booking = CustomerSchedule::query()
                ->with('schedule.classModel')
                ->lockForUpdate()
                ->whereKey($booking->id)
                ->firstOrFail();

            $order = null;
            if ($booking->order_id) {
                $order = Order::query()
                    ->with('package')
                    ->lockForUpdate()
                    ->whereKey($booking->order_id)
                    ->first();
                $booking->setRelation('order', $order);
            }

            $state = $this->evaluate($booking);

            if (!$state['can_cancel']) {
                throw ValidationException::withMessages([
                    'booking' => $state['reason'] ?: 'Booking ini tidak dapat dibatalkan.',
                ]);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => 'member',
                'cancellation_reason' => $reason,
            ]);

            $creditReturned = false;
            $remainingClasses = $order?->remaining_classes;
            $isExclusive = (bool) ($order?->package?->is_exclusive ?? false);

            if ($order && !$isExclusive) {
                $currentRemaining = (int) ($order->remaining_classes ?? 0);
                $maxQuota = $order->package?->quota;
                $newRemaining = $currentRemaining + 1;

                if (is_numeric($maxQuota) && (int) $maxQuota > 0) {
                    $newRemaining = min($newRemaining, (int) $maxQuota);
                }

                if ($newRemaining !== $currentRemaining) {
                    $order->update(['remaining_classes' => $newRemaining]);
                    $creditReturned = true;
                }

                $remainingClasses = $newRemaining;
            }

            return [
                'booking' => $booking,
                'order' => $order,
                'credit_returned' => $creditReturned,
                'remaining_classes' => $remainingClasses,
                'is_exclusive' => $isExclusive,
            ];
        });
    }

    private function hasAttended(CustomerSchedule $booking): bool
    {
        if (!empty($booking->has_checked_in) || !empty($booking->has_checked_out)) {
            return true;
        }

        return Attendance::where('customer_id', $booking->customer_id)
            ->where('schedule_id', $booking->schedule_id)
            ->where(function ($query) {
                $query->whereNotNull('check_in_at')
                    ->orWhereNotNull('check_out_at');
            })
            ->exists();
    }
}
