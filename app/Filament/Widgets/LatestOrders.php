<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->with('customer')
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
