<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Services\AdminNotificationService;

class AttendanceObserver
{
    public function created(Attendance $att): void
    {
        $customer = $att->customer ?? null;
        $schedule = $att->schedule ?? null;
        $className = $schedule?->classModel?->class_name ?? 'Kelas';

        AdminNotificationService::notify(
            'check_in',
            'Member check-in',
            ($customer?->name ?? 'Member') . ' check-in: ' . $className,
            [
                'related_id'   => $att->id,
                'related_type' => Attendance::class,
                'data' => [
                    'customer_id' => $att->customer_id,
                ],
            ]
        );
    }
}
