<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Models\Instructor;
use App\Models\User;
use App\Support\UserRole;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Pelatih';

    protected static ?string $modelLabel = 'Pelatih';

    protected static ?string $pluralModelLabel = 'Pelatih';

    protected static ?string $slug = 'pelatih';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pelatih')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Login')
                            ->relationship(
                                'user',
                                'name',
                                fn (Builder $query) => $query->where('role', \App\Support\UserRole::TRAINER)->orderBy('name')
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->unique(ignoreRecord: true)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set): void {
                                if (! $state) {
                                    return;
                                }

                                $user = User::find($state);

                                if (! $user) {
                                    return;
                                }

                                $set('name', $user->name);
                                $set('phone', $user->phone);
                            })
                            ->helperText('Pilih akun ber-role Pelatih yang sudah ada, ATAU isi Email & Password di bawah untuk membuat akun baru otomatis.'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelatih')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(32)
                            ->placeholder('08xxxxxxxxxx')
                            ->helperText('Nomor tujuan reminder jadwal. Format 08xx atau 62xx.'),

                        Forms\Components\TextInput::make('specialty')
                            ->label('Spesialisasi')
                            ->maxLength(255)
                            ->placeholder('Pilates, Muay Thai, Yoga'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika pelatih sudah tidak mengajar.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Akun Login Portal Pelatih')
                    ->description('Pelatih login di /pelatih. Kosongkan jika akun sudah dipilih di atas.')
                    ->schema([
                        Forms\Components\TextInput::make('login_email')
                            ->label('Email Login')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('pelatih@email.com')
                            ->helperText('Isi untuk membuat akun baru atau mengubah email akun terhubung.'),

                        Forms\Components\TextInput::make('login_password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->autocomplete('new-password')
                            ->placeholder('Minimal 8 karakter')
                            ->helperText('Wajib saat membuat akun baru. Kosongkan saat edit jika tidak diubah.'),
                    ])
                    ->columns(2)
                    ->visible(fn (callable $get): bool => blank($get('user_id')) || filled($get('login_email'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Akun Login')
                    ->searchable()
                    ->placeholder('Belum terhubung'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('specialty')
                    ->label('Spesialisasi')
                    ->limit(30)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('schedules_count')
                    ->label('Jadwal')
                    ->counts('schedules')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Akun Login')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('WA')
                    ->icon('heroicon-o-chat')
                    ->color('success')
                    ->url(fn (Instructor $record): ?string => $record->whatsappUrl())
                    ->openUrlInNewTab()
                    ->visible(fn (Instructor $record): bool => filled($record->whatsappUrl())),

                Tables\Actions\Action::make('link_user')
                    ->label('Hubungkan Akun')
                    ->icon('heroicon-o-link')
                    ->color('warning')
                    ->visible(fn (Instructor $record): bool => blank($record->user_id))
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Login Pelatih')
                            ->options(fn (): array => User::query()
                                ->where('role', UserRole::TRAINER)
                                ->whereDoesntHave('instructorProfile')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray())
                            ->searchable()
                            ->required()
                            ->helperText('Hanya akun ber-role Pelatih yang belum terhubung.'),
                    ])
                    ->action(function (Instructor $record, array $data): void {
                        $record->update(['user_id' => $data['user_id']]);

                        Notification::make()
                            ->success()
                            ->title('Akun pelatih terhubung')
                            ->body('Pelatih sekarang bisa login ke portal /pelatih.')
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus data pelatih?')
                    ->modalSubheading('Jadwal yang terhubung akan tetap ada, tetapi relasi pelatih menjadi kosong.'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('name');
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user')
            ->withCount('schedules');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
