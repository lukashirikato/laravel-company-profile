<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PackageStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        // Cache package metrics to reduce DB load. TTL = 180s
        $stats = Cache::remember('filament.dashboard.package_stats', 180, function () {
            $totalPackages = Package::count();
            $activePackages = Package::has('orders')->count();
            $totalRevenue = Order::whereNotNull('package_id')->sum('total_price');
            $totalOrders = Order::whereNotNull('package_id')->count();
            $avgPrice = Package::avg('price');
            $popularPackage = Package::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->first();

            return compact('totalPackages', 'activePackages', 'totalRevenue', 'totalOrders', 'avgPrice', 'popularPackage');
        });

        return [
            Card::make('Total Packages', $stats['totalPackages'])
                ->description('All packages in system')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary')
                ->chart($this->getPackagesTrend())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition',
                ]),

            Card::make('Active Packages', $stats['activePackages'])
                ->description('Packages with orders')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart($this->getActivePackagesTrend()),

            Card::make('Total Revenue', 'Rp ' . number_format($stats['totalRevenue'], 0, ',', '.'))
                ->description($stats['totalOrders'] . ' orders from packages')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chart($this->getRevenueTrend()),

            Card::make('Average Package Price', 'Rp ' . number_format($stats['avgPrice'], 0, ',', '.'))
                ->description('Mean price across all packages')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('warning'),

            Card::make('Most Popular Package', $stats['popularPackage']?->name ?? 'N/A')
                ->description(($stats['popularPackage']?->orders_count ?? 0) . ' orders')
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
        // Aggregate package creations in a single query for the last 7 days
        $start = now()->startOfDay()->subDays(6);
        $rows = Cache::remember('filament.trends.packages.created_last_7_days', 180, function () use ($start) {
            return Package::where('created_at', '>=', $start)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date')
                ->toArray();
        });

        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = (int) ($rows[$date] ?? 0);
        }

        return $data;
    }

    protected function getActivePackagesTrend(): array
    {
        $start = now()->startOfDay()->subDays(6);
        $rows = Cache::remember('filament.trends.packages.active_last_7_days', 180, function () use ($start) {
            return Order::where('created_at', '>=', $start)
                ->whereNotNull('package_id')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(distinct package_id) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date')
                ->toArray();
        });

        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = (int) ($rows[$date] ?? 0);
        }
        return $data;
    }

    protected function getRevenueTrend(): array
    {
        $start = now()->startOfDay()->subDays(6);
        $rows = Cache::remember('filament.trends.packages.revenue_last_7_days', 180, function () use ($start) {
            return Order::where('created_at', '>=', $start)
                ->whereNotNull('package_id')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total_price) as revenue'))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('revenue', 'date')
                ->toArray();
        });

        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = (float) ($rows[$date] ?? 0);
            $data[] = $revenue / 1000000; // Convert to millions
        }
        return $data;
    }
}