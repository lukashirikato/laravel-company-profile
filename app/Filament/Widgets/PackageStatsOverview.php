<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Number;

class PackageStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalPackages = Package::count();
        $activePackages = Package::has('orders')->count();
        $totalRevenue = Order::whereNotNull('package_id')->sum('total_price');
        $totalOrders = Order::whereNotNull('package_id')->count();
        
        // Calculate average package price
        $avgPrice = Package::avg('price');
        
        // Get most popular package
        $popularPackage = Package::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->first();

        return [
            Card::make('Total Packages', $totalPackages)
                ->description('All packages in system')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary')
                ->chart($this->getPackagesTrend())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition',
                ]),

            Card::make('Active Packages', $activePackages)
                ->description('Packages with orders')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart($this->getActivePackagesTrend()),

            Card::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description($totalOrders . ' orders from packages')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart($this->getRevenueTrend()),

            Card::make('Average Package Price', 'Rp ' . number_format($avgPrice, 0, ',', '.'))
                ->description('Mean price across all packages')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('warning'),

            Card::make('Most Popular Package', $popularPackage?->name ?? 'N/A')
                ->description(($popularPackage?->orders_count ?? 0) . ' orders')
                ->descriptionIcon('heroicon-o-fire')
                ->color('danger'),

            Card::make('Exclusive Packages', Package::where('is_exclusive', true)->count())
                ->description('Premium tier packages')
                ->descriptionIcon('heroicon-o-star')
                ->color('info'),
        ];
    }

    protected function getPackagesTrend(): array
    {
        // Get last 7 days of package creation
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Package::whereDate('created_at', $date)->count();
            $data[] = $count;
        }
        return $data;
    }

    protected function getActivePackagesTrend(): array
    {
        // Get last 7 days of active packages (packages that got orders)
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $packageIds = Order::whereDate('created_at', $date)
                ->whereNotNull('package_id')
                ->distinct()
                ->pluck('package_id');
            $data[] = $packageIds->count();
        }
        return $data;
    }

    protected function getRevenueTrend(): array
    {
        // Get last 7 days of revenue from packages
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $revenue = Order::whereDate('created_at', $date)
                ->whereNotNull('package_id')
                ->sum('total_price');
            $data[] = $revenue / 1000000; // Convert to millions for better chart display
        }
        return $data;
    }
}