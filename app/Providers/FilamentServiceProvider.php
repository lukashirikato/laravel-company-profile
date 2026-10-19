<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
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
        });
    }
}
