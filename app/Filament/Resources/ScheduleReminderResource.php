<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleReminderResource\Pages;
use App\Models\ScheduleReminder;
use App\Services\TrainerScheduleReminderService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ScheduleReminderResource extends Resource
{
    protected static ?string $model = ScheduleReminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Reminder Pelatih';

    protected static ?string $modelLabel = 'Reminder Pelatih';

    protected static ?string $pluralModelLabel = 'Reminder Pelatih';

    protected static ?string $slug = 'reminder-pelatih';

    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.schedule_date')
                    ->label('Tanggal Sesi')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('schedule.class_time')
                    ->label('Jam')
                    ->formatStateUsing(fn ($state): string => $state ? substr((string) $state, 0, 5) : '-'),

                Tables\Columns\TextColumn::make('schedule.schedule_label')
                    ->label('Kelas')
                    ->limit(30)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Pelatih')
                    ->placeholder('Belum terhubung')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor WA')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => ScheduleReminder::STATUS_PENDING,
                        'success' => ScheduleReminder::STATUS_SENT,
                        'danger' => ScheduleReminder::STATUS_FAILED,
                        'warning' => ScheduleReminder::STATUS_SKIPPED,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('attempts')
                    ->label('Percobaan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Terkirim')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('error')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        ScheduleReminder::STATUS_PENDING => 'Pending',
                        ScheduleReminder::STATUS_SENT => 'Terkirim',
                        ScheduleReminder::STATUS_FAILED => 'Gagal',
                        ScheduleReminder::STATUS_SKIPPED => 'Dilewati',
                    ]),

                Tables\Filters\SelectFilter::make('instructor_id')
                    ->label('Pelatih')
                    ->relationship('instructor', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('schedule_date')
                    ->label('Tanggal Sesi')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereHas(
                                'schedule',
                                fn (Builder $schedule) => $schedule->whereDate('schedule_date', '>=', $date)
                            ))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereHas(
                                'schedule',
                                fn (Builder $schedule) => $schedule->whereDate('schedule_date', '<=', $date)
                            ));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('resend')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim ulang reminder?')
                    ->modalSubheading('Pesan WhatsApp akan dikirim ke nomor pada baris ini.')
                    ->action(function (ScheduleReminder $record): void {
                        $result = app(TrainerScheduleReminderService::class)->resendStored($record);

                        if ($result['success'] ?? false) {
                            Notification::make()
                                ->success()
                                ->title('Reminder terkirim')
                                ->body('Pesan WhatsApp berhasil dikirim ulang.')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->danger()
                            ->title('Reminder gagal')
                            ->body((string) ($result['message'] ?? 'Gagal mengirim WhatsApp.'))
                            ->send();
                    })
                    ->visible(fn (ScheduleReminder $record): bool => filled($record->phone)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['schedule', 'instructor'])
            ->where('type', ScheduleReminder::TYPE_H1_DAILY);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduleReminders::route('/'),
        ];
    }
}
