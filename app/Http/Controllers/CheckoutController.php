<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Package;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\Schedule;
use App\Models\CustomerSchedule;
use App\Services\WhatsAppService;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\BookingConfirmationNotification;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class CheckoutController extends Controller
{
    /**
     * Payment status constants
     */
    private const STATUS_PENDING = 'pending';
    private const STATUS_PAID = 'paid';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_SETTLEMENT = 'settlement';
    private const STATUS_SUCCESS = 'success';
    private const STATUS_FAILED = 'failed';
    private const STATUS_EXPIRED = 'expired';
    private const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Schedule modes
     */
    private const MODE_LOCKED = 'locked';
    private const MODE_BOOKING = 'booking';
    
    /**
     * Payment check configuration
     */
    private const WEBHOOK_WAIT_SECONDS = 3;
    
    public function __construct()
    {
        $this->configureMidtrans();
    }
    
    /**
     * Configure Midtrans settings
     */
    private function configureMidtrans(): void
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Show checkout page
     * 
     * @param Package $package
     * @return \Illuminate\View\View
     */
    public function index(Package $package)
    {
        $isExclusiveClass = (bool) $package->is_exclusive;
        $classOptions = [];
        $canSelectSchedule = false;

        if ($isExclusiveClass) {
            $classOptions = $this->getExclusiveClassOptions();
            $canSelectSchedule = true;
        }

        return view('checkout.index', compact('package', 'classOptions', 'canSelectSchedule'));
    }

    /**
     * Show payment success/pending page
     * 
     * @param string $order_code
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(string $order_code)
    {
        $order = Order::where('order_code', $order_code)
            ->with('package')
            ->firstOrFail();

        // Wait for webhook to process (sometimes webhook is faster than redirect)
        sleep(self::WEBHOOK_WAIT_SECONDS);
        $order->refresh();

        Log::info('🔍 Payment Success Page Accessed', [
            'order_code' => $order_code,
            'status' => $order->status,
            'payment_type' => $order->payment_type,
        ]);

        return $this->handlePaymentRedirect($order);
    }

    /**
     * Handle payment redirect based on order status
     * 
     * @param Order $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    private function handlePaymentRedirect(Order $order)
    {
        if ($order->status === self::STATUS_PENDING) {
            Log::info('🟡 Showing PENDING page for order: ' . $order->order_code);
            return view('checkout.pending', compact('order'));
        }
        
        if ($this->isSuccessfulPayment($order->status)) {
            Log::info('✅ Showing SUCCESS page for order: ' . $order->order_code);
            return view('checkout.success', compact('order'));
        }
        
        Log::warning('❌ Payment ' . $order->status . ' for order: ' . $order->order_code);
        return redirect()
            ->route('home')
            ->with('error', 'Payment ' . $order->status . '. Please try again.');
    }

    /**
     * Check if payment status is successful
     * 
     * @param string $status
     * @return bool
     */
    private function isSuccessfulPayment(string $status): bool
    {
        return in_array($status, [
            self::STATUS_PAID,
            self::STATUS_ACTIVE,
            self::STATUS_SETTLEMENT,
            self::STATUS_SUCCESS
        ]);
    }

    /**
     * Process checkout and create Midtrans transaction
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $package = Package::findOrFail($request->package_id);

            // Validate exclusive class selection
            if ($package->is_exclusive && !$request->input('class_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih kelas terlebih dahulu'
                ], 422);
            }

            // Calculate pricing (apply voucher if provided)
            $discount = $this->calculateDiscount($request->voucher_code, (int) $package->price, $package->id);
            $totalPrice = max(0, (int) $package->price - $discount);

            if ($totalPrice <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Total price invalid'
                ], 422);
            }

            // Create order and transaction
            $orderCode = $this->generateOrderCode();
            $order = $this->createOrder($customer, $package, $request, $orderCode, $totalPrice, $discount);
            $this->createTransaction($order, $customer, $package, $totalPrice, $orderCode);

            // Generate Midtrans snap token
            $snapToken = $this->generateSnapToken($order, $customer, $package, $totalPrice, $discount);

            return response()->json([
                'success' => true,
                'snapToken' => $snapToken,
                'order_id' => $orderCode,
            ]);

        } catch (\Exception $e) {
            Log::error("❌ CHECKOUT ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Checkout failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle Midtrans payment notification webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notification(Request $request)
    {
        Log::info('🔔 MIDTRANS NOTIFICATION RECEIVED', [
            'order_id' => $request->order_id ?? 'N/A',
            'transaction_status' => $request->transaction_status ?? 'N/A',
            'payment_type' => $request->payment_type ?? 'N/A',
            'timestamp' => now(),
        ]);

        try {
            $notif = new MidtransNotification();

            Log::info('📦 Notification Details', [
                'order_code' => $notif->order_id,
                'status' => $notif->transaction_status,
                'payment_type' => $notif->payment_type,
                'fraud_status' => $notif->fraud_status,
            ]);

            DB::transaction(function () use ($notif) {
                $this->processPaymentNotification($notif);
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error("❌ NOTIFICATION ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Failed to process notification'], 500);
        }
    }

    /**
     * Process payment notification
     * 
     * @param MidtransNotification $notif
     * @throws \Exception
     */
    private function processPaymentNotification($notif): void
    {
        $order = Order::where('order_code', $notif->order_id)
            ->lockForUpdate()
            ->firstOrFail();

        $transaction = Transaction::where('transaction_id', $notif->order_id)->first();

        $newStatus = $this->mapMidtransStatus($notif->transaction_status);
        $isPaid = $this->isPaymentPaid($notif);

        // Update order status
        $order->update([
            'status' => $newStatus,
            'payment_type' => $notif->payment_type,
        ]);

        // Update transaction
        if ($transaction) {
            $this->updateTransaction($transaction, $notif, $newStatus);
        }

        // Process payment success
        if ($isPaid) {
            $this->processSuccessfulPayment($order, $notif);
        } else {
            Log::info('⏳ Payment not yet settled', ['status' => $notif->transaction_status]);
        }
    }

    /**
     * Process successful payment
     * 
     * @param Order $order
     * @param MidtransNotification $notif
     */
    private function processSuccessfulPayment(Order $order, $notif): void
    {
        $customer = $order->customer;
        $package = $order->package;

        // ❌ TIDAK lagi set expired_at saat payment
        // expired_at akan di-set saat BOOKING PERTAMA di MemberBookingController::store()
        // $this->setPackageExpiration($order, $package);

        // Ensure the order has remaining_quota and remaining_classes assigned from the package when payment is successful.
        // remaining_quota = untuk check-in/checkout (berkurang saat check-in)
        // remaining_classes = untuk booking class (TIDAK berkurang saat check-in, hanya saat booking)
        if ((empty($order->remaining_quota) || empty($order->remaining_classes)) && $package) {
            try {
                $order->update([
                    'remaining_quota' => (int) ($package->quota ?? 0),
                    'remaining_classes' => (int) ($package->quota ?? 0),
                    'quota_applied' => true,
                ]);
                Log::info('ℹ️ Order remaining_quota and remaining_classes initialized', [
                    'order_id' => $order->id,
                    'assigned_quota' => $package->quota,
                    'assigned_classes' => $package->quota,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to initialize quota/classes for order: ' . $e->getMessage(), ['order_id' => $order->id]);
            }
        }

        Log::info('✅ APPLY PACKAGE SUCCESS', [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
        ]);

        // Update customer package
        $customer->update(['package_id' => $package->id]);

        // 📱 Kirim notifikasi WhatsApp payment berhasil
        $this->sendPaymentSuccessNotification($customer, $order);

        // Check auto apply
        if (!$package->auto_apply) {
            $order->update(['status' => 'waiting_admin']);
            Log::info('⛔ Package requires admin approval');
            return;
        }

        // Apply quota (tanpa set expiration - akan di-set saat booking pertama)
        $customer->increment('quota', $package->quota);
        
        // ❌ TIDAK lagi set quota_expired_at saat payment
        // quota_expired_at akan di-set saat BOOKING PERTAMA di MemberBookingController::store()
        // if ($package->duration_days) {
        //     $customer->update([
        //         'quota_expired_at' => Carbon::now()->addDays($package->duration_days),
        //     ]);
        // }

        // Handle schedule assignment
        $this->handleScheduleAssignment($order, $package, $customer);

        // If order used a voucher, increment its usage count (only after successful payment)
        if (!empty($order->voucher_code)) {
            try {
                $vc = Voucher::whereRaw('UPPER(code) = ?', [strtoupper($order->voucher_code)])->first();
                if ($vc) {
                    $vc->increment('used_count');
                }
            } catch (\Exception $e) {
                Log::warning('Failed to increment voucher used_count: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle schedule assignment based on package mode
     * 
     * @param Order $order
     * @param Package $package
     * @param $customer
     */
    private function handleScheduleAssignment(Order $order, Package $package, $customer): void
    {
        Log::info('🔍 SCHEDULE ASSIGNMENT', [
            'package_id' => $package->id,
            'schedule_mode' => $package->schedule_mode,
            'is_exclusive' => $package->is_exclusive,
            'selected_class_id' => $order->selected_class_id,
        ]);

        if ($package->schedule_mode === self::MODE_LOCKED) {
            $this->handleLockedSchedule($order, $package, $customer);
        } elseif ($package->schedule_mode === self::MODE_BOOKING) {
            $this->handleBookingSchedule($order, $package, $customer);
        } else {
            Log::warning('⚠️ No schedule mode configured', ['package_id' => $package->id]);
        }
    }

    /**
     * Handle locked schedule mode
     * 
     * @param Order $order
     * @param Package $package
     * @param $customer
     * @throws \Exception
     */
    private function handleLockedSchedule(Order $order, Package $package, $customer): void
    {
        if (!$package->default_schedule_id) {
            throw new \Exception('Default schedule not configured');
        }

        CustomerSchedule::firstOrCreate([
            'customer_id' => $customer->id,
            'schedule_id' => $package->default_schedule_id,
            'order_id' => $order->id,
        ], [
            'status' => 'confirmed',
            'joined_at' => now(),
        ]);

        Log::info('✅ LOCKED Schedule assigned');

        $order->update([
            'quota_applied' => true,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Handle booking schedule mode
     * 
     * @param Order $order
     * @param Package $package
     * @param $customer
     */
    private function handleBookingSchedule(Order $order, Package $package, $customer): void
    {
        $classKey = $order->selected_class_id;

        Log::info('🚀 BOOKING MODE START', [
            'class_key' => $classKey,
            'is_exclusive' => $package->is_exclusive,
        ]);

        // Regular packages (non-exclusive) - customer selects schedule themselves
        if (!$classKey || !$package->is_exclusive) {
            Log::info('ℹ️ Regular package - customer books schedule manually');
            
            $order->update([
                'quota_applied' => true,
                'status' => self::STATUS_ACTIVE,
            ]);
            
            return;
        }

        // Exclusive class - auto-assign schedules
        $this->assignExclusiveClassSchedules($order, $classKey, $customer);
    }

    /**
     * Assign schedules for exclusive class
     * 
     * @param Order $order
     * @param string $classKey
     * @param $customer
     * @throws \Exception
     */
    private function assignExclusiveClassSchedules(Order $order, string $classKey, $customer): void
    {
        $scheduleMap = $this->getExclusiveScheduleMap();

        if (!isset($scheduleMap[$classKey])) {
            Log::error('❌ Invalid class key', [
                'class_key' => $classKey,
                'available_keys' => array_keys($scheduleMap),
            ]);
            throw new \Exception("Invalid class key: {$classKey}");
        }

        $insertedCount = 0;
        $firstSchedule = null;

        foreach ($scheduleMap[$classKey] as $scheduleData) {
            $schedule = $this->findSchedule($scheduleData);

            if (!$schedule) {
                throw new \Exception(
                    "Schedule not found: {$scheduleData['day']} {$scheduleData['time']}"
                );
            }

            // Check if customer is already assigned to this schedule
            $customerSchedule = CustomerSchedule::where('customer_id', $customer->id)
                ->where('schedule_id', $schedule->id)
                ->first();

            if (!$customerSchedule) {
                $customerSchedule = CustomerSchedule::create([
                    'customer_id' => $customer->id,
                    'schedule_id' => $schedule->id,
                    'order_id' => $order->id,
                    'status' => 'confirmed',
                    'joined_at' => now(),
                ]);
            } else {
                // Update existing entry
                $customerSchedule->update([
                    'status' => 'confirmed',
                    'joined_at' => now(),
                ]);
            }

            if ($insertedCount === 0) {
                $firstSchedule = $customerSchedule;
            }

            $insertedCount++;

            Log::info('✅ Schedule assigned', [
                'schedule_id' => $schedule->id,
                'day' => $scheduleData['day'],
                'time' => $scheduleData['time'],
            ]);
        }

        // 📅 Hitung expired_at dari tanggal jadwal pertama yang ter-assign
        $expiredAt = null;
        $package = $order->package;

        if ($package && $package->duration_days) {
            $assignedScheduleIds = CustomerSchedule::where('customer_id', $customer->id)
                ->where('order_id', $order->id)
                ->pluck('schedule_id');

            $firstScheduleDate = Schedule::whereIn('id', $assignedScheduleIds)
                ->whereNotNull('schedule_date')
                ->orderBy('schedule_date', 'asc')
                ->value('schedule_date');

            if ($firstScheduleDate) {
                $expiredAt = Carbon::parse($firstScheduleDate)->addDays($package->duration_days);

                // Set quota_expired_at di customer
                $customer->update([
                    'quota_expired_at' => $expiredAt,
                ]);

                Log::info('📅 Masa aktif exclusive package dimulai dari jadwal pertama', [
                    'order_id' => $order->id,
                    'first_schedule_date' => $firstScheduleDate,
                    'expired_at' => $expiredAt->toDateTimeString(),
                    'duration_days' => $package->duration_days,
                ]);
            } else {
                Log::warning('⚠️ Tidak ada schedule_date untuk exclusive package', [
                    'order_id' => $order->id,
                    'assigned_schedule_ids' => $assignedScheduleIds->toArray(),
                ]);
            }
        }

        $orderUpdate = [
            'quota_applied' => true,
            'status' => self::STATUS_ACTIVE,
            'remaining_classes' => 0, // Exclusive: semua jadwal sudah di-assign saat checkout
        ];

        if ($expiredAt) {
            $orderUpdate['expired_at'] = $expiredAt;
        }

        $order->update($orderUpdate);

        // 📱 Kirim notifikasi WhatsApp booking berhasil dengan semua jadwal yang di-book
        $this->sendBookingConfirmationNotification($customer, $order);

        Log::info('🎉 BOOKING COMPLETED', [
            'order_id' => $order->id,
            'schedules_assigned' => $insertedCount,
            'expired_at' => $expiredAt ? $expiredAt->toDateTimeString() : null,
        ]);
    }

    /**
     * Find schedule by class, day, and time
     * 
     * @param array $scheduleData
     * @return Schedule|null
     */
    private function findSchedule(array $scheduleData): ?Schedule
    {
        $timeFormatted = strlen($scheduleData['time']) === 5
            ? $scheduleData['time'] . ':00'
            : $scheduleData['time'];

        return Schedule::where('class_id', $scheduleData['class_id'])
            ->where('day', $scheduleData['day'])
            ->whereTime('class_time', $timeFormatted)
            ->first();
    }

    /**
     * Get schedules by class ID for AJAX
     * 
     * @param int $classId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSchedules(int $classId)
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

    /**
     * Check payment status via AJAX
     * 
     * @param string $order_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(string $order_code)
    {
        $order = Order::where('order_code', $order_code)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'status' => $order->status,
            'customer_name' => $order->customer_name ?? 'Unknown',
            'amount' => $order->amount,
            'payment_type' => $order->payment_type,
        ]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Generate unique order code
     * 
     * @return string
     */
    private function generateOrderCode(): string
{
    return 'FTM-' . substr(Str::uuid(), 0, 10);
}


    /**
     * Calculate discount from voucher
     * 
     * @param string|null $voucherCode
     * @return int
     */
    private function calculateDiscount(?string $voucherCode, int $price, int $packageId): int
    {
        if (empty($voucherCode)) {
            return 0;
        }

        $code = strtoupper(trim($voucherCode));
        $voucher = Voucher::whereRaw('UPPER(code) = ?', [$code])
            ->where('active', 1)
            ->with('packages')
            ->first();

        if (!$voucher) {
            return 0;
        }

        // ✅ CHECK: Voucher applicable to this package?
        if (!$voucher->isApplicableToPackage($packageId)) {
            return 0;
        }

        // Date validity
        if ($voucher->valid_from && now()->lt($voucher->valid_from)) {
            return 0;
        }

        if ($voucher->valid_until && now()->gt($voucher->valid_until)) {
            return 0;
        }

        // Usage limit
        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return 0;
        }

        $discount = 0;

        // Voucher types: percent | nominal (fixed amount)
        $type = strtolower($voucher->type ?? 'nominal');

        if ($type === 'percent') {
            $percent = (float) $voucher->value;
            $discount = (int) floor($price * ($percent / 100));

            // cap by max_discount if set
            if ($voucher->max_discount) {
                $discount = min($discount, (int) $voucher->max_discount);
            }
        } else {
            // nominal/fixed amount
            $discount = (int) $voucher->value;
            // cannot exceed price
            $discount = min($discount, $price);
        }

        return max(0, $discount);
    }

    /**
     * Create order record
     * 
     * @param $customer
     * @param Package $package
     * @param Request $request
     * @param string $orderCode
     * @param int $totalPrice
     * @param int $discount
     * @return Order
     */
    private function createOrder($customer, Package $package, Request $request, string $orderCode, int $totalPrice, int $discount): Order
    {
        return Order::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name ?: 'Unknown',
            'package_id' => $package->id,
            'amount' => $totalPrice,
            'voucher_code' => $request->voucher_code,
            'discount' => $discount,
            'selected_class_id' => $request->input('class_id'),
            'schedule_ids' => null,
            'status' => self::STATUS_PENDING,
            'payment_type' => null,
            'order_code' => $orderCode,
            'quota_applied' => false,
            'expired_at' => null,
        ]);
    }

    /**
     * Create transaction record
     * 
     * @param Order $order
     * @param $customer
     * @param Package $package
     * @param int $totalPrice
     * @param string $orderCode
     */
    private function createTransaction(Order $order, $customer, Package $package, int $totalPrice, string $orderCode): void
    {
        Transaction::create([
            'order_id' => $order->id,
            'transaction_id' => $orderCode,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name ?: 'Unknown',
            'package_id' => $package->id,
            'amount' => $totalPrice,
            'description' => 'Package purchase: ' . $package->name,
            'status' => self::STATUS_PENDING,
            'payment_type' => null,
            'midtrans_transaction_id' => null,
            'fraud_status' => null,
            'signature_key' => null,
        ]);
    }

    /**
     * Generate Midtrans snap token
     * 
     * @param Order $order
     * @param $customer
     * @param Package $package
     * @param int $totalPrice
     * @param int $discount
     * @return string
     */
    private function generateSnapToken(Order $order, $customer, Package $package, int $totalPrice, int $discount): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $customer->name ?: 'Customer',
                'email' => $customer->email ?? 'customer@example.com',
                'phone' => $customer->phone_number ?? '08111111111',
            ],
            'item_details' => array_filter([
                [
                    'id' => 'PKG-' . $package->id,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => $package->name,
                ],
                $discount > 0 ? [
                    'id' => 'DISCOUNT',
                    'price' => -$discount,
                    'quantity' => 1,
                    'name' => 'Voucher Discount',
                ] : null
            ]),
            'callbacks' => [
                'finish' => route('payment.success', $order->order_code),
            ],
        ];

        Log::info("✅ Midtrans Request", $params);

        return Snap::getSnapToken($params);
    }

    /**
     * Set package expiration date
     * 
     * @param Order $order
     * @param Package $package
     */
    private function setPackageExpiration(Order $order, Package $package): void
    {
        if ($package->duration_days) {
            $expiredAt = Carbon::now()->addDays($package->duration_days);
            $order->update(['expired_at' => $expiredAt]);

            Log::info('✅ Expiration date set', [
                'order_id' => $order->id,
                'duration_days' => $package->duration_days,
                'expired_at' => $expiredAt->format('Y-m-d H:i:s'),
            ]);
        } else {
            Log::info('ℹ️ Package has no duration (unlimited)', [
                'package_id' => $package->id,
            ]);
        }
    }

    /**
     * Update transaction record
     * 
     * @param Transaction $transaction
     * @param MidtransNotification $notif
     * @param string $newStatus
     */
    private function updateTransaction(Transaction $transaction, $notif, string $newStatus): void
    {
        $transaction->update([
            'status' => $newStatus,
            'payment_type' => $notif->payment_type,
            'midtrans_transaction_id' => $notif->transaction_id,
            'fraud_status' => $notif->fraud_status,
            'signature_key' => $notif->signature_key,
            'amount' => (int) $notif->gross_amount,
        ]);

        Log::info('📝 Transaction updated', ['transaction_id' => $transaction->id]);
    }

    /**
     * Map Midtrans status to internal status
     * 
     * @param string $midtransStatus
     * @return string
     */
    private function mapMidtransStatus(string $midtransStatus): string
    {
        $statusMap = [
            'capture' => self::STATUS_PAID,
            'settlement' => self::STATUS_PAID,
            'pending' => self::STATUS_PENDING,
            'expire' => self::STATUS_EXPIRED,
            'cancel' => self::STATUS_CANCELLED,
            'deny' => self::STATUS_FAILED,
        ];

        return $statusMap[$midtransStatus] ?? self::STATUS_FAILED;
    }

    /**
     * Check if payment is paid based on Midtrans notification
     * 
     * @param MidtransNotification $notif
     * @return bool
     */
    private function isPaymentPaid($notif): bool
    {
        $paidStatuses = ['settlement', 'capture'];
        
        return in_array($notif->transaction_status, $paidStatuses)
            && $notif->fraud_status !== 'deny';
    }

    /**
     * Get exclusive class options for dropdown
     * 
     * @return array
     */
    private function getExclusiveClassOptions(): array
    {
        return [
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
    }

    /**
     * Get exclusive class schedule mapping
     * 
     * @return array
     */
    private function getExclusiveScheduleMap(): array
    {
        return [
            'muaythai_intermediate' => [
                ['class_id' => 17, 'day' => 'Monday', 'time' => '19:00:00'],
                ['class_id' => 17, 'day' => 'Thursday', 'time' => '19:00:00'],
            ],
            'mat_pilates' => [
                ['class_id' => 10, 'day' => 'Wednesday', 'time' => '09:00:00'],
                ['class_id' => 10, 'day' => 'Friday', 'time' => '09:00:00'],
            ],
            'mix_class_1' => [
                ['class_id' => 10, 'day' => 'Wednesday', 'time' => '19:00:00'],
                ['class_id' => 17, 'day' => 'Sunday', 'time' => '09:00:00'],
            ],
            'mix_class_2' => [
                ['class_id' => 10, 'day' => 'Tuesday', 'time' => '19:00:00'],
                ['class_id' => 17, 'day' => 'Saturday', 'time' => '09:30:00'],
            ],
            'mix_class_3' => [
                ['class_id' => 10, 'day' => 'Thursday', 'time' => '19:00:00'],
                ['class_id' => 11, 'day' => 'Sunday', 'time' => '11:00:00'],
            ],
            'mix_class_4' => [
                ['class_id' => 11, 'day' => 'Friday', 'time' => '19:00:00'],
                ['class_id' => 17, 'day' => 'Sunday', 'time' => '10:00:00'],
            ],
            'muaythai_beginner' => [
                ['class_id' => 15, 'day' => 'Tuesday', 'time' => '19:00:00'],
                ['class_id' => 15, 'day' => 'Saturday', 'time' => '08:00:00'],
            ],
        ];
    }

    // ==================== WHATSAPP NOTIFICATION METHODS ====================

    /**
     * Send payment success notification via WhatsApp
     * 
     * @param $customer
     * @param Order $order
     */
    private function sendPaymentSuccessNotification($customer, Order $order): void
    {
        try {
            if (!$customer->phone_number) {
                Log::warning('⚠️ Customer has no phone number', [
                    'customer_id' => $customer->id,
                ]);
                return;
            }

            $whatsapp = new WhatsAppService();
            
            $result = $whatsapp->sendPaymentSuccessNotification(
                $customer->phone_number,
                [
                    'customer_name' => $customer->name,
                    'package_name' => $order->package->name,
                    'amount' => $order->amount,
                    'order_code' => $order->order_code,
                    'package_days' => $order->package->duration_days ?? 'unlimited',
                ]
            );

            if ($result['success']) {
                Log::info('✅ Payment success WhatsApp notification sent', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'phone' => $customer->phone_number,
                ]);
            } else {
                Log::warning('⚠️ Failed to send payment notification', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Payment notification error: ' . $e->getMessage(), [
                'customer_id' => $customer->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Send booking confirmation notification via WhatsApp
     * 
     * @param $customer
     * @param Order $order
     */
    private function sendBookingConfirmationNotification($customer, Order $order): void
    {
        try {
            if (!$customer->phone_number) {
                Log::warning('⚠️ Customer has no phone number', [
                    'customer_id' => $customer->id,
                ]);
                return;
            }

            // Ambil semua schedule yang di-assign untuk order ini
            $customerSchedules = CustomerSchedule::where('order_id', $order->id)
                ->with(['schedule.classModel'])
                ->get();

            if ($customerSchedules->isEmpty()) {
                Log::warning('⚠️ No schedules found for order', [
                    'order_id' => $order->id,
                ]);
                return;
            }

            // Build array dari semua jadwal untuk dikirim ke WhatsApp
            $schedulesList = [];
            foreach ($customerSchedules as $cs) {
                $schedule = $cs->schedule;
                $class = $schedule->classModel;
                
                $schedulesList[] = [
                    'day' => ucfirst($schedule->day ?? 'N/A'),
                    'time' => $schedule->class_time ? Carbon::parse($schedule->class_time)->format('H:i') : '-',
                    'class_name' => $class->class_name ?? 'Class',
                    'level' => $class->level ?? '',
                    'instructor' => $schedule->instructor ?? 'Instructor',
                ];
            }

            $whatsapp = new WhatsAppService();
            
            $result = $whatsapp->sendBookingConfirmationNotification(
                $customer->phone_number,
                [
                    'customer_name' => $customer->name,
                    'package_name' => $order->package->name ?? 'Package',
                    'schedules' => $schedulesList,
                    'total_schedules' => count($schedulesList),
                ]
            );

            if ($result['success']) {
                Log::info('✅ Booking confirmation WhatsApp notification sent', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'phone' => $customer->phone_number,
                    'schedules' => count($schedulesList),
                ]);
            } else {
                Log::warning('⚠️ Failed to send booking notification', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Booking notification error: ' . $e->getMessage(), [
                'customer_id' => $customer->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}