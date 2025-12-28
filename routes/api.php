<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes for external services like Midtrans should be placed here.
| This ensures they are accessible without session/cookie middleware.
|
*/

// Default Laravel Sanctum User Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| MIDTRANS PAYMENT CALLBACK
|--------------------------------------------------------------------------
|
| Midtrans akan mengirimkan notifikasi ke URL ini.
| Pastikan URL ini didaftarkan di Dashboard Midtrans.
|
*/

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback');
