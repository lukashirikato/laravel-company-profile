<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getTableQuery(): Builder
    {
        // Return the query builder instead of executing it here. Filament's
        // TableWidget expects a Builder. Caching is handled at render level
        // in other widgets; to avoid changing Filament expectations we keep
        // this method returning a Builder with eager-loading and limit.
        // Eager load only necessary columns to reduce memory and query size.
        return Order::query()
            ->with(['customer:id,name', 'package:id,name'])
            ->latest()
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_code')
                ->label('Order Code')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer'),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'pending',
                    'primary' => 'processing',
                    'success' => 'completed',
                ]),

            Tables\Columns\TextColumn::make('total_amount')
                ->money('IDR')
                ->label('Amount'),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Date'),
        ];
    }
}
