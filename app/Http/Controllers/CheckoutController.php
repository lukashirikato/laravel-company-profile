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
use App\Models\CustomerSchedule;
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
        // ✅ CEK APAKAH INI EXCLUSIVE CLASS PROGRAM (pakai field is_exclusive)
        $isExclusiveClass = (bool) $package->is_exclusive;
        
        $classOptions = [];
        $canSelectSchedule = false;

        // ✅ HANYA TAMPILKAN DROPDOWN UNTUK EXCLUSIVE CLASS
        if ($isExclusiveClass) {
            $classOptions = [
                'muaythai_intermediate' => [
                    'label' => 'Muaythai Intermediate',
                    'schedules' => [
                        'Monday 19:00',
                        'Thursday 19:00',
                    ],
                ],
                'mat_pilates' => [
                    'label' => 'Mat Pilates',
                    'schedules' => [
                        'Wednesday 09:00',
                        'Friday 09:00',
                    ],
                ],
                'mix_class_1' => [
                    'label' => 'Mix Class (1)',
                    'schedules' => [
                        'Wednesday 19:00 – Mat Pilates',
                        'Sunday 09:00 – Muaythai',
                    ],
                ],
                'mix_class_2' => [
                    'label' => 'Mix Class (2)',
                    'schedules' => [
                        'Tuesday 19:00 – Mat Pilates',
                        'Saturday 09:30 – Muaythai',
                    ],
                ],
                'mix_class_3' => [
                    'label' => 'Mix Class (3)',
                    'schedules' => [
                        'Thursday 19:00 – Mat Pilates',
                        'Sunday 11:00 – Body Shaping',
                    ],
                ],
                'mix_class_4' => [
                    'label' => 'Mix Class (4)',
                    'schedules' => [
                        'Friday 19:00 – Body Shaping',
                        'Sunday 10:00 – Muaythai',
                    ],
                ],
                'muaythai_beginner' => [
                    'label' => 'Muaythai Beginner',
                    'schedules' => [
                        'Tuesday 19:00',
                        'Saturday 08:00',
                    ],
                ],
            ];
            
            $canSelectSchedule = true;
        }

        return view('checkout.index', [
            'package'           => $package,
            'classOptions'      => $classOptions,
            'canSelectSchedule' => $canSelectSchedule,
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
        $package = Package::find($request->package_id);

        if (!$package) {
            return response()->json(['error' => 'Paket tidak ditemukan'], 404);
        }

        // ✅ CEK APAKAH INI EXCLUSIVE CLASS (pakai field is_exclusive)
        $isExclusiveClass = (bool) $package->is_exclusive;
        $classId = $request->input('class_id');

        // ✅ VALIDASI class_id HANYA UNTUK EXCLUSIVE CLASS
        if ($isExclusiveClass && !$classId) {
            return response()->json([
                'message' => 'Silakan pilih kelas terlebih dahulu'
            ], 422);
        }

        // ✅ LOG UNTUK DEBUG
        Log::info('📦 CHECKOUT PROCESS', [
            'package_name' => $package->name,
            'is_exclusive' => $isExclusiveClass,
            'class_id' => $classId,
            'customer_id' => $customer->id,
        ]);

        // HITUNG HARGA
        $discount = 0;
        // TODO: validasi voucher di sini kalau sudah siap
        $totalPrice = (int) $package->price - $discount;

        if ($totalPrice <= 0) {
            return response()->json(['error' => 'Total price invalid'], 422);
        }

        $orderCode = 'ORD-' . Str::uuid();
        $customerName = $customer->name ?: 'Tidak Diketahui';

        try {
            DB::beginTransaction();

            /* CREATE ORDER */
            $order = Order::create([
                'customer_id'       => $customer->id,
                'customer_name'     => $customerName,
                'package_id'        => $package->id,
                'amount'            => $totalPrice,
                'voucher_code'      => $request->voucher_code,
                'discount'          => $discount,
                'selected_class_id' => $classId, // ✅ Bisa NULL untuk non-exclusive
                'schedule_ids'      => null,
                'status'            => 'pending',
                'payment_type'      => null,
                'order_code'        => $orderCode,
                'quota_applied'     => false,
            ]);

            /* CREATE TRANSACTION */
            Transaction::create([
                'order_id'                => $order->id,
                'transaction_id'          => $orderCode,
                'customer_id'             => $customer->id,
                'customer_name'           => $customerName,
                'package_id'              => $package->id,
                'amount'                  => $totalPrice,
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
                        'price'    => (int) $package->price,
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

                'callbacks' => [
                    'finish' => route('payment.success', $orderCode),
                ],
            ];

            Log::info("✅ Midtrans Params", $params);

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success'   => true,
                'snapToken' => $snapToken,
                'order_id'  => $orderCode,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("❌ MIDTRANS ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        Log::info('🔔🔔🔔 NOTIFICATION MASUK', [
            'request_all' => $request->all(),
            'php_version' => PHP_VERSION,
            'timestamp' => now(),
        ]);
        
        try {
            $notif = new MidtransNotification();

            Log::info('[Midtrans] Callback diterima', [
                'order_code'   => $notif->order_id,
                'status'       => $notif->transaction_status,
                'payment_type' => $notif->payment_type,
                'fraud_status' => $notif->fraud_status,
                'midtrans_id'  => $notif->transaction_id,
                'raw'          => (array) $notif,
            ]);

            DB::transaction(function () use ($notif) {

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

                $statusMap = [
                    'capture'    => 'paid',
                    'settlement' => 'paid',
                    'pending'    => 'pending',
                    'expire'     => 'expired',
                    'cancel'     => 'cancelled',
                    'deny'       => 'failed',
                ];

                $order->update([
                    'status'       => $statusMap[$notif->transaction_status] ?? 'failed',
                    'payment_type' => $notif->payment_type,
                ]);

                if ($transaction) {
                    $transaction->update([
                        'status'                  => $statusMap[$notif->transaction_status] ?? 'failed',
                        'payment_type'            => $notif->payment_type,
                        'midtrans_transaction_id' => $notif->transaction_id,
                        'fraud_status'            => $notif->fraud_status,
                        'signature_key'           => $notif->signature_key,
                        'amount'                  => (int) $notif->gross_amount,
                    ]);

                    Log::info('[Midtrans] Transaction updateOrCreate', ['id' => $transaction->id]);
                }

                if (!$isPaid) {
                    Log::info('[Midtrans] Payment belum settlement', ['status' => $notif->transaction_status]);
                    return;
                }

                // ========== MULAI PROSES PAYMENT SUCCESS ==========
                
                $customer = $order->customer;
                $package  = $order->package;

                Log::info('[Midtrans] APPLY PACKAGE SUCCESS', [
                    'order_id'    => $order->id,
                    'customer_id' => $customer->id,
                ]);

                // Update customer package
                $customer->update(['package_id' => $package->id]);

                // ⚠️ CEK AUTO APPLY DULU
                if (!$package->auto_apply) {
                    $order->update(['status' => 'waiting_admin']);
                    Log::info('⛔ Package tidak auto_apply');
                    return;
                }

                // Apply quota
                $customer->increment('quota', $package->quota);

                if ($package->duration_days) {
                    $customer->update([
                        'quota_expired_at' => now()->addDays($package->duration_days),
                    ]);
                }

                Log::info('🔍🔍🔍 DEBUG SCHEDULE INSERT', [
                    'order_id'          => $order->id,
                    'package_id'        => $package->id,
                    'package_name'      => $package->name,
                    'schedule_mode'     => $package->schedule_mode,
                    'auto_apply'        => $package->auto_apply,
                    'is_exclusive'      => $package->is_exclusive,
                    'selected_class_id' => $order->selected_class_id,
                    'quota_applied'     => $order->quota_applied,
                ]);

                // ========== HANDLE LOCKED MODE ==========
                if ($package->schedule_mode === 'locked') {
                    if (!$package->default_schedule_id) {
                        throw new \Exception('Default schedule belum di-set');
                    }

                    CustomerSchedule::firstOrCreate([
                        'customer_id' => $customer->id,
                        'schedule_id' => $package->default_schedule_id,
                        'order_id'    => $order->id,
                    ], [
                        'status'    => 'confirmed',
                        'joined_at' => now(),
                    ]);

                    Log::info('✅ LOCKED Schedule inserted');
                    
                    $order->update([
                        'quota_applied' => 1,
                        'status'        => 'active',
                    ]);
                    
                    return;
                }

                // ========== HANDLE BOOKING MODE ==========
                if ($package->schedule_mode === 'booking') {

                    $classKey = $order->selected_class_id;

                    Log::info('🚀 BOOKING MODE START', [
                        'class_key' => $classKey,
                        'order_id'  => $order->id,
                        'is_exclusive' => $package->is_exclusive,
                    ]);

                    // ✅ JIKA TIDAK ADA CLASS (PAKET REGULAR SEPERTI REFORMER PILATES), SKIP AUTO-INSERT
                    if (!$classKey || !$package->is_exclusive) {
                        Log::info('ℹ️ Paket regular (booking mode) - customer pilih jadwal sendiri', [
                            'order_id' => $order->id,
                            'package' => $package->name,
                            'is_exclusive' => $package->is_exclusive,
                        ]);
                        
                        $order->update([
                            'quota_applied' => 1,
                            'status'        => 'active',
                        ]);
                        
                        return;
                    }

                    // ✅ JIKA ADA CLASS + EXCLUSIVE (EXCLUSIVE CLASS PROGRAM), AUTO-INSERT JADWAL
                    $map = $this->posterScheduleMap();

                    if (!isset($map[$classKey])) {
                        Log::error('❌ Map tidak ada untuk class: ' . $classKey, [
                            'available_keys' => array_keys($map),
                        ]);
                        throw new \Exception("Map tidak ada untuk: {$classKey}");
                    }

                    Log::info('📋 Map Found', ['schedules' => $map[$classKey]]);

                    $insertedCount = 0;

                    foreach ($map[$classKey] as $row) {

    // ✅ NORMALISASI WAKTU
    $timeFormatted = strlen($row['time']) === 5
        ? $row['time'] . ':00'
        : $row['time'];

    Log::info('🔎 Looking for schedule', [
        'class_id' => $row['class_id'],
        'day'      => $row['day'],
        'time'     => $timeFormatted,
    ]);

    $schedule = Schedule::where('class_id', $row['class_id'])
        ->where('day', $row['day'])
        ->whereTime('class_time', $timeFormatted)
        ->first();

    if (!$schedule) {
        Log::error("❌ Schedule DB tidak ada", [
            'class_id' => $row['class_id'],
            'day'      => $row['day'],
            'time'     => $timeFormatted,
        ]);
        throw new \Exception("Schedule tidak ada: {$row['day']} {$timeFormatted}");
    }

    $customerSchedule = CustomerSchedule::firstOrCreate(
        [
            'customer_id' => $customer->id,
            'schedule_id' => $schedule->id,
            'order_id'    => $order->id,
        ],
        [
            'status'    => 'confirmed',
            'joined_at' => now(),
        ]
    );

    $insertedCount++;

    Log::info('✅ Customer Schedule INSERTED', [
        'customer_id' => $customer->id,
        'schedule_id' => $schedule->id,
        'day'         => $row['day'],
        'time'        => $timeFormatted,
        'new'         => $customerSchedule->wasRecentlyCreated,
    ]);
}

                    $order->update([
                        'quota_applied' => 1,
                        'status'        => 'active',
                    ]);

                    Log::info('🎉🎉🎉 BOOKING SELESAI', [
                        'order_id' => $order->id,
                        'schedules_inserted' => $insertedCount,
                    ]);
                    
                    return;
                }

                // ========== JIKA TIDAK ADA SCHEDULE MODE ==========
                Log::warning('⚠️ Package tidak punya schedule_mode atau bukan booking/locked', [
                    'package_id' => $package->id,
                    'schedule_mode' => $package->schedule_mode,
                ]);

            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error("❌❌❌ NOTIFICATION ERROR: " . $e->getMessage());
            Log::error("TRACE: " . $e->getTraceAsString());
            return response()->json(['error' => 'Gagal'], 500);
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

    /* ===============================
     * MAP JADWAL FIX SESUAI POSTER
     * =============================== */
    private function posterScheduleMap()
{
    return [
        'muaythai_intermediate' => [
            ['class_id' => 17, 'day' => 'Monday',   'time' => '19:00:00'],
            ['class_id' => 17, 'day' => 'Thursday', 'time' => '19:00:00'],
        ],

        'mat_pilates' => [
            ['class_id' => 10, 'day' => 'Wednesday', 'time' => '09:00:00'],
            ['class_id' => 10, 'day' => 'Friday',    'time' => '09:00:00'],
        ],

        'mix_class_1' => [
            ['class_id' => 10, 'day' => 'Wednesday', 'time' => '19:00:00'], // Mat Pilates
            ['class_id' => 17, 'day' => 'Sunday',    'time' => '09:00:00'], // Muaythai
        ],

        'mix_class_2' => [
            ['class_id' => 10, 'day' => 'Tuesday',  'time' => '19:00:00'], // Mat Pilates
            ['class_id' => 17, 'day' => 'Saturday', 'time' => '09:30:00'], // Muaythai
        ],

        // 🔥 INI YANG KAMU TANYAIN (MIX CLASS 3)
        'mix_class_3' => [
            ['class_id' => 10, 'day' => 'Thursday', 'time' => '19:00:00'], // ✅ Mat Pilates
            ['class_id' => 11, 'day' => 'Sunday',   'time' => '11:00:00'], // ✅ Body Shaping
        ],

        'mix_class_4' => [
            ['class_id' => 11, 'day' => 'Friday', 'time' => '19:00:00'], // Body Shaping
            ['class_id' => 17, 'day' => 'Sunday', 'time' => '10:00:00'], // Muaythai
        ],

        'muaythai_beginner' => [
            ['class_id' => 15, 'day' => 'Tuesday',  'time' => '19:00:00'],
            ['class_id' => 15, 'day' => 'Saturday', 'time' => '08:00:00'],
        ],
    ];
}
}