<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\WhatsAppService;

use Midtrans\Config;
use Midtrans\Snap;

class GuestCheckoutController extends Controller
{
    /**
     * Status order yang dianggap pembayaran berhasil.
     */
    private const PAID_STATUSES = ['paid', 'active', 'settlement', 'success'];

    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Tampilkan form checkout untuk tamu.
     *
     * @param  string  $packageId  ID atau slug paket
     */
    public function show($packageId)
    {
        $package = $this->resolvePackage($packageId);

        if ($redirect = $this->guardPackage($package)) {
            return $redirect;
        }

        $classOptions = $this->classOptionsFor($package);

        return view('guest.checkout', compact('package', 'classOptions'));
    }

    /**
     * Proses guest checkout: buat customer (bila belum ada), order, dan snap token.
     */
    public function process(Request $request, $packageId)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
        ]);

        $package = $this->resolvePackage($packageId);

        if ($redirect = $this->guardPackage($package)) {
            return response()->json([
                'success' => false,
                'message' => 'Paket ini tidak dapat dibeli secara online.',
            ], 422);
        }

        $email = Str::lower(trim($data['email']));
        $phone = $this->normalizePhone($data['phone']);

        if ($phone === '') {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid.',
            ], 422);
        }

        $classOptions = $this->classOptionsFor($package);
        $selectedClassKey = null;

        if ($package->is_exclusive) {
            $selectedClassKey = (string) $request->input('class_id', '');

            if ($selectedClassKey === '' || !array_key_exists($selectedClassKey, $classOptions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan pilih kelas yang tersedia terlebih dahulu.',
                ], 422);
            }
        }

        $voucherCode = trim((string) $request->input('voucher_code', ''));
        $voucherCode = $voucherCode !== '' ? Str::upper($voucherCode) : null;

        $discount = $voucherCode
            ? $this->calculateVoucherDiscount($voucherCode, (int) $package->price, $package->id)
            : 0;

        if ($voucherCode && $discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau tidak berlaku untuk paket ini.',
            ], 422);
        }

        $totalAmount = max(0, (int) $package->price - $discount);

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Total pembayaran menjadi Rp0. Silakan hubungi admin untuk proses manual.',
            ], 422);
        }

        try {
            [$order, $customer] = DB::transaction(function () use ($data, $package, $email, $phone, $selectedClassKey, $discount, $totalAmount, $voucherCode) {
                $customer = $this->resolveCustomer($data['name'], $email, $phone);

                $order = Order::create([
                    'customer_id'       => $customer->id,
                    'customer_name'     => $customer->name ?: $data['name'],
                    'package_id'        => $package->id,
                    'order_code'        => $this->generateOrderCode(),
                    'amount'            => $totalAmount,
                    'discount'          => $discount,
                    'quota_applied'     => false,
                    'payment_type'      => null,
                    'voucher_code'      => $voucherCode,
                    'schedule_ids'      => null,
                    'selected_class_id' => $selectedClassKey,
                    'status'            => 'pending',
                    'expired_at'        => null,
                ]);

                Transaction::create([
                    'order_id'               => $order->id,
                    'transaction_id'         => $order->order_code,
                    'customer_id'            => $customer->id,
                    'customer_name'          => $customer->name ?: $data['name'],
                    'package_id'             => $package->id,
                    'amount'                 => $totalAmount,
                    'description'            => 'Package purchase: ' . $package->name,
                    'status'                 => 'pending',
                    'payment_type'           => null,
                    'midtrans_transaction_id' => null,
                    'fraud_status'           => null,
                    'signature_key'          => null,
                ]);

                return [$order, $customer];
            });
        } catch (\Throwable $e) {
            Log::error('❌ GUEST CHECKOUT: gagal membuat order', [
                'email' => $email,
                'package_id' => $package->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.',
            ], 500);
        }

        try {
            $snapToken = $this->createSnapToken($order, $customer, $package);
            $order->update(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            Log::error('❌ GUEST CHECKOUT: gagal membuat snap token', [
                'order_code' => $order->order_code,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gerbang pembayaran sedang tidak tersedia. Silakan coba beberapa saat lagi.',
                'order_code' => $order->order_code,
            ], 502);
        }

        $codes = (array) $request->session()->get('guest_order_codes', []);
        $codes[] = $order->order_code;
        $request->session()->put('guest_order_codes', array_values(array_unique($codes)));

        return response()->json([
            'success'      => true,
            'snapToken'    => $snapToken,
            'order_code'   => $order->order_code,
            'redirect_url' => route('guest.checkout.success', $order->order_code),
        ]);
    }

    /**
     * Halaman status pesanan guest (berhasil / pending / gagal).
     */
    public function success(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['package', 'customer', 'transaction'])
            ->firstOrFail();

        if ($this->isPaid($order->status) || $order->status === 'waiting_admin') {
            $needsActivation = $this->needsActivation($order->customer);

            return view('guest.success', [
                'order' => $order,
                'needsActivation' => $needsActivation,
                'activationUrl' => $needsActivation ? self::activationUrl($order) : null,
                'awaitingAdmin' => $order->status === 'waiting_admin',
            ]);
        }

        if ($order->status === 'pending') {
            return view('guest.pending', compact('order'));
        }

        return view('guest.failed', compact('order'));
    }

    /**
     * Cek status pesanan (dipakai halaman pending untuk polling otomatis).
     */
    public function status(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'paid' => $this->isPaymentConfirmed($order->status),
        ]);
    }

    /**
     * Buat ulang snap token untuk order pending.
     */
    public function retry(Request $request, string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['package', 'customer'])
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'failed', 'expired', 'cancelled'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini tidak dapat dibayar ulang.',
            ], 422);
        }

        $sessionCodes = (array) $request->session()->get('guest_order_codes', []);
        $emailMatches = $request->filled('email')
            && $order->customer
            && Str::lower(trim((string) $request->email)) === Str::lower((string) $order->customer->email);

        if (!in_array($orderCode, $sessionCodes, true) && !$emailMatches) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi kedaluwarsa. Silakan buka ulang tautan pembayaran dari email Anda.',
            ], 403);
        }

        try {
            $snapToken = $this->createSnapToken($order, $order->customer, $order->package);
            $order->update([
                'snap_token' => $snapToken,
                'status'     => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ GUEST CHECKOUT: gagal retry snap', [
                'order_code' => $orderCode,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gerbang pembayaran sedang tidak tersedia. Coba lagi nanti.',
            ], 502);
        }

        return response()->json([
            'success'      => true,
            'snapToken'    => $snapToken,
            'redirect_url' => route('guest.checkout.success', $order->order_code),
        ]);
    }

    /**
     * Tampilkan form aktivasi akun setelah pembayaran berhasil.
     *
     * Hanya bisa dibuka lewat signed link dari WhatsApp atau setelah
     * customer lolos verifikasi kode pesanan + email di halaman pemulihan.
     */
    public function showActivate(Request $request, string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['package', 'customer'])
            ->firstOrFail();

        if (!$this->isPaymentConfirmed($order->status)) {
            return redirect()
                ->route('guest.checkout.success', $order->order_code)
                ->with('error', 'Pembayaran belum dikonfirmasi. Aktivasi akun belum bisa dilakukan.');
        }

        if (!$this->needsActivation($order->customer)) {
            return redirect()
                ->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan masuk.');
        }

        $sessionApproved = $request->session()->get('guest_activation_order') === $order->order_code;

        if (!$sessionApproved && !$request->hasValidSignature()) {
            return redirect()
                ->route('guest.checkout.activate.recovery')
                ->with('error', 'Link aktivasi tidak valid atau sudah kedaluwarsa. Masukkan kode pesanan dan email untuk melanjutkan.');
        }

        $request->session()->put('guest_activation_order', $order->order_code);

        return view('guest.activate', compact('order'));
    }

    /**
     * Halaman pemulihan aktivasi: kode pesanan + email.
     */
    public function showActivationRecovery()
    {
        return view('guest.activate-recovery');
    }

    /**
     * Verifikasi kode pesanan + email lalu buka form pembuatan password.
     */
    public function submitActivationRecovery(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'max:40'],
            'email'      => ['required', 'email', 'max:255'],
        ]);

        $order = Order::where('order_code', Str::upper(trim($data['order_code'])))
            ->with('customer')
            ->first();

        $emailMatches = $order
            && $order->customer
            && Str::lower(trim((string) $order->customer->email)) === Str::lower(trim($data['email']));

        if (!$order || !$emailMatches || !$this->isPaymentConfirmed($order->status) || !$this->needsActivation($order->customer)) {
            return back()
                ->withInput($request->only('order_code', 'email'))
                ->withErrors(['order_code' => 'Data pesanan tidak ditemukan atau akun sudah aktif. Periksa kembali kode pesanan dan email Anda.']);
        }

        $request->session()->put('guest_activation_order', $order->order_code);

        return redirect()
            ->route('guest.checkout.activate', $order->order_code)
            ->with('success', 'Verifikasi berhasil. Silakan buat password akun Anda.');
    }

    /**
     * Kirim ulang link aktivasi ke WhatsApp customer.
     */
    public function resendActivation(Request $request, string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['package', 'customer'])
            ->firstOrFail();

        if ($request->session()->get('guest_activation_order') !== $order->order_code) {
            return redirect()
                ->route('guest.checkout.activate.recovery')
                ->with('error', 'Sesi aktivasi tidak ditemukan. Masukkan kode pesanan dan email Anda.');
        }

        if (!$this->isPaymentConfirmed($order->status) || !$this->needsActivation($order->customer)) {
            return redirect()
                ->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan masuk.');
        }

        $result = $this->sendActivationNotification($order);

        return back()->with(
            $result ? 'success' : 'error',
            $result
                ? 'Link aktivasi sudah dikirim ulang ke WhatsApp Anda.'
                : 'Link aktivasi gagal dikirim. Periksa nomor WhatsApp Anda atau hubungi admin.'
        );
    }

    /**
     * Set password guest customer lalu login otomatis.
     */
    public function activate(Request $request, string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['package', 'customer'])
            ->firstOrFail();

        if (!$this->isPaymentConfirmed($order->status)) {
            return back()->with('error', 'Pembayaran belum dikonfirmasi. Aktivasi akun belum bisa dilakukan.');
        }

        $customer = $order->customer;

        if (!$customer || !$this->needsActivation($customer)) {
            return redirect()
                ->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan masuk.');
        }

        if ($request->session()->get('guest_activation_order') !== $order->order_code) {
            return redirect()
                ->route('guest.checkout.activate.recovery')
                ->with('error', 'Link aktivasi tidak valid atau sudah kedaluwarsa. Masukkan kode pesanan dan email untuk melanjutkan.');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->update([
            'password'    => Hash::make($data['password']),
            'is_verified' => true,
        ]);

        $request->session()->forget('guest_activation_order');

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()
            ->route('member.dashboard')
            ->with('success', 'Akun berhasil diaktifkan. Selamat datang di FTM Society!');
    }

    /**
     * Resolve paket berdasarkan ID atau slug.
     */
    private function resolvePackage(string $packageId): Package
    {
        return Package::where('id', $packageId)
            ->orWhere('slug', $packageId)
            ->firstOrFail();
    }

    /**
     * Tolak paket tanpa harga / sudah login (dialihkan ke checkout member).
     */
    private function guardPackage(Package $package)
    {
        if (empty($package->price) || (float) $package->price <= 0) {
            return redirect()->away($package->whatsapp_inquiry_url);
        }

        if (Auth::guard('customer')->check()) {
            return redirect()->route('checkout.index', $package->slug ?: $package->id);
        }

        return null;
    }

    /**
     * Opsi kelas untuk paket exclusive (dari jadwal admin).
     */
    private function classOptionsFor(Package $package): array
    {
        if (!$package->is_exclusive) {
            return [];
        }

        return app(CheckoutController::class)->getExclusiveClassOptions($package);
    }

    /**
     * Hitung diskon voucher dengan aturan yang sama seperti checkout member.
     */
    private function calculateVoucherDiscount(string $voucherCode, int $price, int $packageId): int
    {
        return app(CheckoutController::class)->calculateDiscount($voucherCode, $price, $packageId);
    }

    /**
     * Cari customer berdasarkan email/telepon, atau buat baru sebagai guest.
     */
    private function resolveCustomer(string $name, string $email, string $phone): Customer
    {
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            $customer = Customer::where('phone_number', $phone)->first();
        }

        if ($customer) {
            $updates = [];

            if (blank($customer->name) && $name !== '') {
                $updates['name'] = $name;
            }

            if (blank($customer->phone_number) && $phone !== '') {
                $updates['phone_number'] = $phone;
            }

            if ($updates) {
                $customer->update($updates);
            }

            return $customer;
        }

        return Customer::create([
            'name'         => $name,
            'email'        => $email,
            'phone_number' => $phone,
            'password'     => null,
            'is_verified'  => false,
            'quota'        => 0,
        ]);
    }

    /**
     * Normalisasi nomor telepon: buang spasi, tanda hubung, dan kurung.
     */
    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]/', '', trim($phone)) ?? '';
    }

    /**
     * Generate kode order unik yang aman untuk Midtrans.
     */
    private function generateOrderCode(): string
    {
        do {
            $code = 'GST-' . strtoupper(Str::random(10));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Buat snap token Midtrans untuk order guest.
     */
    private function createSnapToken(Order $order, Customer $customer, Package $package): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => (int) $order->amount,
            ],
            'customer_details' => [
                'first_name' => $customer->name ?: 'Guest',
                'email'      => $customer->email,
                'phone'      => $customer->phone_number ?: '',
            ],
            'item_details' => [
                [
                    'id'       => 'PKG-' . $package->id,
                    'price'    => (int) $order->amount,
                    'quantity' => 1,
                    'name'     => Str::limit($package->name, 47),
                ],
            ],
            'callbacks' => [
                'finish' => route('guest.checkout.success', $order->order_code),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Status pembayaran dianggap berhasil.
     */
    private function isPaid(?string $status): bool
    {
        return in_array((string) $status, self::PAID_STATUSES, true);
    }

    /**
     * Pembayaran sudah dikonfirmasi (termasuk order yang menunggu approval admin).
     */
    private function isPaymentConfirmed(?string $status): bool
    {
        return $this->isPaid($status) || (string) $status === 'waiting_admin';
    }

    /**
     * URL aktivasi bertanda tangan, berlaku 30 hari.
     */
    public static function activationUrl(Order $order, int $days = 30): string
    {
        return URL::temporarySignedRoute(
            'guest.checkout.activate',
            now()->addDays($days),
            ['order_code' => $order->order_code]
        );
    }

    /**
     * Kirim link aktivasi ke WhatsApp customer.
     */
    private function sendActivationNotification(Order $order): bool
    {
        $customer = $order->customer;

        if (!$customer || !$customer->phone_number) {
            return false;
        }

        try {
            $result = app(WhatsAppService::class)->sendGuestActivationNotification(
                $customer->phone_number,
                [
                    'customer_name' => $customer->name,
                    'package_name'  => $order->package->name ?? 'Paket FTM',
                    'order_code'    => $order->order_code,
                    'activation_url' => self::activationUrl($order),
                ]
            );

            return (bool) ($result['success'] ?? false);
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim link aktivasi guest', [
                'order_code' => $order->order_code,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Customer butuh aktivasi akun (belum punya password).
     */
    private function needsActivation(?Customer $customer): bool
    {
        if (!$customer) {
            return false;
        }

        return empty($customer->password);
    }
}
