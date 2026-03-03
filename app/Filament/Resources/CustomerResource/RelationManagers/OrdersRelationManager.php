<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Notifications\Notification;

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
            ->defaultSort('created_at', 'desc')
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

                        $record->update([
                            'remaining_classes' => $data['remaining_classes'],
                            'remaining_quota' => $data['remaining_quota'],
                        ]);

                        Notification::make()
                            ->title('Classes & Quota Diperbarui')
                            ->body(
                                "Classes: {$oldClasses} → {$data['remaining_classes']}, " .
                                "Quota: {$oldQuota} → {$data['remaining_quota']}. " .
                                "Alasan: {$data['reason']}"
                            )
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Adjust Classes & Quota')
                    ->modalSubheading(fn ($record) => "Order: {$record->order_code} | Package: " . ($record->package->name ?? '-')),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
