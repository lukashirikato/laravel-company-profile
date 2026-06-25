<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'order_code';

    protected static ?string $title = 'Orders & Classes Remaining';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Info')
                ->schema([
                    Forms\Components\TextInput::make('order_code')
                        ->label('Order Code')
                        ->disabled()
                        ->columnSpan(1),

                    Forms\Components\Select::make('package_id')
                        ->relationship('package', 'name')
                        ->label('Package')
                        ->disabled()
                        ->columnSpan(1),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            'paid' => 'Paid',
                        ])
                        ->required()
                        ->label('Status')
                        ->columnSpan(1),
                ])
                ->columns(3),

            Forms\Components\Section::make('Classes & Quota')
                ->description('Kelola sisa kelas booking dan kuota check-in member')
                ->schema([
                    Forms\Components\TextInput::make('remaining_classes')
                        ->label('Classes Remaining (Booking)')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Sisa kelas yang bisa di-booking oleh member. Tambahkan jika member cancel/gagal book.')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('remaining_quota')
                        ->label('Remaining Quota (Check-in)')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Sisa kuota check-in/check-out member.')
                        ->columnSpan(1),
                ])
                ->columns(2),

            Forms\Components\Section::make('Detail')
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->prefix('Rp')
                        ->label('Amount')
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('expired_at')
                        ->label('Expired At'),
                ])
                ->columns(2)
                ->collapsible(),
        ]);
    }

    /**
     * ✅ SYNC QUOTA WHEN EDITED FROM ORDER RELATIONMANAGER
     * Ensures order.remaining_quota stays in sync with customer.quota
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Get the order being edited
        $order = $this->ownerRecord->orders()->find($this->record->id);
        
        if ($order && isset($data['remaining_quota'])) {
            $newQuota = (int) $data['remaining_quota'];
            
            \Illuminate\Support\Facades\Log::info('🔄 OrdersRelationManager: Syncing quota', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'customer_id' => $order->customer_id,
                'old_quota' => $order->remaining_quota,
                'new_quota' => $newQuota,
            ]);
            
            // Also update customer.quota to keep it in sync
            try {
                $order->customer->update(['quota' => $newQuota]);
                
                \Illuminate\Support\Facades\Log::info('✅ OrdersRelationManager: Quota synced to customer', [
                    'customer_id' => $order->customer_id,
                    'customer_quota' => $newQuota,
                ]);
                
                Notification::make()
                    ->title('✅ Order & Customer Quota Updated')
                    ->body("Order {$order->order_code}: {$order->remaining_quota} → {$newQuota}")
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('❌ OrdersRelationManager: Quota sync failed', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);
            }
        }
        
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('remaining_classes')
                    ->label('Classes Remaining')
                    ->colors([
                        'success' => fn ($state) => $state > 3,
                        'warning' => fn ($state) => $state > 0 && $state <= 3,
                        'danger' => fn ($state) => $state <= 0,
                    ])
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('remaining_quota')
                    ->label('Quota Remaining')
                    ->colors([
                        'success' => fn ($state) => $state > 3,
                        'warning' => fn ($state) => $state > 0 && $state <= 3,
                        'danger' => fn ($state) => $state <= 0,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->label('Amount'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => ['completed', 'paid'],
                        'danger' => 'cancelled',
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('expired_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Expired At'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Created'),
            ])
            // Filament relation managers use ->paginate() under the hood for large lists.
            // To set pagination, we configure the table's default pagination per page.
            ->defaultSort('created_at', 'desc')
            ->recordsPerPage(10)
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'paid' => 'Paid',
                    ]),
            ])
            ->headerActions([
                // No create - orders are created through payment flow
            ])
            ->actions([
                // Quick Adjust Classes action
                Tables\Actions\Action::make('quick_adjust')
                    ->label('Adjust')
                    ->icon('heroicon-o-adjustments')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('remaining_classes')
                            ->label('Classes Remaining (Booking)')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->remaining_classes)
                            ->minValue(0),
                        Forms\Components\TextInput::make('remaining_quota')
                            ->label('Remaining Quota (Check-in)')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->remaining_quota)
                            ->minValue(0),
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Perubahan')
                            ->placeholder('Contoh: Member gagal booking, cancel class, kompensasi, dll.')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $oldClasses = $record->remaining_classes;
                        $oldQuota = $record->remaining_quota;
                        $newQuota = (int) $data['remaining_quota'];

                        try {
                            $record->update([
                                'remaining_classes' => $data['remaining_classes'],
                                'remaining_quota' => $newQuota,
                            ]);
                            
                            // ✅ SYNC: Also update customer.quota to keep consistent
                            $record->customer->update([
                                'quota' => $newQuota,
                            ]);
                            
                            Log::info('✅ Quick Adjust: Order & Customer quota synced', [
                                'order_id' => $record->id,
                                'customer_id' => $record->customer_id,
                                'old_quota' => $oldQuota,
                                'new_quota' => $newQuota,
                                'reason' => $data['reason'],
                            ]);

                            Notification::make()
                                ->title('✅ Classes & Quota Updated & Synced')
                                ->body(
                                    "Order {$record->order_code}: Classes {$oldClasses} → {$data['remaining_classes']}, " .
                                    "Quota {$oldQuota} → {$newQuota}. Alasan: {$data['reason']}"
                                )
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Log::error('❌ Quick Adjust failed', [
                                'error' => $e->getMessage(),
                                'order_id' => $record->id,
                            ]);
                            
                            Notification::make()
                                ->title('❌ Update Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Adjust Classes & Quota')
                    ->modalSubheading(fn ($record) => "Order: {$record->order_code} | Package: " . ($record->package->name ?? '-')),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
