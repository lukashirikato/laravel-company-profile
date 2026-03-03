<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerScheduleResource\Pages;
use App\Models\CustomerSchedule;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CustomerScheduleResource extends Resource
{
    protected static ?string $model = CustomerSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Member Classes';
    protected static ?string $pluralLabel = 'Member Classes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Member Information')
                ->schema([
                    Forms\Components\Select::make('customer_id')
                        ->label('Member')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Booking Status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'attended' => 'Attended',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('pending'),

                    Forms\Components\DateTimePicker::make('joined_at')
                        ->label('Joined Date/Time')
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Class Schedule')
                ->schema([
                    Forms\Components\Select::make('schedule_id')
                        ->label('Schedule / Class')
                        ->relationship('schedule', 'schedule_label', fn($query) => $query->with('classModel'))
                        ->getOptionLabelFromRecordUsing(fn($record) => sprintf(
                            '%s - %s (%s)',
                            $record->classModel?->class_name ?? $record->schedule_label ?? 'N/A',
                            $record->day ?? 'N/A',
                            $record->class_time ?? 'N/A'
                        ))
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Forms\Components\Section::make('Order & Package Information')
                ->description('Package details dari order yang di-take')
                ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('Order / Package')
                        ->relationship('order', 'order_code', fn($query) => $query->with('package'))
                        ->getOptionLabelFromRecordUsing(fn($record) => sprintf(
                            'Order #%s - %s (Rp%s)',
                            $record->order_code ?? 'N/A',
                            $record->package?->name ?? 'Deleted Package',
                            number_format($record->package?->price ?? 0, 0, ',', '.')
                        ))
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ]),

            Forms\Components\Section::make('Schedule & Package Dates')
                ->description('Tanggal jadwal dan masa aktif paket')
                ->schema([
                    Forms\Components\Placeholder::make('schedule_date_display')
                        ->label('Schedule Date')
                        ->content(fn($record) => $record?->schedule?->schedule_date
                            ? \Carbon\Carbon::parse($record->schedule->schedule_date)->format('d/m/Y')
                            : 'N/A'),

                    Forms\Components\Placeholder::make('start_date_display')
                        ->label('Start Date')
                        ->content(function ($record) {
                            if ($record?->order?->expired_at && $record?->order?->package) {
                                $durationDays = $record->order->package->duration_days ?? 0;
                                return $durationDays > 0
                                    ? \Carbon\Carbon::parse($record->order->expired_at)->subDays($durationDays)->format('d/m/Y')
                                    : 'N/A';
                            }
                            return 'N/A';
                        }),

                    Forms\Components\Placeholder::make('end_date_display')
                        ->label('End Date')
                        ->content(fn($record) => $record?->order?->expired_at
                            ? \Carbon\Carbon::parse($record->order->expired_at)->format('d/m/Y')
                            : 'N/A'),
                ])
                ->columns(3)
                ->visibleOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('schedule.classModel.class_name')
                    ->label('Class')
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.day')
                    ->label('Day')
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.class_time')
                    ->label('Time')
                    ->formatStateUsing(function($state) {
                        if (!$state || $state === 'N/A') return '-';
                        return is_string($state) ? date('H:i', strtotime($state)) : (method_exists($state, 'format') ? $state->format('H:i') : '-');
                    }),

                Tables\Columns\TextColumn::make('schedule.schedule_date')
                    ->label('Schedule Date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('package_start_date')
                    ->label('Start Date')
                    ->formatStateUsing(function ($record) {
                        if ($record->order && $record->order->expired_at && $record->order->package) {
                            $durationDays = $record->order->package->duration_days ?? 0;
                            if ($durationDays > 0) {
                                return \Carbon\Carbon::parse($record->order->expired_at)->subDays($durationDays)->format('d/m/Y');
                            }
                        }
                        return '-';
                    })
                    ->sortable(false)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order.expired_at')
                    ->label('End Date')
                    ->formatStateUsing(fn($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y') : '-')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order.order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order.package.name')
                    ->label('Package')
                    ->default('N/A')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->tooltip(function ($record) {
                        if ($record->order && $record->order->package) {
                            return 'Package: ' . $record->order->package->name . ' (Rp' . number_format($record->order->package->price ?? 0, 0, ',', '.') . ')';
                        }
                        return 'No package data';
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => ['confirmed', 'attended'],
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'attended' => 'Attended',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Member')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('schedule_id')
                    ->label('Has Scheduled Class'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Booking')
                    ->modalSubheading('Anda yakin ingin menghapus booking ini?'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerSchedules::route('/'),
            'create' => Pages\CreateCustomerSchedule::route('/create'),
            'edit' => Pages\EditCustomerSchedule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'customer',
                'schedule',
                'schedule.classModel',
                'order',
                'order.package'
            ])
            ->latest('created_at');
    }
}