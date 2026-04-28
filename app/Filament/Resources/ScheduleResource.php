<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use App\Models\Package;
use App\Models\ClassModel;
use App\Services\ScheduleExpansionService;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Schedules';

    public static function form(Form $form): Form
    {

        $packageOptions = Package::query()->orderBy('name')->pluck('name', 'id')->toArray();

        $classOptions = Cache::remember('classes_select_options', 3600, fn() => 
            ClassModel::query()->orderBy('class_name')->pluck('class_name', 'id')->toArray()
        );

        return $form->schema([
            Forms\Components\Section::make('ℹ️ Informasi Tampilan')
                ->description('Jadwal akan ditampilkan di halaman publik (landing page) jika opsi "Tampil di Landing" diubah menjadi aktif.')
                ->collapsible()
                ->collapsed(false)
                ->schema([
                    Forms\Components\Toggle::make('show_on_landing')
                        ->label('✓ Tampilkan di Halaman Publik (Landing Page)')
                        ->helperText('Jika diaktifkan, jadwal ini akan terlihat di halaman utama ')
                        ->default(false)
                        ->inline(false),
                ])
                ->columns(1),

            Forms\Components\Section::make('Schedule Info')
                ->schema([
                    Forms\Components\CheckboxList::make('packages')
                        ->label('Packages')
                        ->helperText('Pilih satu atau lebih package untuk jadwal ini')
                        ->options($packageOptions)
                        ->searchable()
                        ->required()
                        ->relationship('packages', 'name'),

                    Forms\Components\Select::make('class_id')
                        ->label('Class')
                        ->options($classOptions)
                        ->searchable()
                        ->nullable(),

                    Forms\Components\TextInput::make('schedule_label')
                        ->label('Schedule Label')
                        ->placeholder('e.g. Mix Class 1, Morning Batch, etc')
                        ->helperText('Nama unik untuk jadwal ini (wajib diisi)')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('day')
                        ->label('Day(s)')
                        ->placeholder('Wednesday atau Wednesday & Friday')
                        ->helperText('Bisa 1 hari atau beberapa hari. Contoh: Wednesday & Friday')
                        ->required()
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            // Auto-set schedule_date berdasarkan hari pertama yang dipilih
                            if ($state) {
                                try {
                                    $service = app(ScheduleExpansionService::class);
                                    $preview = $service->buildPreview($state, Carbon::now());

                                    if (!empty($preview['first_date'])) {
                                        $set('schedule_date', $preview['first_date']);
                                    }
                                } catch (\Exception $e) {
                                    // ignore
                                }
                            }
                        })
                        ->nullable(),

                    Forms\Components\DatePicker::make('schedule_date')
                        ->label('Tanggal Jadwal')
                        ->displayFormat('d/M/Y')
                        ->helperText('Otomatis terisi dari Day, atau pilih tanggal manual')
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

            Forms\Components\Section::make('📅 Ekspansi Otomatis 1 Bulan')
                ->description('Admin bisa melihat preview jadwal dulu, lalu klik konfirmasi sebelum disimpan.')
                ->collapsible()
                ->collapsed(false)
                ->schema([
                    Forms\Components\Toggle::make('expand_to_month')
                        ->label('Expand otomatis 1 bulan')
                        ->helperText('Jika ON, jadwal akan dibuat otomatis untuk hari yang dipilih sampai akhir bulan.')
                        ->reactive()
                        ->default(false),

                    Forms\Components\Placeholder::make('expansion_preview')
                        ->label('Preview Jadwal')
                        ->content(function (callable $get) {
                            if (! $get('expand_to_month') || ! $get('day')) {
                                return new HtmlString('<span class="text-gray-500">Aktifkan toggle lalu isi hari untuk melihat preview jadwal.</span>');
                            }

                            try {
                                $service = app(ScheduleExpansionService::class);
                                $preview = $service->buildPreview((string) $get('day'), Carbon::now());
                                $days = implode(', ', $preview['days'] ?? []);
                                $dates = collect($preview['dates'] ?? [])->pluck('date')->implode(', ');

                                return new HtmlString(
                                    '<div class="space-y-1 text-sm">'
                                    . '<div><strong>Rentang:</strong> ' . e($preview['range']) . '</div>'
                                    . '<div><strong>Hari:</strong> ' . e($days ?: '-') . '</div>'
                                    . '<div><strong>Total jadwal:</strong> ' . e((string) ($preview['count'] ?? 0)) . '</div>'
                                    . '<div><strong>Tanggal:</strong> ' . e($dates ?: '-') . '</div>'
                                    . '</div>'
                                );
                            } catch (\Throwable $e) {
                                return new HtmlString('<span class="text-red-600">Preview belum bisa dibuat. Periksa input hari.</span>');
                            }
                        })
                        ->visible(fn (callable $get) => (bool) $get('expand_to_month')),

                    Forms\Components\Radio::make('schedule_preview_confirmed')
                        ->label('Sudah sesuai?')
                        ->options([
                            1 => 'Ya, lanjutkan simpan jadwal',
                            0 => 'Belum, saya mau perbaiki',
                        ])
                        ->default(null)
                        ->required(fn (callable $get) => (bool) $get('expand_to_month'))
                        ->helperText('Klik jawaban yang sesuai sebelum menyimpan.')
                        ->inline()
                        ->visible(fn (callable $get) => (bool) $get('expand_to_month')),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                // show a single representative package rather than the full list
                Tables\Columns\TextColumn::make('packageSummary')
                    ->label('Packages')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('schedule_label')
                    ->label('Label')
                    ->sortable()
                    ->searchable()
                    ->color(fn($state) => $state ? 'success' : 'danger'),

                Tables\Columns\BadgeColumn::make('series_status')
                    ->label('Series')
                    ->sortable()
                    ->colors([
                        'success' => fn ($state) => str_starts_with((string) $state, '👑 Parent'),
                        'warning' => fn ($state) => str_starts_with((string) $state, '📅 Child'),
                        'gray' => fn ($state) => $state === 'Single',
                    ]),

                Tables\Columns\TextColumn::make('classModel.class_name')
                    ->label('Class')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('day')
                    ->label('Day'),

                Tables\Columns\TextColumn::make('schedule_date')
                    ->label('Date')
                    ->date('d/M/Y'),

                Tables\Columns\TextColumn::make('class_time')
                    ->label('Time')
                    ->formatStateUsing(fn($state) => $state
                        ? (is_object($state) && method_exists($state, 'format')
                            ? $state->format('H:i')
                            : date('H:i', strtotime($state)))
                        : '-'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructor')
                    ->label('Instructor')
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('show_on_landing')
                    ->label('Visibility')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ])
                    ->formatStateUsing(fn($state) => $state ? '✓ Tampil' : '✗ Tersembunyi'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('series_status')
                    ->label('Series')
                    ->options([
                        'single' => 'Single',
                        'parent' => 'Parent',
                        'child' => 'Child',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $state = $data['value'] ?? null;

                        if ($state === 'single') {
                            $query->whereNull('series_id')->whereNull('parent_schedule_id');
                        }

                        if ($state === 'parent') {
                            $query->where('is_series_parent', true);
                        }

                        if ($state === 'child') {
                            $query->whereNotNull('parent_schedule_id');
                        }
                    }),

                Tables\Filters\SelectFilter::make('packages')
                    ->label('Package')
                    ->relationship('packages', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('class_id')
                    ->label('Class')
                    ->options(ClassModel::query()->orderBy('class_name')->pluck('class_name', 'id')->toArray()),

                // Text filter for instructor (partial match)
                Tables\Filters\Filter::make('instructor')
                    ->label('Instructor')
                    ->form([
                        Forms\Components\TextInput::make('instructor')
                            ->label('Instructor')
                            ->placeholder('Cari nama instruktur (partial)')
                            ->maxLength(255),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['instructor'])) {
                            $query->where('instructor', 'like', '%' . $data['instructor'] . '%');
                        }
                    }),

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

                Tables\Filters\TernaryFilter::make('show_on_landing')
                    ->label('Tampil di Landing')
                    ->trueLabel('✓ Ditampilkan')
                    ->falseLabel('✗ Tersembunyi')
                    ->placeholder('Semua Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('toggle_visibility')
                    ->label(fn($record) => $record->show_on_landing ? 'Hide' : 'Show')
                    ->tooltip('Toggle visibility di landing')
                    ->color(fn($record) => $record->show_on_landing ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->update(['show_on_landing' => !$record->show_on_landing]);
                    }),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulk_edit')
                    ->label('Edit Selected')
                    ->modalHeading('Edit beberapa jadwal')
                    ->form([
                        Forms\Components\CheckboxList::make('packages')
                            ->label('Packages (ganti)')
                            ->options(\App\Models\Package::query()->orderBy('name')->pluck('name', 'id')->toArray())
                            ->columns(2),

                        Forms\Components\Select::make('class_id')
                            ->label('Class')
                            ->options(Cache::remember('classes_select_options', 3600, fn() => \App\Models\ClassModel::query()->orderBy('class_name')->pluck('class_name', 'id')->toArray()))
                            ->nullable(),
                    ])
                    ->action(function ($records, $data) {
                        // $data may contain 'packages' and/or 'class_id'
                        foreach ($records as $record) {
                            if (array_key_exists('class_id', $data) && $data['class_id']) {
                                $record->class_id = $data['class_id'];
                            }

                            if (array_key_exists('packages', $data) && is_array($data['packages'])) {
                                // Replace packages for each schedule (as requested)
                                $record->packages()->sync($data['packages']);
                            }

                            $record->save();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkAction::make('bulk_show')
                    ->label('✓ Tampilkan di Landing')
                    ->action(fn($records) => $records->each->update(['show_on_landing' => true]))
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkAction::make('bulk_hide')
                    ->label('✗ Sembunyikan dari Landing')
                    ->action(fn($records) => $records->each->update(['show_on_landing' => false]))
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('day', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
            // ✅ Tampilkan SEMUA jadwal dari database (tidak ada filter)
            // Eager load packages untuk display
            return parent::getEloquentQuery()
                ->with(['packages', 'classModel'])
                ->withCount('children')
                ->select([
                    'schedules.id',
                    'schedules.class_id',
                    'schedules.schedule_label',
                    'schedules.day',
                    'schedules.schedule_date',
                    'schedules.class_time',
                    'schedules.instructor',
                    'schedules.show_on_landing',
                    'schedules.series_id',
                    'schedules.parent_schedule_id',
                    'schedules.is_series_parent',
                    'schedules.expand_to_month',
                    'schedules.created_at',
                    'schedules.updated_at',
                ])
                ->orderByDesc('created_at'); // Jadwal terbaru di atas
    }
}
