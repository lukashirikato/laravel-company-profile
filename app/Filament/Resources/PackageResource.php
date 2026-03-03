<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use App\Models\ClassModel;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Packages';
    protected static ?string $pluralLabel = 'Packages';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Package Basic Information')
                ->description('Basic package details and identification')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Package Name')
                        ->required()
                        ->maxLength(255)
                        ->helperText('e.g., Paket 12x Muaythai'),

                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Package::class, 'slug', ignoreRecord: true)
                        ->helperText('Auto-generated from package name'),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->maxLength(1000)
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull()
                        ->helperText('Detailed package description for customers'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Package Pricing & Quota')
                ->description('Set pricing, sessions, and validity period')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Price (Rp)')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->step(1000)
                        ->prefix('Rp')
                        ->helperText('Package price in Rupiah'),

                    Forms\Components\TextInput::make('quota')
                        ->label('Session Quota')
                        ->numeric()
                        ->minValue(1)
                        ->nullable()
                        ->helperText('Number of sessions included. Leave empty for unlimited.'),

                    Forms\Components\TextInput::make('duration_days')
                        ->label('Validity Duration (Days)')
                        ->numeric()
                        ->minValue(1)
                        ->nullable()
                        ->helperText('Package validity period. Leave empty for no expiration.'),

                    Forms\Components\Select::make('type')
                        ->label('Package Type')
                        ->options([
                            'membership' => 'Membership',
                            'session' => 'Session Package',
                            'contact' => 'Contact Us',
                        ])
                        ->default('session')
                        ->nullable()
                        ->helperText('Category of this package'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Package Configuration')
                ->description('Advanced settings and behavior')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Toggle::make('is_exclusive')
                                ->label('Exclusive Package')
                                ->default(false)
                                ->helperText('Mark as premium/exclusive package'),

                            Forms\Components\Toggle::make('requires_schedule')
                                ->label('Requires Schedule Selection')
                                ->default(true)
                                ->helperText('Member must select schedule when booking'),

                            Forms\Components\Toggle::make('auto_apply')
                                ->label('Auto Apply Package')
                                ->default(true)
                                ->helperText('No admin approval needed for orders'),

                            Forms\Components\Select::make('schedule_mode')
                                ->label('Schedule Mode')
                                ->options([
                                    'locked' => 'Locked (Fixed Schedule)',
                                    'booking' => 'Booking (Member Select)',
                                ])
                                ->default('booking')
                                ->nullable()
                                ->reactive()
                                ->helperText('How members select their class schedule'),
                        ]),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('default_schedule_id')
                                ->label('Default Schedule')
                                ->relationship('defaultSchedule', 'schedule_label')
                                ->getOptionLabelFromRecordUsing(fn($record) => sprintf(
                                    '%s - %s (%s)',
                                    $record->classModel?->class_name ?? 'N/A',
                                    $record->day ?? 'N/A',
                                    $record->class_time ?? 'N/A'
                                ))
                                ->searchable()
                                ->nullable()
                                ->helperText('Fixed schedule for locked mode')
                                ->reactive()
                                ->visible(fn (callable $get) => $get('schedule_mode') === 'locked'),

                            Forms\Components\Select::make('class_id')
                                ->label('Associated Class')
                                ->relationship('class', 'class_name')
                                ->searchable()
                                ->nullable()
                                ->helperText('Link package to specific class'),
                        ]),
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('name')
                ->label('Package Name')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->color('primary')
                ->wrap(),

            Tables\Columns\TextColumn::make('price')
                ->formatStateUsing(fn ($state) =>
                    'Rp ' . number_format($state ?? 0, 0, ',', '.')
                )
                ->sortable()
                ->weight('bold')
                ->color('success')
                ->alignEnd(),

            Tables\Columns\TextColumn::make('quota')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state . ' sessions' : 'Unlimited'
                )
                ->color(fn ($record) =>
                    $record->quota ? 'info' : 'gray'
                )
                ->alignCenter(),

            Tables\Columns\TextColumn::make('duration_days')
                ->label('Duration')
                ->formatStateUsing(fn ($state) =>
                    $state ? $state . ' days' : 'No Expiry'
                )
                ->color(fn ($record) =>
                    $record->duration_days ? 'warning' : 'success'
                )
                ->alignCenter(),

            Tables\Columns\BadgeColumn::make('type')
                ->colors([
                    'primary' => 'membership',
                    'success' => 'session',
                    'warning' => 'contact',
                ])
                ->formatStateUsing(fn ($state) =>
                    ucfirst($state ?? 'N/A')
                ),

            Tables\Columns\IconColumn::make('is_exclusive')
                ->boolean()
                ->tooltip(fn ($record) =>
                    $record->is_exclusive
                        ? 'Premium Package'
                        : 'Regular Package'
                ),

            Tables\Columns\IconColumn::make('auto_apply')
                ->boolean()
                ->toggleable(),

            Tables\Columns\BadgeColumn::make('schedule_mode')
                ->colors([
                    'warning' => 'locked',
                    'info' => 'booking',
                ])
                ->formatStateUsing(fn ($state) =>
                    ucfirst($state ?? 'N/A')
                ),

            Tables\Columns\TextColumn::make('defaultSchedule.schedule_label')
                ->label('Default Schedule')
                ->default('-')
                ->limit(30)
                ->toggleable(),

            Tables\Columns\TextColumn::make('class.class_name')
                ->label('Class')
                ->default('All Classes')
                ->color(fn ($record) =>
                    $record->class ? 'info' : 'gray'
                )
                ->toggleable(),

            Tables\Columns\TextColumn::make('orders_count')
                ->label('Orders')
                ->counts('orders')
                ->sortable()
                ->alignCenter()
                ->color('success')
                ->weight('bold'),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])

        ->filters([

            Tables\Filters\SelectFilter::make('type')
                ->options([
                    'membership' => 'Membership',
                    'session' => 'Session Package',
                    'contact' => 'Contact Us',
                ])
                ->multiple(),

            Tables\Filters\TernaryFilter::make('is_exclusive'),

            Tables\Filters\TernaryFilter::make('auto_apply'),

            Tables\Filters\SelectFilter::make('schedule_mode')
                ->options([
                    'locked' => 'Locked',
                    'booking' => 'Booking',
                ])
                ->multiple(),

            Tables\Filters\SelectFilter::make('class_id')
                ->relationship('class', 'class_name')
                ->searchable()
                ->multiple(),
        ])

        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ])

        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])

        ->defaultSort('created_at', 'desc');
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['defaultSchedule', 'defaultSchedule.classModel', 'class'])
            ->withCount('orders')
            ->latest('created_at');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}