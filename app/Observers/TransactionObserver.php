<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\AdminNotificationService;

class TransactionObserver
{
    public function created(Transaction $tx): void
    {
        $this->maybeNotifyByStatus($tx);
    }

    public function updated(Transaction $tx): void
    {
        if ($tx->wasChanged('status')) {
            $this->maybeNotifyByStatus($tx);
        }
    }

    protected function maybeNotifyByStatus(Transaction $tx): void
    {
        $status = strtolower((string) $tx->status);
        $amount = number_format((int) ($tx->amount ?? 0), 0, ',', '.');
        $invoice = $tx->order_id ? ('INV-' . str_pad($tx->order_id, 6, '0', STR_PAD_LEFT)) : ('TX-'.$tx->id);
        $customerName = $tx->customer_name ?? $tx->customer?->name ?? 'Member';

        if ($status === 'success' || $status === 'settlement' || $status === 'capture' || $status === 'paid') {
            AdminNotificationService::notify(
                'payment_success',
                'Pembayaran berhasil',
                $customerName . ' membayar Rp ' . $amount . ' (' . $invoice . ')',
                [
                    'related_id'   => $tx->id,
                    'related_type' => Transaction::class,
                    'data' => [
                        'url'    => '/admin/resources/transactions/' . $tx->id,
                        'amount' => $tx->amount,
                        'invoice' => $invoice,
                    ],
                ]
            );
        } elseif (in_array($status, ['failed', 'failure', 'expire', 'expired', 'cancel', 'cancelled', 'deny', 'denied'])) {
            AdminNotificationService::notify(
                'payment_failed',
                'Pembayaran ' . $status,
                'Pembayaran ' . $invoice . ' (' . $customerName . ') ' . $status,
                [
                    'related_id'   => $tx->id,
                    'related_type' => Transaction::class,
                    'data' => [
                        'url'     => '/admin/resources/transactions/' . $tx->id,
                        'invoice' => $invoice,
                        'status'  => $status,
                    ],
                ]
            );
        }
    }
}
