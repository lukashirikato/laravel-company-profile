<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = -2;
    
    protected static string $view = 'filament.pages.dashboard';
    
    protected static bool $shouldRegisterNavigation = false; // ← UBAH jadi FALSE
protected ?string $heading = ''; // ← TAMBAHKAN ini
    
    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }
    
    public function getTitle(): string
    {
        return 'Dashboard';
    }
    
    public function getHeading(): string
    {
        return 'Dashboard';
    }
    
    public function getHeaderWidgets(): array
    {
        return [];
    }
    
    public function getFooterWidgets(): array
    {
        return [];
    }
    
    protected function getWidgets(): array
    {
        return [];
    }
}
