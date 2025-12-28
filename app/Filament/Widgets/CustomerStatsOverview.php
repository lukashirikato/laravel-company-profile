<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Customer;

class CustomerStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Customers', Customer::count()),
            Card::make('Verified', Customer::where('is_verified', true)->count()),
            Card::make('Quota Tersisa', Customer::sum('quota')),
        ];
    }
}
