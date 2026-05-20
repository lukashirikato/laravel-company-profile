<?php

namespace App\Services;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    /**
     * Buat notifikasi admin baru.
     *
     * @param  string      $type     identifier event (member_registered, payment_success, dll)
     * @param  string      $title    judul notif
     * @param  string      $message  isi pesan
     * @param  array       $opts     ['icon'=>..., 'color'=>..., 'data'=>[], 'related_id'=>..., 'related_type'=>...]
     */
    public static function notify(string $type, string $title, string $message, array $opts = []): ?AdminNotification
    {
        try {
            return AdminNotification::create([
                'type'         => $type,
                'icon'         => $opts['icon']         ?? self::defaultIcon($type),
                'color'        => $opts['color']        ?? self::defaultColor($type),
                'title'        => $title,
                'message'      => $message,
                'data'         => $opts['data']         ?? null,
                'related_id'   => $opts['related_id']   ?? null,
                'related_type' => $opts['related_type'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('[AdminNotification] Failed to create: '.$e->getMessage(), [
                'type' => $type,
                'title' => $title,
            ]);
            return null;
        }
    }

    public static function defaultIcon(string $type): string
    {
        return match ($type) {
            'member_registered'  => 'user-plus',
            'member_verified'    => 'user-check',
            'order_created'      => 'shopping-bag',
            'payment_success'    => 'credit-card-check',
            'payment_failed'     => 'credit-card-x',
            'booking_created'    => 'calendar-plus',
            'check_in'           => 'qr-code',
            'voucher_used'       => 'ticket',
            'package_expiring'   => 'clock-alert',
            'package_quota_out'  => 'package-x',
            default              => 'bell',
        };
    }

    public static function defaultColor(string $type): string
    {
        return match ($type) {
            'member_registered',
            'member_verified',
            'payment_success',
            'check_in'           => 'green',
            'order_created',
            'booking_created'    => 'pink',
            'voucher_used'       => 'cherry',
            'package_expiring',
            'package_quota_out'  => 'amber',
            'payment_failed'     => 'red',
            default              => 'cherry',
        };
    }
}
