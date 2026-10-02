<?php

// Railway Healthcheck
Route::get('/healthz', function () {
    return response('OK', 200);
});

use Illuminate\Support\Facades\Route;
use App\Models\Schedule;
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
use App\Http\Controllers\MemberAttendanceController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerSignupController;
use App\Http\Livewire\AdminDashboard;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\MemberBookingController;
use App\Http\Controllers\Member\MyClassesController;
use App\Http\Controllers\Customer\CustomerPackageController;
use App\Http\Controllers\QRScannerController;
use App\Http\Controllers\QRApiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
| Routes accessible without authentication
*/

// Landing Page
Route::get('/join', function () {
    return view('join');
})->name('join');

Route::get('/', function () {
    try {
        $schedules = Schedule::with('classModel')
            ->where('show_on_landing', 1)
            ->limit(100)
            ->orderBy('day')
            ->orderBy('class_time')
            ->get()
            ->groupBy('day');

        $customer = auth('customer')->check() ? auth('customer')->user() : null;

        // Query dynamic packages untuk ditampilkan di landing
        $packages = Package::active()->latest()->get();

        return view('welcome', compact('schedules', 'customer', 'packages'));
    } catch (\Exception $e) {
        return view('welcome', ['schedules' => collect(), 'customer' => null, 'packages' => collect(), 'error' => $e->getMessage()]);
    }
})->name('home');

// Public API Endpoints
Route::post('/customers', [CustomerController::class, 'store'])->name('public.customers.store');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::post('/voucher/check', [VoucherController::class, 'check'])->name('voucher.check');

// Customer Signup
Route::post('/signup', [CustomerSignupController::class, 'store'])->name('signup.store');

/*
|--------------------------------------------------------------------------
| OTP VERIFICATION (Public — selama session aktif)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\OtpVerificationController;

Route::get('/verify-otp',  [OtpVerificationController::class, 'show'])->name('member.otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('member.otp.verify');
Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resend'])->name('member.otp.resend');
Route::post('/verify-otp/change-phone', [OtpVerificationController::class, 'changePhone'])->name('member.otp.change-phone');

/*
|--------------------------------------------------------------------------
| JOIN PROGRAM (Package Selection Flow)
|--------------------------------------------------------------------------
| Handles package selection from landing page → checkout
*/
Route::get('/join/{package}', function ($package) {
    // Find package by ID or slug
    $pkg = is_numeric($package)
        ? Package::find($package)
        : Package::where('slug', $package)->first();

    if (!$pkg) {
        return redirect()->route('home')->with('error', 'Package not found');
    }

    // Paket tanpa harga (Private Program, Private Group Program) tidak untuk
    // checkout — arahkan ke WhatsApp admin untuk konsultasi.
    if (empty($pkg->price) || (float) $pkg->price <= 0) {
        return redirect()->away($pkg->whatsapp_inquiry_url);
    }

    // Not logged in → redirect to signup
    if (!auth('customer')->check()) {
        session(['after_register_package' => $pkg->slug]);
        return redirect()->to(route('home') . '#signup');
    }

    // Logged in → proceed to checkout
    return redirect()->route('checkout.index', $pkg->slug);
})->name('join.package');

/*
|--------------------------------------------------------------------------
| GUEST CHECKOUT
|--------------------------------------------------------------------------
| Checkout without registration (optional feature)
*/
Route::get('/guest/checkout/{package:slug}', [GuestCheckoutController::class, 'show'])
    ->name('guest.checkout.show');
Route::post('/guest/checkout/{package:slug}', [GuestCheckoutController::class, 'process'])
    ->name('guest.checkout.process');

