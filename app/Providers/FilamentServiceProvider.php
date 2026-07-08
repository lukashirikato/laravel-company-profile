<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use App\Filament\Widgets\LatestCustomers;
use App\Filament\Widgets\LatestOrders;
use App\Filament\Pages\QrScanner;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {

            // ✅ Register Pages
            Filament::registerPages([
                QrScanner::class,
            ]);

            // ✅ Register Widgets
            Filament::registerWidgets([
                LatestCustomers::class,
                LatestOrders::class,
            ]);

            // Use filemtime directly for cache busting so CSS changes reflect immediately.
            $themePath = public_path('css/filament-theme.css');
            $version = file_exists($themePath) ? filemtime($themePath) : time();

            Filament::registerStyles([
                asset('css/filament-theme.css') . '?v=' . $version,
            ]);

        });
    }
}
