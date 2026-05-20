<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\AdminNotificationService;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        AdminNotificationService::notify(
            'member_registered',
            'Member baru terdaftar',
            ($customer->name ?? 'Member baru') . ' baru saja mendaftar',
            [
                'related_id'   => $customer->id,
                'related_type' => Customer::class,
                'data' => [
                    'url'   => '/admin/resources/customers/' . $customer->id,
                    'email' => $customer->email,
                ],
            ]
        );
    }

    public function updated(Customer $customer): void
    {
        // Trigger notif "verified" hanya saat field is_verified berubah dari false → true
        if ($customer->wasChanged('is_verified') && $customer->is_verified) {
            AdminNotificationService::notify(
                'member_verified',
                'Akun member terverifikasi',
                'Akun ' . ($customer->name ?? '#'.$customer->id) . ' sudah terverifikasi via OTP',
                [
                    'related_id'   => $customer->id,
                    'related_type' => Customer::class,
                    'data' => [
                        'url'   => '/admin/resources/customers/' . $customer->id,
                        'email' => $customer->email,
                    ],
                ]
            );
        }
    }
}
