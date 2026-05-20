<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\AdminNotificationService;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $customer = $booking->customer ?? null;
        $program  = $booking->program ?? 'Kelas';
        $date     = $booking->schedule_date ?? '';
        $time     = $booking->schedule_time ?? '';

        AdminNotificationService::notify(
            'booking_created',
            'Booking jadwal baru',
            ($customer?->name ?? 'Member') . ' booking ' . $program
                . ($date ? ' tanggal ' . $date : '')
                . ($time ? ' jam ' . $time : ''),
            [
                'related_id'   => $booking->id,
                'related_type' => Booking::class,
                'data' => [
                    'url'         => url('/bookings'),
                    'customer_id' => $booking->customer_id,
                ],
            ]
        );
    }
}
