<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Package;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Midtrans Callback Received', $request->all());

        // Validasi signature key
        $serverKey = config('midtrans.server_key');
        $hash = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hash !== $request->signature_key) {
            Log::error('Invalid Signature Key');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ambil order
        $order = Order::find($request->order_id);
        if (!$order) {
            Log::error('Order tidak ditemukan');
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Ambil customer
        $customer = Customer::find($order->customer_id);

        // Ambil package
        $package = Package::find($order->package_id);

        // Update order
        $order->update([
            'status'         => $request->transaction_status,
            'payment_type'   => $request->payment_type,
            'transaction_id' => $request->transaction_id,
        ]);

        // ================================
        // 🔥 ANTI DUPLIKAT TRANSAKSI
        // ================================
        // Satu transaksi = satu midtrans_transaction_id
        $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

        if (!$transaction) {
            // Buat transaksi baru jika belum ada
            $transaction = Transaction::create([
                'order_id'                 => $order->id,
                'customer_id'              => $order->customer_id,
                'customer_name'            => $customer->name ?? 'Unknown Customer',
                'package_id'               => $order->package_id,
                'description'              => $package->description ?? '-',
                'amount'                   => $order->amount,
                'status'                   => $request->transaction_status,
                'payment_type'             => $request->payment_type,
                'transaction_id'           => $request->transaction_id,
                'midtrans_transaction_id'  => $request->transaction_id,
                'fraud_status'             => $request->fraud_status ?? null,
                'signature_key'            => $request->signature_key,
            ]);

        } else {
            // Kalau callback datang lagi → update saja
            $transaction->update([
                'status'                   => $request->transaction_status,
                'payment_type'             => $request->payment_type,
                'fraud_status'             => $request->fraud_status ?? null,
                'signature_key'            => $request->signature_key,
            ]);
        }

        Log::info('Callback Berhasil Diproses', ['transaction_id' => $transaction->id]);

        return response()->json(['message' => 'OK']);
    }
}
