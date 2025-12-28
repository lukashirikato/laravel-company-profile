<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Customer;
use Midtrans\Config;
use Midtrans\Snap;

class GuestCheckoutController extends Controller
{
    // Tampilkan form checkout untuk tamu
    public function show($packageId)
    {
        // Accept either numeric ID or slug string
        $package = Package::where('id', $packageId)
            ->orWhere('slug', $packageId)
            ->firstOrFail();
        return view('guest.checkout', compact('package'));
    }

    // Submit form guest checkout
    public function process(Request $request, $packageId)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required',
        ]);

        // Cari Customer berdasarkan email atau buat baru
        $customer = Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt('password123') // default
            ]
        );

        // Accept either numeric ID or slug string
        $package = Package::where('id', $packageId)
            ->orWhere('slug', $packageId)
            ->firstOrFail();

        // Generate order ID unik
        $orderId = 'GUEST-' . time() . '-' . rand(100,999);

        // Simpan pembayaran pending
        $payment = Payment::create([
            'customer_id' => $customer->id,
            'package_id'  => $package->id,
            'amount'      => $package->price,
            'status'      => 'pending',
            'transaction_id' => $orderId
        ]);

        // Midtrans Config
        Config::$serverKey     = config('midtrans.server_key');
        Config::$isProduction  = config('midtrans.is_production');
        Config::$isSanitized   = true;
        Config::$is3ds         = true;

        // Parameter Snap
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $package->price
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email'      => $customer->email,
                'phone'      => $customer->phone,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('guest.payment', [
            'snapToken' => $snapToken,
            'payment' => $payment
        ]);
    }
}
