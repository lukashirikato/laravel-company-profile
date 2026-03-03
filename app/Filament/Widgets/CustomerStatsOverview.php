<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CustomerStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Customers', Customer::count()),
            Card::make('Orders', Order::count()),
        ];
    }
}
