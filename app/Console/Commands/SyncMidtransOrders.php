<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Http\Controllers\CheckoutController;

class SyncMidtransOrders extends Command
{
    protected $signature = 'midtrans:sync {order_code?}';
    protected $description = 'Sync pending order status directly from Midtrans API';

    public function handle()
    {
        $orderCode = $this->argument('order_code');

        if ($orderCode) {
            $orders = Order::where('order_code', $orderCode)->get();
        } else {
            $orders = Order::whereIn('status', ['pending', 'processing'])->get();
        }

        if ($orders->isEmpty()) {
            $this->info('Tidak ada order pending yang perlu disinkronkan.');
            return Command::SUCCESS;
        }

        $controller = app(CheckoutController::class);

        foreach ($orders as $order) {
            $this->info("Memeriksa order {$order->order_code} ({$order->customer_name})...");
            try {
                $controller->status($order->order_code);
                $order->refresh();
                $this->info("-> Status terbaru: {$order->status} | Quota Applied: " . ($order->quota_applied ? 'Ya' : 'Tidak'));
            } catch (\Exception $e) {
                $this->error("-> Gagal sync: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
