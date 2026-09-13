<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = -2;
    
    protected static string $view = 'filament.pages.dashboard';
    
    protected static bool $shouldRegisterNavigation = false; // False agar tidak duplikat dengan dashboard bawaan Filament
    
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
        return '';
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
        return [
            // Anda bisa menambahkan widget lain di sini jika diperlukan
        ];
    }
}
