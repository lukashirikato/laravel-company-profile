<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\UserPermission;
use App\Support\UserRole;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Manajemen User';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'User';

    protected static ?int $navigationSort = 10;

    protected static function shouldRegisterNavigation(): bool
    {
        return \Filament\Facades\Filament::auth()->user()?->isOwner() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->description('Data dasar akun panel admin')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Farhan'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('nama@email.com'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Role & Akses')
                    ->description('Tentukan hak akses akun di dalam panel admin')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options(fn (): array => UserRole::optionsFor(
                                Auth::user()?->role ?? UserRole::USER
                            ))
                            ->reactive()
                            ->required(fn (?User $record): bool => $record === null || Auth::user()?->id !== $record->id)
                            ->default(UserRole::USER)
                            ->disabled(fn (?User $record): bool => $record !== null && Auth::user()?->id === $record->id)
                            ->helperText(function (?User $record): string {
                                if ($record !== null && Auth::user()?->id === $record->id) {
                                    return 'Role akun sendiri tidak dapat diubah untuk mencegah kehilangan akses.';
                                }

                                return Auth::user()?->isOwner()
                                    ? 'Owner dapat membuat akun Admin maupun Owner lain.'
                                    : 'Admin hanya dapat membuat akun ber-role User.';
                            }),

                        Forms\Components\Placeholder::make('hak_akses')
                            ->label('Ringkasan Hak Akses')
                            ->content(fn ($get): string => match ($get('role')) {
                                UserRole::OWNER => 'Owner: Memiliki seluruh hak akses secara otomatis (CRUD data, kelola staf, & konfigurasi).',
                                UserRole::ADMIN => 'Admin: Akses operasional. Izin tambah/hapus data diatur pada bagian izin khusus di bawah.',
                                default => 'User: tidak memiliki akses ke panel admin.',
                            })
                            ->columnSpan('full'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hak Akses Khusus Admin (Permissions)')
                    ->description('Pilih izin Tambah (create), Hapus (delete), dan pengaturan yang diizinkan untuk akun Admin ini oleh Owner.')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('Izin Khusus Operasional (Tambah, Hapus & Kelola)')
                            ->options(UserPermission::options())
                            ->columns(2)
                            ->helperText('Centang izin yang ingin diberikan oleh Owner kepada Admin ini. Tanpa centang [TAMBAH], Admin tidak bisa menambah data modul tersebut. Owner selalu memiliki semua izin otomatis.')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($get): bool => $get('role') === UserRole::ADMIN),

                Forms\Components\Section::make('Password')
                    ->description('Minimal 8 karakter. Kosongkan saat edit jika tidak ingin mengubah password.')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn (?User $record): bool => $record === null)
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->helperText('Kosongkan jika password tidak ingin diubah.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->label('Role')
                    ->formatStateUsing(fn ($state): string => UserRole::label($state))
                    ->colors([
                        'secondary' => fn ($state): bool => UserRole::normalize($state) === UserRole::USER,
                        'warning' => fn ($state): bool => UserRole::normalize($state) === UserRole::ADMIN,
                        'success' => fn ($state): bool => UserRole::normalize($state) === UserRole::OWNER,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options(UserRole::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record): bool => static::can('update', $record)),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus User')
                    ->modalSubheading('Akun ini akan dihapus permanen. Tindakan tidak dapat dibatalkan.')
                    ->successNotificationTitle('User berhasil dihapus.')
                    ->visible(fn (User $record): bool => static::can('delete', $record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus User Terpilih')
                    ->modalSubheading('Semua akun terpilih akan dihapus permanen.')
                    ->successNotificationTitle('User terpilih berhasil dihapus.')
                    ->visible(fn (): bool => static::canDeleteAny()),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