/*
|--------------------------------------------------------------------------
| MIDTRANS WEBHOOK
|--------------------------------------------------------------------------
| ⚠️ CRITICAL: Must be accessible without authentication
| This endpoint receives payment notifications from Midtrans
*/
Route::post('/midtrans/notification', [CheckoutController::class, 'notification'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| GUEST AUTH ROUTES
|--------------------------------------------------------------------------
| Login and registration for customers
*/
Route::middleware('guest:customer')->group(function () {
    
    // Login Routes
    Route::get('/login', [MemberAuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [MemberAuthController::class, 'login'])
        ->name('login.submit');
    
    // Registration Routes
    Route::get('/member/register', [MemberAuthController::class, 'showRegisterForm'])
        ->name('member.register');
    Route::post('/member/register', [MemberAuthController::class, 'register'])
        ->name('member.register.submit');

    // Forgot Password (Public — tanpa perlu login)
    // STEP 1 — input email + phone, kirim OTP via WhatsApp
    Route::get('/forgot-password', [MemberAuthController::class, 'showForgotPasswordForm'])
        ->name('member.forgot-password.form');
    Route::post('/forgot-password', [MemberAuthController::class, 'forgotPassword'])
        ->name('member.forgot-password.submit');

    // STEP 2 — verifikasi OTP
    Route::get('/forgot-password/otp', [MemberAuthController::class, 'showResetPasswordOtpForm'])
        ->name('member.forgot-password.otp.form');
    Route::post('/forgot-password/otp', [MemberAuthController::class, 'verifyResetPasswordOtp'])
        ->name('member.forgot-password.otp.verify');
    Route::post('/forgot-password/otp/resend', [MemberAuthController::class, 'resendResetPasswordOtp'])
        ->name('member.forgot-password.otp.resend');

    // STEP 3 — set password baru (hanya bisa diakses setelah OTP verified)
    Route::get('/forgot-password/reset', [MemberAuthController::class, 'showResetPasswordForm'])
        ->name('member.forgot-password.reset.form');
    Route::post('/forgot-password/reset', [MemberAuthController::class, 'resetPassword'])
        ->name('member.forgot-password.reset.submit');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED CUSTOMER ROUTES
|--------------------------------------------------------------------------
| Routes requiring customer authentication
*/
Route::middleware('auth:customer')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT & PAYMENT FLOW
    |--------------------------------------------------------------------------
    */
    
    // Checkout Page
    Route::get('/checkout/{package:slug}', [CheckoutController::class, 'index'])
        ->name('checkout.index');
    
    // Process Checkout (Create Midtrans Transaction)
    Route::post('/checkout/process', [CheckoutController::class, 'process'])
        ->name('checkout.process');
    
    // Check Payment Status (AJAX)
    Route::get('/checkout/status/{order_code}', [CheckoutController::class, 'status'])
        ->name('checkout.status.get');
    
    // Get Class Schedules (AJAX)
    Route::get('/checkout/class/{id}/schedules', [CheckoutController::class, 'getSchedules'])
        ->name('checkout.class.schedules');
    
    // Payment Success/Pending Page
    Route::get('/payment/success/{order_code}', [CheckoutController::class, 'success'])
        ->name('payment.success');
    
    /*
    |--------------------------------------------------------------------------
    | ORDER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('order')->name('order.')->group(function () {
        Route::post('/create', [OrderController::class, 'create'])->name('create');
        Route::get('/checkout/{id}', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/pay/{id}', [OrderController::class, 'pay'])->name('pay');
    });
    
    /*
    |--------------------------------------------------------------------------
    | INVOICE MANAGEMENT
    |--------------------------------------------------------------------------
    | Complete invoice system with multiple output formats
    */
    Route::prefix('invoice')->name('invoice.')->group(function () {
        
        // View invoice in browser (HTML)
        Route::get('/{id}', [InvoiceController::class, 'show'])
            ->name('show')
            ->where('id', '[0-9]+');
        
        // Download invoice as PDF
        Route::get('/{id}/download', [InvoiceController::class, 'download'])
            ->name('download')
            ->where('id', '[0-9]+');
        
        // View PDF in browser (stream)
        Route::get('/{id}/pdf', [InvoiceController::class, 'viewPdf'])
            ->name('pdf')
            ->where('id', '[0-9]+');
        
        // Print-friendly version
        Route::get('/{id}/print', [InvoiceController::class, 'print'])
            ->name('print')
            ->where('id', '[0-9]+');
        
        // Send invoice via email
        Route::post('/{id}/email', [InvoiceController::class, 'sendEmail'])
            ->name('email')
            ->where('id', '[0-9]+');
        
        // Get invoice data as JSON (AJAX/API)
        Route::get('/{id}/data', [InvoiceController::class, 'getInvoiceData'])
            ->name('data')
            ->where('id', '[0-9]+');
    });
    
    /*
    |--------------------------------------------------------------------------
    | PROFILE MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::get('/profile-modal', [ProfileController::class, 'show'])
        ->name('member.profile.modal');
    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| MEMBER AREA (Dashboard & Features)
|--------------------------------------------------------------------------
| Main member dashboard and related features
*/
Route::prefix('member')
    ->middleware('auth:customer')
    ->name('member.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [ProfileController::class, 'show'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | MY PACKAGES
        |--------------------------------------------------------------------------
        | View and manage customer's purchased packages
        */
        Route::prefix('packages')->name('packages.')->group(function () {
            // List all packages
            Route::get('/', [CustomerPackageController::class, 'index'])
                ->name('index');
            
            // Package details
            Route::get('/{id}', [CustomerPackageController::class, 'show'])
                ->name('detail')
                ->where('id', '[0-9]+');
            
            // Available packages API (for modal/AJAX)
            Route::get('/api/available', [CustomerPackageController::class, 'availablePackages'])
                ->name('api.available');
        });

        /*
        |--------------------------------------------------------------------------
        | CLASS BOOKING
        |--------------------------------------------------------------------------
        */
        
        // Book a new class
        Route::get('/book-class', [MemberBookingController::class, 'index'])
            ->name('book');
        Route::post('/book-class', [MemberBookingController::class, 'store'])
            ->name('book.store');
        
        // View booked classes
        Route::get('/my-classes', [MyClassesController::class, 'index'])
            ->name('my-classes');

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION HISTORY
        |--------------------------------------------------------------------------
        */
        Route::get('/transactions', [MemberTransactionController::class, 'index'])
            ->name('transactions');

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE (Check-in/Check-out)
        |--------------------------------------------------------------------------
        */
        Route::get('/attendance', [MemberAttendanceController::class, 'index'])
            ->name('attendance');
        
        Route::post('/check-in', [CheckInCheckOutController::class, 'checkIn'])
            ->name('checkin');
        Route::post('/check-out', [CheckInCheckOutController::class, 'checkOut'])
            ->name('checkout');

        /*
        |--------------------------------------------------------------------------
        | QR CODE SCANNER - ATTENDANCE
        |--------------------------------------------------------------------------
        | Member QR code scanning untuk check-in/out
        */
        Route::prefix('qr')->name('qr.')->group(function () {
            // Show QR scanner page
            Route::get('/scanner', [QRScannerController::class, 'index'])
                ->name('scanner');
            
            // Process QR scan (AJAX)
            Route::post('/scan', [QRScannerController::class, 'scan'])
                ->name('scan');
            
            // Check-out from class (AJAX)
            Route::post('/checkout', [QRScannerController::class, 'checkOut'])
                ->name('checkout');
            
            // Get active attendance (for checkout list)
            Route::get('/active', [QRScannerController::class, 'getActiveAttendance'])
                ->name('active');
        });

        /*
        |--------------------------------------------------------------------------
        | PROFILE & SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::get('/profile', [MemberProfileController::class, 'show'])
            ->name('profile');
        Route::get('/account', function () {
            return view('member.account');
        })->name('account');
        Route::get('/program-saya', [CustomerController::class, 'program'])
            ->name('program');

        /*
        |--------------------------------------------------------------------------
        | QR CODE API - Generate, Regenerate, Disable, Enable
        |--------------------------------------------------------------------------
        */
        Route::prefix('api/qr')->name('api.qr.')->group(function () {
            Route::post('/generate', [QRApiController::class, 'generate'])->name('generate');
            Route::post('/regenerate', [QRApiController::class, 'regenerate'])->name('regenerate');
            Route::post('/disable', [QRApiController::class, 'disable'])->name('disable');
            Route::post('/enable', [QRApiController::class, 'enable'])->name('enable');
            Route::get('/status', [QRApiController::class, 'status'])->name('status');
        });

        /*
        |--------------------------------------------------------------------------
        | ACCOUNT / PROFILE API — Edit profil, password, avatar,
        | notification preferences, emergency contact, login history
        |--------------------------------------------------------------------------
        */
        Route::prefix('api/profile')->name('api.profile.')->group(function () {
            Route::post('/update',         [\App\Http\Controllers\MemberAccountController::class, 'updateProfile'])->name('update');
            Route::post('/avatar',         [\App\Http\Controllers\MemberAccountController::class, 'uploadAvatar'])->name('avatar');
            Route::post('/avatar/remove',  [\App\Http\Controllers\MemberAccountController::class, 'removeAvatar'])->name('avatar.remove');
            Route::post('/password',       [\App\Http\Controllers\MemberAccountController::class, 'updatePassword'])->name('password');
            Route::post('/notifications',  [\App\Http\Controllers\MemberAccountController::class, 'updateNotifications'])->name('notifications');
            Route::get('/login-history',   [\App\Http\Controllers\MemberAccountController::class, 'loginHistory'])->name('login-history');
            Route::post('/logout-all',     [\App\Http\Controllers\MemberAccountController::class, 'logoutAll'])->name('logout-all');
        });

        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */
        Route::post('/logout', [MemberAuthController::class, 'logout'])
            ->name('logout');
    });

/*
|--------------------------------------------------------------------------
| STAFF / ADMIN SCANNER ROUTES
|--------------------------------------------------------------------------
| Scanner untuk check-in member - accessible oleh authenticated users
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:web', 'admin:web'])->group(function () {
    // Scanner Interface
    Route::get('/scanner', function () {
        return view('admin.scanner');
    })->name('scanner');
});

/*
|--------------------------------------------------------------------------
| ADMIN NOTIFICATION CENTER (di luar prefix /admin agar tidak collide
| dengan Filament 2 resource routing)
|--------------------------------------------------------------------------
| Pakai guard `web` (sama dengan Filament 2 admin) supaya session
| admin yang login lewat Filament dianggap valid.
*/
Route::prefix('staff/notifications')
    ->name('admin.notifications.')
    ->middleware(['web', 'auth:web', 'admin:web'])
    ->group(function () {
        Route::get('/feed',         [\App\Http\Controllers\Admin\NotificationController::class, 'feed'])->name('feed');
        Route::post('/{id}/read',   [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('read')->whereNumber('id');
        Route::post('/read-all',    [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('readAll');
        Route::delete('/cleanup',   [\App\Http\Controllers\Admin\NotificationController::class, 'cleanup'])->name('cleanup');
    });

/*
|--------------------------------------------------------------------------
| DASHBOARD ANALYTICS API (real-time)
|--------------------------------------------------------------------------
| Endpoint JSON untuk dashboard admin FTM Society. Guard `web` sama seperti
| Filament 2 admin sehingga session admin yang login dianggap valid.
*/
Route::prefix('staff/dashboard')
    ->name('admin.dashboard.')
    ->middleware(['web', 'auth:web', 'admin:web'])
    ->group(function () {
        Route::get('/analytics', [\App\Http\Controllers\Admin\DashboardAnalyticsController::class, 'analytics'])->name('analytics');
        Route::get('/search',    [\App\Http\Controllers\Admin\DashboardAnalyticsController::class, 'search'])->name('search');
        Route::get('/customers', [\App\Http\Controllers\Api\CustomerApiController::class, 'getList'])->name('customers');
    });

/*
|--------------------------------------------------------------------------
| DEBUG & TESTING ROUTES
|--------------------------------------------------------------------------
| Dihapus demi keamanan. Endpoint /api/customers dipindah ke grup
| staff/dashboard (dilindungi auth + role admin) di atas.
*/