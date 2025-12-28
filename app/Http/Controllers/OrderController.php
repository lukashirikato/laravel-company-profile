<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Create new order
     */
    public function create(Request $request)
    {
        $request->validate([
    'packages' => 'required|array|min:1',
    'packages.*.package_id' => 'required|exists:packages,id',
    'packages.*.qty' => 'required|integer|min:1',
]);


        if (!Auth::guard('customer')->check()) {
            return redirect('/customer/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $customer = Auth::guard('customer')->user();
        $package  = Package::findOrFail($request->package_id);

        // Generate order code
        $orderCode = "TRX-" . time() . "-" . $customer->id;

        // Simpan ke tabel orders
        $order = Order::create([
    'customer_id'    => $customer->id,
    'package_id'     => $package->id,
    'customer_name'  => $customer->name,
    'amount'         => $package->price,
    'status'         => 'pending',
    'payment_type'   => null,
    'transaction_id' => null,
    'order_code'     => $orderCode,

    // 🔥 INI KUNCI UTAMA
    'schedule_ids'   => json_encode($request->schedule_ids),
]);


        // Buat transaksi di tabel transactions
        Transaction::create([
            'customer_id'   => $customer->id,
            'customer_name' => $customer->name,
            'package_id'    => $package->id,
            'order_code'    => $orderCode,
            'amount'        => $package->price,
            'status'        => 'pending',
            'payment_type'  => null,
            'transaction_id'=> null,
        ]);

        return redirect()->route('checkout', $order->id);
    }

    /**
     * Checkout page
     */
    public function checkout($id)
    {
        $order = Order::with('package', 'customer')->findOrFail($id);

        if (Auth::guard('customer')->id() !== $order->customer_id) {
            abort(403, 'Akses ditolak');
        }

        if ($order->status === 'paid') {
            return redirect()->route('order.success', $order->id);
        }

        return view('checkout', compact('order'));
    }

    /**
     * Choose payment method
     */
    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_type' => 'required|in:bank_transfer,ewallet,cash',
        ]);

        $order = Order::findOrFail($id);

        if (Auth::guard('customer')->id() !== $order->customer_id) {
            abort(403, 'Akses ditolak');
        }

        $transactionId = "PAY-" . strtoupper(uniqid());

        // Update orders
        $order->update([
            'payment_type'   => $request->payment_type,
            'status'         => 'pending',
            'transaction_id' => $transactionId,
        ]);

        // Update transactions
        Transaction::where('order_code', $order->order_code)->update([
            'payment_type'   => $request->payment_type,
            'transaction_id' => $transactionId,
        ]);

        if ($request->payment_type === 'cash') {
            return redirect()->route('checkout', $order->id)
                ->with('success', 'Silakan datang ke lokasi untuk melakukan pembayaran.');
        }

        return redirect()->route('checkout', $order->id)
            ->with('success', 'Metode pembayaran disimpan. Silakan lanjutkan proses pembayaran.');
    }

     // ⬇️ Tambahkan method invoice DI SINI
    public function invoice($id)
    {
        $order = Order::with('package', 'customer')->findOrFail($id);

        if (Auth::guard('customer')->id() !== $order->customer_id) {
            abort(403, 'Akses ditolak');
        }

        $pdf = Pdf::loadView('invoice.template', [
            'order'     => $order,
            'customer'  => $order->customer,
            'package'   => $order->package,
        ]);

        return $pdf->download('invoice-' . $order->order_code . '.pdf');
    }
}


    

