<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Midtrans\Notification;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerSchedule;
use Illuminate\Support\Facades\DB;


class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Ambil notifikasi dari Midtrans (library Midtrans mengisi properti)
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status ?? $request->transaction_status ?? null;
            $paymentType       = $notification->payment_type ?? $request->payment_type ?? 'unknown';
            $orderCode         = $notification->order_id ?? $request->order_id ?? null;
            $fraudStatus       = $notification->fraud_status ?? $request->fraud_status ?? null;
            $midtransId        = $notification->transaction_id ?? $request->transaction_id ?? null;
            $signatureKey      = $notification->signature_key ?? $request->signature_key ?? null;

            Log::info("[Midtrans] Callback diterima", [
                'order_code' => $orderCode,
                'status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'midtrans_id' => $midtransId,
                'raw' => $request->all()
            ]);

            if (!$orderCode) {
                Log::error("[Midtrans] order_id kosong di payload");
                return response()->json(['message' => 'order_id missing'], 422);
            }

            // Cari order berdasarkan order_code
            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                Log::error("[Midtrans] ORDER NOT FOUND untuk order_code {$orderCode}");
                return response()->json(['message' => 'Order not found'], 404);
            }

            $customer = Customer::find($order->customer_id);
            $package  = $order->package; // relasi package harus ada di model Order

            // Tentukan status final
            $status = match ($transactionStatus) {
                'capture' => $fraudStatus === 'challenge' ? 'pending' : 'paid',
                'settlement' => 'paid',
                'pending' => 'pending',
                'deny', 'expire', 'cancel' => 'failed',
                default => 'pending',
            };

            // Jika config menyimpan is_production, gunakan itu untuk menentukan sandbox
            $isSandbox = ! (bool) config('midtrans.is_production');

            // Sandbox override (opsional) — jika sandbox treat settlement/capture as paid
            if ($isSandbox && in_array($transactionStatus, ['settlement', 'capture'])) {
                $status = 'paid';
            }

            // Mapping payment type agar konsisten
            $paymentTypeMap = [
                'gopay'         => 'qris',
                'qris'          => 'qris',
                'bank_transfer' => 'transfer',
                'echannel'      => 'transfer',
            ];
            $paymentTypeMapped = $paymentTypeMap[$paymentType] ?? $paymentType ?? 'cash';

            // Ambil nama customer dari order/customer (prioritas order.customer_name)
            $customerName = $order->customer_name ?: ($customer->name ?? 'Unknown Customer');

            // Ambil deskripsi package (pakai package.description bila ada)
            $packageDescription = $package->description ?? $package->name ?? '-';

            // ---------------------------------------------------------
            // Prevent duplicate rows:
            // 1) Jika ada transaction yang dibuat saat checkout (order_id & midtrans_transaction_id IS NULL),
            //    update row tersebut (isi midtrans_transaction_id, signature_key, status dll).
            // 2) Jika tidak ada, updateOrCreate berdasarkan midtrans_transaction_id.
            // ---------------------------------------------------------

            $existingPending = null;
            if ($order->id) {
                $existingPending = Transaction::where('order_id', $order->id)
                    ->whereNull('midtrans_transaction_id')
                    ->latest()
                    ->first();
            }

            if ($existingPending) {
                // update existing pending record (biasanya yang dibuat saat order)
                $existingPending->update([
                    'midtrans_transaction_id' => $midtransId,
                    'transaction_id'          => $orderCode,
                    'status'                  => $status,
                    'payment_type'            => $paymentTypeMapped,
                    'fraud_status'            => $fraudStatus,
                    'signature_key'           => $signatureKey,
                    'customer_name'           => $customerName,
                    'description'             => $packageDescription,
                    'package_name'            => $package->name ?? null,
                    'amount'                  => $order->amount,
                ]);

                $transaction = $existingPending;
                Log::info("[Midtrans] Existing pending transaction updated", ['id' => $transaction->id]);
            } else {
                // Jika tidak ada pending, updateOrCreate by midtrans_transaction_id (mencegah duplikat)
                $transaction = Transaction::updateOrCreate(
                    ['midtrans_transaction_id' => $midtransId],
                    [
                        'order_id'               => $order->id,
                        'customer_id'            => $order->customer_id,
                        'customer_name'          => $customerName,
                        'package_id'             => $order->package_id,
                        'package_name'           => $package->name ?? null,
                        'description'            => $packageDescription,
                        'amount'                 => $order->amount,
                        'status'                 => $status,
                        'payment_type'           => $paymentTypeMapped,
                        'fraud_status'           => $fraudStatus,
                        'signature_key'          => $signatureKey,
                        'transaction_id'         => $orderCode,
                    ]
                );

                Log::info("[Midtrans] Transaction updateOrCreate", ['id' => $transaction->id]);
            }

           // ✅ GUARD PALING AWAL
if ($order->status === 'active') {
    Log::info('[Midtrans] Order already active, skip', [
        'order_id' => $order->id
    ]);
    return response()->json(['message' => 'Already active']);
}

// Update order (set paid / failed)
$order->update([
    'status'               => $status,
    'payment_type'         => $paymentTypeMapped,
    'transaction_id'       => $midtransId,
]);



          if (
    $status === 'paid' &&
    $package->schedule_mode === 'locked' &&
    empty($order->schedule_ids)
) {
    Log::error('[Midtrans] LOCKED package but schedule_ids empty', [
        'order_id'   => $order->id,
        'package_id' => $package->id,
    ]);

    // ❗ JANGAN return
    throw new \Exception('Locked package tapi schedule_ids kosong');
}


    DB::transaction(function () use ($order, $customer, $package) {

    $order = Order::lockForUpdate()->find($order->id);
    $customer = Customer::lockForUpdate()->find($customer->id);

    // GUARD FINAL
    if ($order->status === 'active') {
        return;
    }

    // 1️⃣ SET PACKAGE
    $customer->update([
        'package_id' => $package->id,
    ]);

    // 2️⃣ APPLY SCHEDULE
    $scheduleIds = json_decode($order->schedule_ids, true) ?? [];

    foreach ($scheduleIds as $scheduleId) {
        CustomerSchedule::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'schedule_id' => (int) $scheduleId,
            ],
            [
                'order_id'  => $order->id,
                'status'    => 'confirmed',
                'joined_at' => now(),
            ]
        );
    }

    // 3️⃣ QUOTA
    if ($package->quota > 0) {
        $customer->increment('quota', (int) $package->quota);
    }

    // 4️⃣ EXPIRED
    if (!empty($package->duration_days)) {
        $customer->update([
            'quota_expired_at' => now()->addDays($package->duration_days),
        ]);
    }

    // 5️⃣ FINAL STATE (PALING AKHIR)
    $order->update([
        'status'              => 'paid',
        'quota_applied'       => true,
        'quota_applied_at'    => now(),
        'schedule_applied_at' => now(),
    ]);
});



    Log::info('[Midtrans] APPLY PACKAGE SUCCESS', [
        'order_id' => $order->id,
        'customer_id' => $customer->id,
    ]);




            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error("[Midtrans] Callback error", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}