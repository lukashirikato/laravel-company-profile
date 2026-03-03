<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Package;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Transaction Details')
                ->schema([
                    Forms\Components\Select::make('customer_id')
                        ->label('Customer')
                        ->options(Customer::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('package_id')
                        ->label('Package')
                        ->options(Package::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),

                    Forms\Components\TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->maxLength(255)
                        ->nullable(),

                    Forms\Components\TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'pending',
                            'paid' => 'paid',
                            'settlement' => 'settlement',
                            'success' => 'success',
                            'failed' => 'failed',
                            'expired' => 'expired',
                            'cancelled' => 'cancelled',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('payment_type')
                        ->label('Payment Type')
                        ->maxLength(50)
                        ->nullable(),

                    Forms\Components\TextInput::make('transaction_id')
                        ->label('Transaction ID')
                        ->maxLength(255)
                        ->nullable(),

                    Forms\Components\TextInput::make('midtrans_transaction_id')
                        ->label('Midtrans Transaction ID')
                        ->maxLength(255)
                        ->nullable(),

                    Forms\Components\TextInput::make('fraud_status')
                        ->label('Fraud Status')
                        ->maxLength(255)
                        ->nullable(),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->getStateUsing(fn($record) => $record->customer_name ?? $record->customer?->name)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn($state) => 'Rp' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => ['paid', 'settlement', 'success'],
                        'danger' => ['failed', 'expired', 'cancelled'],
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('midtrans_transaction_id')
                    ->label('Midtrans ID')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('fraud_status')
                    ->label('Fraud')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'paid' => 'paid',
                        'settlement' => 'settlement',
                        'success' => 'success',
                        'failed' => 'failed',
                        'expired' => 'expired',
                        'cancelled' => 'cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('Payment Method')
                    ->options(fn() => Transaction::query()->distinct()->pluck('payment_type', 'payment_type')->filter()->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.resources.transactions.edit', [$record->id])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Delete')
                    ->modalSubheading(fn($record) => "Delete transaction {$record->transaction_id} ? This action cannot be undone."),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'package'])->latest();
    }
}
