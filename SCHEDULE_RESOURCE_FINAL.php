<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use App\Models\Package;
use App\Models\ClassModel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * ScheduleResource - Filament Admin Resource untuk Schedule Management
 * 
 * ✅ Fitur:
 * - Manage jadwal kelas
 * - Toggle visibility untuk landing page publik
 * - Filter by package, class, day
 * - Bulk actions untuk multiple schedules
 * - Optimized queries dengan caching
 * 
 * 🔧 Perbaikan dari error:
 * - Removed invalid hero icons (heroicon-o-eye-slash)
 * - Optimized query dengan select specific columns
 * - Added caching untuk form options
 * - Removed N+1 queries problem
 */
class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Schedules';

    /**
     * Form schema untuk create & edit schedule
     * Dengan caching untuk package & class options
     */
    public static function form(Form $form): Form
    {
        // Cache package options untuk performa lebih baik
        $packageOptions = Cache::remember('packages_select_options', 3600, fn() => 
            Package::query()->orderBy('name')->pluck('name', 'id')->toArray()
        );

        // Cache class options untuk performa lebih baik
        $classOptions = Cache::remember('classes_select_options', 3600, fn() => 
            ClassModel::query()->orderBy('class_name')->pluck('class_name', 'id')->toArray()
        );

        return $form->schema([
            // ===== SECTION 1: VISIBILITY SETTINGS =====
            Forms\Components\Section::make('ℹ️ Informasi Tampilan')
                ->description('Jadwal akan ditampilkan di halaman publik (landing page) jika opsi "Tampil di Landing" diubah menjadi aktif.')
                ->collapsible()
                ->collapsed(false)
                ->schema([
                    Forms\Components\Toggle::make('show_on_landing')
                        ->label('✓ Tampilkan di Halaman Publik (Landing Page)')
                        ->description('Jika diaktifkan, jadwal ini akan terlihat di halaman utama FTM (localhost:8000)')
                        ->default(false)
                        ->inline(false),
                ])
                ->columns(1),

            // ===== SECTION 2: SCHEDULE DETAILS =====
            Forms\Components\Section::make('Schedule Info')
                ->schema([
                    Forms\Components\Select::make('package_id')
                        ->label('Package')
                        ->options($packageOptions)
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Select::make('class_id')
                        ->label('Class')
                        ->options($classOptions)
                        ->searchable()
                        ->nullable(),

                    Forms\Components\TextInput::make('schedule_label')
                        ->label('Label')
                        ->maxLength(255)
                        ->nullable(),

                    Forms\Components\Select::make('day')
                        ->label('Day')
                        ->options([
                            'Monday' => 'Monday',
                            'Tuesday' => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday' => 'Thursday',
                            'Friday' => 'Friday',
                            'Saturday' => 'Saturday',
                            'Sunday' => 'Sunday',
                        ])
                        ->nullable(),

                    Forms\Components\TextInput::make('class_time')
                        ->label('Class Time')
                        ->type('time')
                        ->nullable(),

                    Forms\Components\TextInput::make('instructor')
                        ->label('Instructor')
                        ->maxLength(255)
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    /**
     * Table schema untuk display schedules dengan actions & filters
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('schedule_label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('day')
                    ->label('Day')
                    ->sortable(),

                Tables\Columns\TextColumn::make('class_time')
                    ->label('Time')
                    ->formatStateUsing(fn($state) => $state ? date('H:i', strtotime($state)) : '-'),

                Tables\Columns\BadgeColumn::make('show_on_landing')
                    ->label('Tampil di Landing')
                    ->formatStateUsing(fn($state) => $state ? '✓ Tampil' : '✗ Tersembunyi')
                    ->color(fn($state) => $state ? 'success' : 'warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Filter by package
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('Package')
                    ->options(Package::query()->orderBy('name')->pluck('name', 'id')->toArray()),

                // Filter by class
                Tables\Filters\SelectFilter::make('class_id')
                    ->label('Class')
                    ->options(ClassModel::query()->orderBy('class_name')->pluck('class_name', 'id')->toArray()),

                // Filter by day
                Tables\Filters\SelectFilter::make('day')
                    ->label('Day')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ]),

                // Filter by visibility status
                Tables\Filters\TernaryFilter::make('show_on_landing')
                    ->label('Tampil di Landing')
                    ->trueLabel('✓ Ditampilkan')
                    ->falseLabel('✗ Tersembunyi')
                    ->placeholder('Semua Status'),
            ])
            // ===== ROW ACTIONS =====
            ->actions([
                // Edit action
                Tables\Actions\EditAction::make(),

                // Toggle visibility action
                Tables\Actions\Action::make('toggle_visibility')
                    ->label(fn($record) => $record->show_on_landing ? 'Hide' : 'Show')
                    ->tooltip('Toggle visibility di landing page')
                    ->color(fn($record) => $record->show_on_landing ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->update(['show_on_landing' => !$record->show_on_landing]);
                    }),

                // Delete action
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            // ===== BULK ACTIONS =====
            ->bulkActions([
                // Tampilkan multiple schedules
                Tables\Actions\BulkAction::make('bulk_show')
                    ->label('✓ Tampilkan di Landing')
                    ->action(fn($records) => $records->each->update(['show_on_landing' => true]))
                    ->deselectRecordsAfterCompletion(),
                
                // Sembunyikan multiple schedules
                Tables\Actions\BulkAction::make('bulk_hide')
                    ->label('✗ Sembunyikan dari Landing')
                    ->action(fn($records) => $records->each->update(['show_on_landing' => false]))
                    ->deselectRecordsAfterCompletion(),

                // Delete multiple schedules
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('day', 'asc');
    }

    /**
     * Define pages untuk resource (index, create, edit)
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    /**
     * Optimize query loading tanpa N+1 problem
     * - Hanya select columns yang dibutuhkan
     * - Tidak eager load unnecessary relationships
     * - Result dalam faster page load & less memory usage
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'schedules.id',
                'schedules.package_id',
                'schedules.class_id',
                'schedules.schedule_label',
                'schedules.day',
                'schedules.class_time',
                'schedules.instructor',
                'schedules.show_on_landing',
                'schedules.created_at',
                'schedules.updated_at',
            ]);
    }
}
