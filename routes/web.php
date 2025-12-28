<?php

use Illuminate\Support\Facades\Route;
use App\Models\Schedule;
use App\Models\Customer;
use App\Models\Package;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckInCheckOutController;
use App\Http\Controllers\MemberTransactionController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerSignupController;
use App\Http\Livewire\AdminDashboard;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\MemberBookingController;


/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $schedules = Schedule::with('classModel')
        ->where('show_on_landing', 1)
        ->orderBy('day')
        ->orderBy('class_time')
        ->get()
        ->groupBy('day');

    $customer = auth('customer')->check()
        ? auth('customer')->user()
        : null;

    return view('welcome', compact('schedules', 'customer'));
})->name('home');


Route::post('/customers', [CustomerController::class, 'store'])->name('public.customers.store');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::post('/voucher/check', [VoucherController::class, 'check']);


/*
|--------------------------------------------------------------------------
| JOIN PROGRAM (Landing → Checkout)
|--------------------------------------------------------------------------
*/
Route::get('/join/{package}', function ($package) {
    $pkg = is_numeric($package)
        ? Package::find($package)
        : Package::where('slug', $package)->first();

    if (!$pkg) return redirect()->route('home')->with('error', 'Paket tidak ditemukan');

    // Kalau belum login → arahkan ke signup & simpan paket yang dipilih
    if (!auth('customer')->check()) {
        session(['after_register_package' => $pkg->slug]);
        return redirect()->to(route('home') . '#signup');
    }

    return redirect()->route('checkout.index', $pkg->slug);
})->name('join.package');


/*
|--------------------------------------------------------------------------
| GUEST CHECKOUT
|--------------------------------------------------------------------------
*/
Route::get('/guest/checkout/{package:slug}', [GuestCheckoutController::class, 'show'])->name('guest.checkout.show');
Route::post('/guest/checkout/{package:slug}', [GuestCheckoutController::class, 'process'])->name('guest.checkout.process');


/*
|--------------------------------------------------------------------------
| USER LOGIN (alias)
|--------------------------------------------------------------------------
*/
Route::get('/login', [MemberAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [MemberAuthController::class, 'login'])->name('login.submit');


/*
|--------------------------------------------------------------------------
| CHECKOUT WITH MIDTRANS SNAP
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->group(function () {

    Route::get('/checkout/{package:slug}', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/checkout/status/{order_code}', [CheckoutController::class, 'status'])
        ->name('checkout.status.get');
});


Route::middleware(['auth:customer'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

        Route::get('/dashboard', [ProfileController::class, 'show'])
            ->name('dashboard');

        Route::get('/book-class', [MemberBookingController::class, 'index'])
            ->name('book');

        Route::post('/book-class', [MemberBookingController::class, 'store'])
            ->name('book.store');

        Route::get('/my-classes', [MemberBookingController::class, 'myClasses'])
            ->name('my-classes');

        Route::get('/transactions', [MemberTransactionController::class, 'index'])
            ->name('transactions');
});



/*
|--------------------------------------------------------------------------
| CLASS SCHEDULE AJAX
|--------------------------------------------------------------------------
*/
Route::get('/checkout/class/{id}/schedules', [CheckoutController::class, 'getSchedules'])
    ->name('checkout.class.schedules');


/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (WAJIB DI LUAR AUTH)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');


/*
|--------------------------------------------------------------------------
| ORDER FLOW (Create → Checkout → Bayar)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->group(function () {

    Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/order/checkout/{id}', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/order/pay/{id}', [OrderController::class, 'pay'])->name('order.pay');

});


/*
|--------------------------------------------------------------------------
| PAYMENT SUCCESS PAGE
|--------------------------------------------------------------------------
*/
Route::get('/payment/success/{order_code}', function ($order_code) {
    $order = \App\Models\Order::where('order_code', $order_code)->firstOrFail();
    return view('checkout.success', compact('order'));
})->middleware('auth:customer')->name('payment.success');


/*
|--------------------------------------------------------------------------
| SIGNUP CUSTOMER
|--------------------------------------------------------------------------
*/
Route::post('/signup', [CustomerSignupController::class, 'store'])->name('signup.store');


/*
|--------------------------------------------------------------------------
| MEMBER AUTH PAGES
|--------------------------------------------------------------------------
*/
Route::middleware('guest:customer')->group(function () {

    Route::get('/login-member', [MemberAuthController::class, 'showLoginForm'])->name('member.login.form');
    Route::post('/login-member', [MemberAuthController::class, 'login'])->name('member.login.submit');

    Route::get('/member/register', [MemberAuthController::class, 'showRegisterForm'])->name('member.register');
    Route::post('/member/register', [MemberAuthController::class, 'register'])->name('member.register.submit');

});


/*
|--------------------------------------------------------------------------
| MEMBER AREA
|--------------------------------------------------------------------------
*/
Route::prefix('member')->middleware('auth:customer')->group(function () {

    Route::get('/profile', [MemberProfileController::class, 'show'])->name('member.profile');
    Route::post('/check-in', [CheckInCheckOutController::class, 'checkIn'])->name('member.checkin');
    Route::post('/check-out', [CheckInCheckOutController::class, 'checkOut'])->name('member.checkout');
    Route::get('/transactions', [MemberTransactionController::class, 'index'])->name('member.transactions');
    Route::get('/program-saya', [CustomerController::class, 'program'])->name('member.program');

    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
    Route::get('/change-password', [MemberAuthController::class, 'showChangePasswordForm'])->name('member.password.form');
    Route::post('/change-password', [MemberAuthController::class, 'changePassword'])->name('member.change-password');

});


/*
|--------------------------------------------------------------------------
| PROFILE + INVOICE (FIX DUPLIKAT ROUTE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->group(function () {

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile-modal', [ProfileController::class, 'show'])->name('member.profile.modal');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // HANYA SATU ROUTE INVOICE — TIDAK BOLEH DUPLIKAT
    Route::get('/invoice/{id}', [OrderController::class, 'invoice'])->name('invoice.show');

});


/*
|--------------------------------------------------------------------------
| TEST TRANSACTION (Debug)
|--------------------------------------------------------------------------
*/
Route::get('/test-transaction', function () {
    $trx = \App\Models\Transaction::with('customer')->latest()->first();
    return [
        'transaction_id' => $trx->id ?? null,
        'order_id'       => $trx->order_id ?? null,
        'customer_id'    => $trx->customer_id ?? null,
        'customer_name'  => $trx->customer->name ?? 'TIDAK ADA',
        'package_id'     => $trx->package_id ?? null,
        'status'         => $trx->status ?? null,
    ];
});
