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

            // Cache the filemtime lookup so each admin request avoids repeated filesystem reads.
            $version = cache()->remember('filament.theme.version', 3600, function () {
                $themePath = public_path('css/filament-theme.css');

                return file_exists($themePath) ? filemtime($themePath) : time();
            });

            Filament::registerStyles([
                asset('css/filament-theme.css') . '?v=' . $version,
            ]);

        });
    }
}
