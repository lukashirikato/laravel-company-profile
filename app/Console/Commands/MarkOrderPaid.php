<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Transaction;

class MarkOrderPaid extends Command
{
    protected $signature = 'order:mark-paid {order_code}';
    protected $description = 'Konfirmasi lunas order dan aktifkan kuota member secara manual';

    public function handle()
    {
        $orderCode = $this->argument('order_code');
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            $this->error("Order dengan kode {$orderCode} tidak ditemukan.");
            return Command::FAILURE;
        }

        $order->update([
            'status' => 'paid',
            'payment_type' => $order->payment_type ?? 'qris',
        ]);

        $transaction = Transaction::where('order_id', $order->id)
            ->orWhere('transaction_id', $order->order_code)
            ->first();

        if ($transaction) {
            $transaction->update([
                'status' => 'success',
                'payment_type' => $order->payment_type ?? 'qris',
            ]);
        }

        if ($order->package) {
            $packageQuota = (int) ($order->package->quota ?? 0);
            $order->update([
                'remaining_quota' => $packageQuota,
                'remaining_classes' => $packageQuota,
                'total_quota' => $packageQuota,
                'total_classes' => $packageQuota,
                'quota_applied' => true,
            ]);

            if ($order->customer) {
                $order->customer->update([
                    'package_id' => $order->package_id,
                    'quota' => (int) $order->customer->quota + $packageQuota,
                ]);
            }
        }

        $this->info("SUCCESS: Order {$order->order_code} berhasil diset LUNAS (paid) dan kuota member telah diaktifkan!");
        return Command::SUCCESS;
    }
}
