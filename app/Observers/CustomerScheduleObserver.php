<?php

namespace App\Observers;

use App\Models\CustomerSchedule;
use App\Services\AdminNotificationService;

class CustomerScheduleObserver
{
    public function created(CustomerSchedule $cs): void
    {
        $customer = $cs->customer ?? null;
        $schedule = $cs->schedule ?? null;
        $className = $schedule?->classModel?->class_name ?? $schedule?->class?->class_name ?? 'Kelas';
        $day       = $schedule?->day ?? '';
        $time      = $schedule?->class_time ? \Carbon\Carbon::parse($schedule->class_time)->format('H:i') : '';

        $msg = ($customer?->name ?? 'Member')
            . ' booking ' . $className
            . ($day ? ' - ' . $day : '')
            . ($time ? ' ' . $time : '');

        AdminNotificationService::notify(
            'booking_created',
            'Booking jadwal kelas',
            $msg,
            [
                'related_id'   => $cs->id,
                'related_type' => CustomerSchedule::class,
                'data' => [
                    'customer_id' => $cs->customer_id,
                    'schedule_id' => $cs->schedule_id,
                    'order_id'    => $cs->order_id ?? null,
                ],
            ]
        );
    }
}
