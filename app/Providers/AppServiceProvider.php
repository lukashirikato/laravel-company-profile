<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Midtrans Configuration
        Config::$serverKey     = config('midtrans.server_key');
        Config::$clientKey     = config('midtrans.client_key');

        // Pastikan boolean, bukan string
        Config::$isProduction  = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);

        // Keamanan & 3DS wajib true
        Config::$isSanitized   = true;
        Config::$is3ds         = true;
    }
}
