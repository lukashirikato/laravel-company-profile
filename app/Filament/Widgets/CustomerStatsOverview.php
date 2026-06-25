<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CustomerStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        // Cache the dashboard metrics for a short TTL to reduce DB load and
        // improve dashboard responsiveness. TTL is set to 120 seconds.
        $metrics = Cache::remember('filament.dashboard.customer_stats', 120, function () {
            return [
                'customers' => Customer::count(),
                'orders' => Order::count(),
            ];
        });

        return [
            Card::make('Customers', $metrics['customers']),
            Card::make('Orders', $metrics['orders']),
        ];
    }
}
