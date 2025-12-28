<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Package;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ClassModel;
use App\Models\Schedule;
use Illuminate\Support\Str;


use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey    = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /* ====================================
     * HALAMAN CHECKOUT
     * ==================================== */
  public function index(Package $package)
{
    $groups = \App\Models\ClassGroup::with([
        'classes.schedules' => function ($q) {
            $q->where('show_on_landing', 1)
              ->orderByRaw("
                FIELD(day,
                    'Monday','Tuesday','Wednesday',
                    'Thursday','Friday','Saturday','Sunday'
                )
              ")
              ->orderBy('class_time');
        }
    ])->orderBy('id')->get();

    // ✅ TAMBAHKAN DI SINI (JANGAN DI ATAS!)
    $classOptions = [
        'muaythai_intermediate' => [
            'label' => 'Muaythai Intermediate',
            'schedule' => [
                'Monday & Thursday — 19:00',
            ],
        ],
        'mat_pilates' => [
            'label' => 'Mat Pilates',
            'schedule' => [
                'Wednesday & Friday — 09:00',
            ],
        ],
        'mix_1' => [
            'label' => 'Mix Class (1)',
            'schedule' => [
                'Mat Pilates — Wednesday 19:00',
                'Muaythai — Sunday 09:00',
            ],
        ],
        'mix_2' => [
            'label' => 'Mix Class (2)',
            'schedule' => [
                'Mat Pilates — Tuesday 19:00',
                'Muaythai — Saturday 09:30',
            ],
        ],
        'mix_3' => [
            'label' => 'Mix Class (3)',
            'schedule' => [
                'Mat Pilates — Thursday 19:00',
                'Body Shaping — Sunday 11:00',
            ],
        ],
        'mix_4' => [
            'label' => 'Mix Class (4)',
            'schedule' => [
                'Body Shaping — Friday 19:00',
                'Muaythai — Sunday 10:00',
            ],
        ],
        'muaythai_beginner_1' => [
            'label' => 'Muaythai Beginner (1)',
            'schedule' => [
                'Tuesday 19:00 & Saturday 08:00',
            ],
        ],
    ];

    // ✅ DAN UBAH return view JADI SEPERTI INI
    return view('checkout.index', [
        'package'      => $package,
        'groups'       => $groups,
        'classOptions' => $classOptions, // ⬅️ INI PENTING
    ]);
}


    /* ====================================
 * HALAMAN SUCCESS PEMBAYARAN
 * ==================================== */
public function success($order_code)
{
    $order = Order::where('order_code', $order_code)->firstOrFail();

    return view('checkout.success', compact('order'));
}




    /* ====================================
     * PROSES CHECKOUT
     * ==================================== */
    public function process(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Silakan login terlebih dahulu'], 401);
        }

        

        
        $package = Package::find($request->package_id);
if (!$package) {
    return response()->json(['error' => 'Paket tidak ditemukan'], 404);
}

$totalPrice = (int) $request->total_price;
$discount   = (int) $request->discount;


if ($totalPrice <= 0) {
    return response()->json(['error' => 'Total price invalid'], 422);
}




        $orderCode = 'ORD-' . Str::uuid();
        $customerName = $customer->name ?: 'Tidak Diketahui';

        try {





            DB::beginTransaction();

            

            /* CREATE ORDER */
            $order = Order::create([
                    'customer_id'   => $customer->id,
                    'customer_name' => $customerName,
                    'package_id'    => $package->id,
                    'amount'        => $totalPrice,
                    'voucher_code'  => $request->voucher_code,
                    'discount'      => $discount,
                    'status'        => 'pending',
                    'payment_type'  => null,
                    'order_code'    => $orderCode,
                    'quota_applied' => false,
                    ]);

            /* CREATE TRANSACTION */
            Transaction::create([
                'order_id'                => $order->id,
                'transaction_id'          => $orderCode,
                'customer_id'             => $customer->id,
                'customer_name'           => $customerName,
                'package_id'              => $package->id,
               'amount' => $totalPrice,
                'description'             => 'Pembelian paket: ' . $package->name,
                'status'                  => 'pending',
                'payment_type'            => null,
                'midtrans_transaction_id' => null,
                'fraud_status'            => null,
                'signature_key'           => null,
            ]);

            DB::commit();

            /* MIDTRANS PARAMETER */
            $params = [
    'transaction_details' => [
        'order_id'     => $orderCode,
        'gross_amount' => $totalPrice,
    ],

    'customer_details' => [
        'first_name' => $customerName,
        'email'      => $customer->email ?? 'email@dummy.com',
        'phone'      => $customer->phone_number ?? '08111111111',
    ],

    'item_details' => array_filter([
    [
        'id'       => 'PKG-' . $package->id,
        'price' => (int) $package->price,
        'quantity' => 1,
        'name'     => $package->name,
    ],
    $discount > 0 ? [
        'id'       => 'DISCOUNT',
        'price'    => -$discount,
        'quantity' => 1,
        'name'     => 'Voucher Discount',
    ] : null
]),


    // ✅ INI KUNCI PALING PENTING
    'callbacks' => [
        'finish' => route('payment.success', $orderCode),
    ],
];


            Log::info("Midtrans Params", $params);

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snapToken' => $snapToken,
                'order_id'  => $orderCode,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("MIDTRANS ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
    'error'  => $e->getMessage(),
], 500);

        }
    }

    /* ====================================
     * MIDTRANS NOTIFICATION HANDLER
     * ==================================== */
   public function notification(Request $request)
{
    try {
        $notif = new MidtransNotification();


        DB::transaction(function () use ($notif) {

            // 🔒 LOCK DI DALAM TRANSACTION
            $order = Order::where('order_code', $notif->order_id)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                throw new \Exception('Order tidak ditemukan');
            }

            $transaction = Transaction::where('transaction_id', $notif->order_id)->first();

            $paidStatuses = ['settlement', 'capture'];
            $isPaid = in_array($notif->transaction_status, $paidStatuses)
                && $notif->fraud_status !== 'deny';

            // Update status
            $order->update([
                'status'       => $isPaid ? 'paid' : 'failed',
                'payment_type' => $notif->payment_type,
            ]);

            if ($transaction) {
                $transaction->update([
                    'status'                  => $isPaid ? 'paid' : 'failed',
                    'payment_type'            => $notif->payment_type,
                    'midtrans_transaction_id' => $notif->transaction_id,
                    'fraud_status'            => $notif->fraud_status,
                    'signature_key'           => $notif->signature_key,
                    'amount'                  => (int) $notif->gross_amount,
                ]);
            }

            // ⛔ STOP kalau belum paid / sudah diapplied
            if (!$isPaid || $order->quota_applied) {
                return;
            }

            $customer = $order->customer;
            $package  = $order->package;

            // ✅ SET PACKAGE CUSTOMER SETELAH PAYMENT SUKSES
$customer->update([
    'package_id' => $package->id,
]);
            // ⛔ Jika paket tidak auto-apply, tunggu admin

            if (!$package->auto_apply) {
                $order->update(['status' => 'waiting_admin']);
                return;
            }

            // ➕ QUOTA
            $customer->increment('quota', $package->quota);

            // ⏳ EXPIRED
            if ($package->duration_days) {
                $customer->update([
                    'quota_expired_at' => now()->addDays($package->duration_days),
                ]);
            }

            // ===============================
// APPLY SCHEDULE SETELAH PAYMENT
// ===============================

// 🔒 Paket dengan jadwal FIX (contoh: Paket A)
if ($package->schedule_type === 'fixed') {

    if (!$package->default_schedule_id) {
        throw new \Exception('Default schedule belum di-set untuk paket ini');
    }

    \App\Models\CustomerSchedule::firstOrCreate([
        'customer_id' => $customer->id,
        'schedule_id' => $package->default_schedule_id,
        'order_id'    => $order->id,
    ], [
        'status'    => 'confirmed',
        'joined_at' => now(),
    ]);
}

// 🟢 Paket dengan jadwal PILIH (contoh: Paket B)
// ➜ TIDAK ADA APA-APA DI SINI
// ➜ Jadwal dipilih di dashboard member

            // ✅ FLAG
            $order->update([
                'quota_applied' => 1,
                'status'        => 'active',
            ]);
        });

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error("NOTIFICATION ERROR: " . $e->getMessage());
        return response()->json(['error' => 'Notification gagal'], 500);
    }
}



    /* ====================================
 * AJAX: GET SCHEDULE BY CLASS (CHECKOUT)
 * ==================================== */
public function getSchedules($classId)
{
    return Schedule::where('class_id', $classId)
        ->where('show_on_landing', 1)
        ->orderByRaw("
            FIELD(day, 
                'Monday','Tuesday','Wednesday',
                'Thursday','Friday','Saturday','Sunday'
            )
        ")
        ->orderBy('class_time')
        ->get();
}




    /* ====================================
     * CEK STATUS PEMBAYARAN
     * ==================================== */
    public function status($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        return response()->json([
            'status'        => $order->status,
            'customer_name' => $order->customer_name ?? 'Tidak Diketahui',
            'amount'        => $order->amount,
            'payment_type'  => $order->payment_type,
        ]);
    }
}







