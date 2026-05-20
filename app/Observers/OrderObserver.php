<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\AdminNotificationService;

class OrderObserver
{
    public function created(Order $order): void
    {
        $packageName = $order->package?->name ?? 'Paket';
        $customerName = $order->customer?->name ?? 'Member';

        AdminNotificationService::notify(
            'order_created',
            'Order baru dibuat',
            $customerName . ' membuat order: ' . $packageName,
            [
                'related_id'   => $order->id,
                'related_type' => Order::class,
                'data' => [
                    'url'      => '/admin/resources/orders/' . $order->id,
                    'amount'   => $order->total ?? null,
                    'package'  => $packageName,
                ],
            ]
        );

        // Voucher dipakai → notif terpisah
        if (!empty($order->voucher_id) || !empty($order->voucher_code)) {
            AdminNotificationService::notify(
                'voucher_used',
                'Voucher digunakan',
                'Voucher dipakai oleh ' . $customerName . ' di order #' . $order->id,
                [
                    'related_id'   => $order->id,
                    'related_type' => Order::class,
                    'data' => [
                        'url'         => '/admin/resources/orders/' . $order->id,
                        'voucher'     => $order->voucher_code ?? ('ID #'.$order->voucher_id),
                        'customer'    => $customerName,
                    ],
                ]
            );
        }
    }

    public function updated(Order $order): void
    {
        // Notif kuota habis
        $hasQuotaField = array_key_exists('remaining_quota', $order->getAttributes())
                      || array_key_exists('remaining_classes', $order->getAttributes());

        if ($hasQuotaField) {
            $rem = $order->remaining_quota ?? $order->remaining_classes ?? null;
            $wasChanged = $order->wasChanged('remaining_quota') || $order->wasChanged('remaining_classes');

            if ($wasChanged && $rem === 0) {
                AdminNotificationService::notify(
                    'package_quota_out',
                    'Kuota paket habis',
                    'Order #' . $order->id . ' (' . ($order->package?->name ?? 'paket') . ') sudah habis kuotanya',
                    [
                        'related_id'   => $order->id,
                        'related_type' => Order::class,
                        'data' => [
                            'url' => '/admin/resources/orders/' . $order->id,
                        ],
                    ]
                );
            }
        }
    }
}
