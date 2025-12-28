<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $navigationGroup = 'FTM Admin';
    protected static string $view = 'filament.pages.dashboard';

    // Judul halaman
    protected static ?string $title = 'FTM Admin Dashboard';

    // Tambahkan widget statistik di dashboard
    protected function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\CustomerStatsOverview::class,
        ];
    }
}
