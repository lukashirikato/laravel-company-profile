<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Orders';
    
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->searchable()
                            ->label('Customer'),
                        
                        Forms\Components\TextInput::make('customer_name')
                            ->required()
                            ->label('Customer Name'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('order_code')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->default(fn () => 'ORD-' . strtoupper(uniqid()))
                            ->label('Order Code'),
                        
                        Forms\Components\Select::make('package_id')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->label('Package'),
                        
                        Forms\Components\Select::make('selected_class_id')
                            ->label('Selected Class')
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Amount'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Payment & Discount')
                    ->schema([
                        Forms\Components\Select::make('payment_type')
                            ->options([
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'credit_card' => 'Credit Card',
                                'e_wallet' => 'E-Wallet',
                            ])
                            ->required()
                            ->label('Payment Type'),
                        
                        Forms\Components\TextInput::make('voucher_code')
                            ->label('Voucher Code')
                            ->helperText('Enter voucher code if applicable'),
                        
                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->label('Discount'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Additional Info')
                    ->schema([
                        Forms\Components\Toggle::make('quota_applied')
                            ->label('Quota Applied')
                            ->default(false),
                        
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'paid' => 'Paid',
                            ])
                            ->required()
                            ->default('pending')
                            ->label('Status'),
                        
                        Forms\Components\DateTimePicker::make('expired_at')
                            ->label('Expired At'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Quota & Classes')
                    ->description('Kelola sisa kuota check-in dan sisa kelas booking untuk member')
                    ->schema([
                        Forms\Components\TextInput::make('remaining_classes')
                            ->label('Classes Remaining (Booking)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Sisa kelas yang bisa di-booking oleh member. Tambahkan jika member cancel/gagal book.'),

                        Forms\Components\TextInput::make('remaining_quota')
                            ->label('Remaining Quota (Check-in)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Sisa kuota check-in/check-out member.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->searchable()
                    ->sortable()
                    ->label('Order Code'),
                
               
                
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer'),
                
                Tables\Columns\TextColumn::make('package.name')
                    ->searchable()
                    ->sortable()
                    ->label('Package'),
                
                Tables\Columns\TextColumn::make('selected_class_id')
                    ->label('Selected Class'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.'))
                    ->label('Amount'),
                
                Tables\Columns\TextColumn::make('discount')
                ->formatStateUsing(fn ($state) => 'Rp' . number_format($state ?? 0, 0, ',', '.'))
                ->label('Discount'),

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
                
                Tables\Columns\BadgeColumn::make('quota_applied')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->label('Quota Applied')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                
                Tables\Columns\TextColumn::make('payment_type')
                    ->searchable()
                    ->sortable()
                    ->label('Payment Type'),
                
                Tables\Columns\TextColumn::make('voucher_code')
                    ->searchable()
                    ->sortable()
                    
                    ->color('success')
                    ->label('Voucher Code'),
                
                
                
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
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Created At'),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Updated At'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'paid' => 'Paid',
                    ])
                    ->label('Status'),
                
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'credit_card' => 'Credit Card',
                        'e_wallet' => 'E-Wallet',
                    ])
                    ->label('Payment Type'),
                
                Tables\Filters\TernaryFilter::make('quota_applied')
                    ->label('Quota Applied'),
            ])
            ->actions([
                Tables\Actions\Action::make('adjust_classes')
                    ->label('Adjust Classes')
                    ->icon('heroicon-o-adjustments')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('remaining_classes')
                            ->label('Classes Remaining (Booking)')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->remaining_classes)
                            ->minValue(0)
                            ->helperText('Ubah jumlah sisa kelas yang bisa di-booking member.'),
                        Forms\Components\TextInput::make('remaining_quota')
                            ->label('Remaining Quota (Check-in)')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->remaining_quota)
                            ->minValue(0)
                            ->helperText('Ubah jumlah sisa kuota check-in member.'),
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Perubahan')
                            ->placeholder('Contoh: Member gagal booking, cancel class, dll.')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'remaining_classes' => $data['remaining_classes'],
                            'remaining_quota' => $data['remaining_quota'],
                        ]);

                        \Filament\Facades\Filament::notify('success', 'Classes & Quota berhasil diperbarui untuk order ' . $record->order_code);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Adjust Classes & Quota')
                    ->modalSubheading('Perubahan ini akan langsung mempengaruhi kemampuan member untuk booking kelas dan check-in.'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    
}