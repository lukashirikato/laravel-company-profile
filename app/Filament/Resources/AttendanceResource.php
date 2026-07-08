<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Attendance';

    protected static ?string $pluralLabel = 'Attendance';

    protected static ?string $slug = 'attendance';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->label('Member'),
                Forms\Components\Select::make('schedule_id')
                    ->relationship('schedule', 'schedule_label')
                    ->searchable()
                    ->nullable()
                    ->label('Kelas'),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_code')
                    ->searchable()
                    ->nullable()
                    ->label('Order'),
                Forms\Components\TextInput::make('program')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\DateTimePicker::make('check_in_at')
                    ->label('Check In'),
                Forms\Components\DateTimePicker::make('check_out_at')
                    ->label('Check Out'),
                Forms\Components\Select::make('attendance_status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\TextInput::make('duration_minutes')
                    ->numeric()
                    ->nullable()
                    ->label('Durasi (menit)'),
                Forms\Components\Select::make('check_in_type')
                    ->options([
                        'qr' => 'QR Code',
                        'manual' => 'Manual',
                        'admin' => 'Admin',
                    ])
                    ->nullable()
                    ->label('Tipe Check In'),
                Forms\Components\Select::make('checkout_type')
                    ->options([
                        'manual' => 'Manual',
                        'auto' => 'Auto',
                    ])
                    ->nullable()
                    ->label('Tipe Check Out'),
                Forms\Components\Toggle::make('quota_deducted')
                    ->label('Quota Deducted')
                    ->default(false),
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
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('schedule.schedule_label')
                    ->label('Kelas')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('attendance_status')
                    ->label('Status')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'active',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('check_in_at')
                    ->label('Check In')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out_at')
                    ->label('Check Out')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} mnt" : '-'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('check_in_type')
                    ->label('Tipe')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('quota_deducted')
                    ->label('Quota')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attendance_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Member')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->multiple(),
                Tables\Filters\Filter::make('check_in_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('check_in_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('check_in_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('quota_deducted')
                    ->label('Quota Terpakai')
                    ->query(fn (Builder $q) => $q->where('quota_deducted', true)),
                Tables\Filters\Filter::make('has_checkout')
                    ->label('Sudah Check Out')
                    ->query(fn (Builder $q) => $q->whereNotNull('check_out_at')),
            ])
            ->defaultSort('check_in_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('checkout')
                    ->label('Check Out Manual')
                    ->icon('heroicon-o-logout')
                    ->color('warning')
                    ->visible(fn (Attendance $record) => $record->attendance_status === 'active' && !$record->check_out_at)
                    ->action(function (Attendance $record) {
                        $record->update([
                            'check_out_at' => now(),
                            'attendance_status' => 'completed',
                            'checkout_type' => 'manual',
                        ]);
                        if ($record->check_in_at) {
                            $minutes = $record->check_in_at->diffInMinutes(now());
                            $record->update(['duration_minutes' => $minutes]);
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Check Out berhasil')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records) {
                        $csv = "Member,Email,Kelas,Status,Check In,Check Out,Durasi (mnt),Lokasi,Tipe\n";
                        foreach ($records as $record) {
                            $csv .= implode(',', [
                                '"' . ($record->customer->name ?? '') . '"',
                                '"' . ($record->customer->email ?? '') . '"',
                                '"' . ($record->schedule->schedule_label ?? '') . '"',
                                $record->attendance_status,
                                $record->check_in_at ? $record->check_in_at->format('d/m/Y H:i') : '',
                                $record->check_out_at ? $record->check_out_at->format('d/m/Y H:i') : '',
                                $record->duration_minutes ?? '',
                                '"' . ($record->location ?? '') . '"',
                                $record->check_in_type ?? '',
                            ]) . "\n";
                        }
                        return Response::streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'attendance-export-' . now()->format('Ymd-His') . '.csv', [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'schedule', 'order']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
