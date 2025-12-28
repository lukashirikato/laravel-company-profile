<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Models\Customer;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Customers';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer Info')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Full Name'),

                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->label('Phone Number')
                    ->nullable(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(
                        table: Customer::class,
                        column: 'email',
                        ignoreRecord: true
                    ),

                Forms\Components\DatePicker::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Program & Membership')->schema([
                Forms\Components\TextInput::make('program'),

                Forms\Components\TextInput::make('quota')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                Forms\Components\Select::make('membership')
                    ->options([
                        'Basic' => 'Basic',
                        'Premium' => 'Premium',
                    ])
                    ->nullable(),

                Forms\Components\Checkbox::make('is_verified')
                    ->label('Verified'),
            ])->columns(2),

            Forms\Components\Section::make('Additional Info')->schema([
                Forms\Components\Textarea::make('goals')->rows(2),
                Forms\Components\Textarea::make('kondisi_khusus')->rows(2),
                Forms\Components\TextInput::make('referensi'),
                Forms\Components\Textarea::make('pengalaman')->rows(2),

                Forms\Components\Select::make('is_muslim')
                    ->label('Muslimah')
                    ->options([
                        1 => 'Ya',
                        0 => 'Tidak',
                    ])
                    ->nullable(),

                Forms\Components\TextInput::make('voucher_code'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('program'),

                // gunakan $record (bukan $state)
                Tables\Columns\BadgeColumn::make('membership')
                    ->colors([
                        'warning' => fn ($record) => (string) $record->membership === 'Basic',
                        'success' => fn ($record) => (string) $record->membership === 'Premium',
                    ]),

                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),

                // warna berdasarkan field quota (pakai $record)
                Tables\Columns\TextColumn::make('quota')
                    ->sortable()
                    ->color(fn ($record) => $record->quota > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('goals')->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('kondisi_khusus')->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('referensi')->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('pengalaman')->wrap()->toggleable(),

                Tables\Columns\BadgeColumn::make('is_muslim')
                    ->label('Muslimah')
                    ->enum([
                        1 => 'Ya',
                        0 => 'Tidak',
                    ])
                    ->colors([
                        'success' => fn ($record) => (int) $record->is_muslim === 1,
                        'danger' => fn ($record) => (int) $record->is_muslim === 0,
                    ]),

                Tables\Columns\TextColumn::make('voucher_code')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])

            ->actions([
                // CHECK-IN ACTION
                Action::make('checkin')
                    ->label('Cek In')
                    ->icon('heroicon-o-check')
                    ->color('blue')
                    ->requiresConfirmation()
                    ->visible(fn (Customer $record) => $record->quota > 0)
                    ->action(function (Customer $record) {
                        if ($record->quota <= 0) {
                            Notification::make()
                                ->title('Quota habis!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->decrement('quota');

                        Notification::make()
                            ->title('Cek In berhasil — quota berkurang 1')
                            ->success()
                            ->send();
                    }),

                // WHATSAPP ACTION
                Action::make('whatsapp_verify')
                    ->label('Verifikasi & Kirim WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('green')
                    ->visible(fn (Customer $record) => !$record->is_verified)
                    ->requiresConfirmation()
                    ->action(function (Customer $record) {
                        $password = Str::random(8);

                        $record->update([
                            'password' => bcrypt($password),
                            'is_verified' => true,
                        ]);

                        $pesan = urlencode(
                            "Assalamu'alaikum, {$record->name}.\n\n" .
                            "Akun Anda telah diaktifkan.\n\n" .
                            "Email: {$record->email}\n" .
                            "Password: {$password}\n\n" .
                            "Login: " . url('/login')
                        );

                        return redirect()->away(
                            'https://wa.me/' .
                                preg_replace('/[^0-9]/', '', $record->phone_number) .
                                '?text=' . $pesan
                        );
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])

            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return true; }
    public static function canCreate(): bool { return true; }
    public static function canEdit(Model $record): bool { return true; }
    public static function canDelete(Model $record): bool { return true; }
}
