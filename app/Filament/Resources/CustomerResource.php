<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\Package;
use Filament\Forms;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Customers';
    protected static ?string $pluralLabel = 'Customers';

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Pribadi')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\DatePicker::make('birth_date')
                        ->label('Tanggal Lahir')
                        ->displayFormat('d/m/Y'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Package & Membership')
                ->schema([
                    // Package Selection
                    Forms\Components\Select::make('package_id')
                        ->label('Package')
                        ->relationship('package', 'name')
                        ->searchable()
                        ->placeholder('Pilih Package')
                        ->helperText('Package yang dibeli oleh customer')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            // Optional: Auto-fill quota dari package
                            if ($state) {
                                // Use cached lookup to avoid repeated DB calls while
                                // admin is interacting with the form.
                                $package = \Illuminate\Support\Facades\Cache::remember("filament.package.{$state}", 300, fn() => Package::find($state));
                                if ($package && $package->quota) {
                                    $set('quota', $package->quota);
                                }
                            }
                        }),

                    Forms\Components\TextInput::make('quota')
                        ->label('Quota')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Sisa quota customer'),

                    Forms\Components\Select::make('membership')
                        ->label('Membership Status')
                        ->options([
                            'Basic' => 'Basic',
                            'Premium' => 'Premium',
                            'Trial' => 'Trial',
                        ])
                        ->helperText('Status membership saat ini'),
                ])
                ->columns(3)
                ->collapsible(),

            Forms\Components\Section::make('Detail Lainnya')
                ->schema([
                    Forms\Components\Textarea::make('goals')
                        ->label('Tujuan Anda')
                        ->rows(3),

                    Forms\Components\Textarea::make('kondisi_khusus')
                        ->label('Kondisi Khusus')
                        ->rows(3),

                    Forms\Components\Textarea::make('referensi')
                        ->label('Mengenal FTM dari')
                        ->rows(3),

                    Forms\Components\Textarea::make('pengalaman')
                        ->label('Pengalaman')
                        ->rows(3),

                    Forms\Components\Select::make('is_muslim')
                        ->label('Muslim')
                        ->options([
                            'ya' => 'Ya',
                            'tidak' => 'Tidak',
                        ]),

                    Forms\Components\TextInput::make('voucher_code')
                        ->label('Voucher Code')
                        ->maxLength(50),
                ])
                ->columns(2)
                ->collapsible(),

            Forms\Components\Section::make('Status Akun')
                ->schema([
                    Forms\Components\Toggle::make('is_verified')
                        ->label('Sudah Bisa Login')
                        ->default(false),
                ])
                ->collapsible(),

            Forms\Components\Section::make('Manage Remaining Quota')
                ->schema([
                    // Show current active order info
                    Forms\Components\Placeholder::make('active_order_info')
                        ->label('Order Aktif Saat Ini')
                        ->content(function ($record) {
                            if (!$record) return 'Simpan customer terlebih dahulu.';
                            $activeOrder = static::findActiveOrder($record);
                            if (!$activeOrder) return 'Tidak ada order aktif.';
                            return "Order: {$activeOrder->order_code} | Package: " . ($activeOrder->package->name ?? '-') . " | Classes Remaining: {$activeOrder->remaining_classes} | Quota Remaining: {$activeOrder->remaining_quota}";
                        })
                        ->visibleOn('edit'),

                    // Edit field for customer quota (legacy field)
                    Forms\Components\TextInput::make('quota')
                        ->label('Customer Quota (Legacy Field)')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Field lama - akan disinkronkan dengan orders.remaining_quota saat disimpan. Gunakan field di bawah untuk update.')
                        ->disabled()
                        ->visibleOn('edit'),

                    // Edit field for remaining quota on active order
                    Forms\Components\TextInput::make('remaining_quota_to_update')
                        ->label('Remaining Quota (Active Order)')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Ubah nilai ini untuk update quota pada order aktif. Akan disinkronkan ke customers.quota juga.')
                        ->default(function ($record) {
                            if (!$record) return 0;
                            $activeOrder = static::findActiveOrder($record);
                            return $activeOrder ? $activeOrder->remaining_quota : 0;
                        })
                        ->dehydrated(false)
                        ->visibleOn('edit'),
                ])
                ->columns(1)
                ->collapsible()
                ->visibleOn('edit'),

            Forms\Components\Section::make('Classes Remaining (Active Order)')
                ->schema([
                    Forms\Components\Placeholder::make('adjust_hint')
                        ->label('')
                        ->content('Untuk mengubah Classes Remaining, gunakan tab "Orders & Classes Remaining" di bawah form ini, atau gunakan tombol "Adjust Classes" di tabel customer.')
                        ->visibleOn('edit'),
                ])
                ->collapsible()
                ->visibleOn('edit'),
        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->url(function ($record) {
                        if (!$record->phone_number) return null;
                        return 'https://wa.me/62' . ltrim($record->phone_number, '0');
                    })
                    ->openUrlInNewTab()
                    ->color('success')
                    ->icon('heroicon-o-phone')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->url(fn($record) => "mailto:{$record->email}")
                    ->color('primary')
                    ->copyable()
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir / Umur')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $date = Carbon::parse($state);
                        return $date->format('d/m/Y') . ' (' . $date->age . ' th)';
                    })
                    ->sortable()
                    ->toggleable(),

                // PACKAGE COLUMN - PENTING!
                Tables\Columns\BadgeColumn::make('package.name')
                    ->label('Package')
                    ->colors([
                        'success' => fn ($state) => $state !== null,
                        'secondary' => fn ($state) => $state === null,
                    ])
                    ->default('-')
                    ->sortable()
                    ->searchable()
                    ->tooltip(function ($record) {
                        if (!$record->package) return 'Belum beli package';
                        return "Package: {$record->package->name}";
                    }),

                Tables\Columns\BadgeColumn::make('quota')
                    ->label('Quota')
                    ->color(function ($record) {
                        if ($record->quota <= 0) return 'danger';
                        if ($record->quota < 5) return 'warning';
                        return 'success';
                    })
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('active_remaining_classes')
                    ->label('Classes Remaining')
                    ->getStateUsing(function ($record) {
                        $activeOrder = static::findActiveOrder($record);
                        return $activeOrder ? $activeOrder->remaining_classes : '-';
                    })
                    ->colors([
                        'success' => fn ($state) => $state !== '-' && $state > 3,
                        'warning' => fn ($state) => $state !== '-' && $state > 0 && $state <= 3,
                        'danger' => fn ($state) => $state === '-' || $state <= 0,
                    ]),

               

                Tables\Columns\TextColumn::make('goals')
                    ->label('Tujuan Anda')
                    ->limit(20)
                    ->toggleable()
                    ->tooltip(function ($record) {
                        return $record->goals;
                    }),

                Tables\Columns\TextColumn::make('kondisi_khusus')
                    ->label('Kondisi Khusus')
                    ->limit(20)
                    ->toggleable()
                    ->tooltip(function ($record) {
                        return $record->kondisi_khusus;
                    }),

                Tables\Columns\TextColumn::make('referensi')
                    ->label('Mengenal FTM dari')
                    ->limit(20)
                    ->toggleable()
                    ->tooltip(function ($record) {
                        return $record->referensi;
                    }),

                Tables\Columns\TextColumn::make('pengalaman')
                    ->label('Pengalaman')
                    ->limit(20)
                    ->toggleable()
                    ->tooltip(function ($record) {
                        return $record->pengalaman;
                    }),

                Tables\Columns\BadgeColumn::make('is_muslim')
                    ->label('Muslim')
                    ->colors([
                        'success' => 'ya',
                        'danger' => 'tidak',
                    ])
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Login Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('membership')
                    ->options([
                        'Basic' => 'Basic',
                        'Premium' => 'Premium',
                        'Trial' => 'Trial',
                    ]),

                Tables\Filters\SelectFilter::make('is_muslim')
                    ->options([
                        'ya' => 'Ya',
                        'tidak' => 'Tidak',
                    ]),

                // FILTER BY PACKAGE - PENTING!
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('quota_habis')
                    ->label('Quota Habis')
                    ->query(fn(Builder $query): Builder => $query->where('quota', '<=', 0)),

                Tables\Filters\Filter::make('belum_verified')
                    ->label('Belum Verified')
                    ->query(fn(Builder $query): Builder => $query->where('is_verified', false)),

                // FILTER: Customer yang sudah beli package
                Tables\Filters\Filter::make('sudah_beli_package')
                    ->label('Sudah Beli Package')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('package_id')),

                // FILTER: Customer yang belum beli package
                Tables\Filters\Filter::make('belum_beli_package')
                    ->label('Belum Beli Package')
                    ->query(fn(Builder $query): Builder => $query->whereNull('package_id')),
            ])

            ->actions([

                /* ADJUST CLASSES REMAINING */
                Tables\Actions\Action::make('adjust_classes')
                    ->label('Adjust Classes')
                    ->icon('heroicon-o-adjustments')
                    ->color('warning')
                    ->visible(fn ($record) => (bool) static::findActiveOrder($record))
                    ->form(function ($record) {
                        $activeOrder = static::findActiveOrder($record);

                        return [
                            Forms\Components\Placeholder::make('info')
                                ->label('Order Aktif')
                                ->content($activeOrder
                                    ? "Order: {$activeOrder->order_code} | Package: " . ($activeOrder->package->name ?? '-')
                                    : 'Tidak ada order aktif'),
                            Forms\Components\TextInput::make('remaining_classes')
                                ->label('Classes Remaining (Booking)')
                                ->numeric()
                                ->required()
                                ->default($activeOrder->remaining_classes ?? 0)
                                ->minValue(0)
                                ->helperText('Sisa kelas yang bisa di-booking. Tambahkan jika member gagal book/cancel.'),
                            Forms\Components\TextInput::make('remaining_quota')
                                ->label('Remaining Quota (Check-in)')
                                ->numeric()
                                ->required()
                                ->default($activeOrder->remaining_quota ?? 0)
                                ->minValue(0)
                                ->helperText('Sisa kuota check-in member.'),
                            Forms\Components\Textarea::make('reason')
                                ->label('Alasan Perubahan')
                                ->placeholder('Contoh: Member gagal booking, cancel class, kompensasi, dll.')
                                ->required(),
                        ];
                    })
                    ->action(function ($record, array $data) {
                        $activeOrder = static::findActiveOrder($record);

                        if ($activeOrder) {
                            $activeOrder->update([
                                'remaining_classes' => $data['remaining_classes'],
                                'remaining_quota' => $data['remaining_quota'],
                            ]);

                            Notification::make()
                                ->title("Classes & Quota diperbarui untuk {$record->name}")
                                ->body("Classes: {$data['remaining_classes']}, Quota: {$data['remaining_quota']}. Alasan: {$data['reason']}")
                                ->success()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Adjust Classes & Quota')
                    ->modalSubheading(fn ($record) => "Ubah jumlah classes remaining dan quota untuk {$record->name}"),

                /* CHECK-IN */
                Tables\Actions\Action::make('checkin')
                    ->label('Cek In')
                    ->icon('heroicon-o-check')
                    ->color('primary')
                    ->visible(fn($record) => $record->quota > 0)
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Check-in')
                    ->modalSubheading(fn($record) => "Check-in untuk {$record->name}?")
                    ->action(function (Customer $record) {
                        $record->decrement('quota');
                        
                        Notification::make()
                            ->title('Cek In Berhasil!')
                            ->success()
                            ->send();
                    }),

                /* KIRIM LOGIN WA */
                Tables\Actions\Action::make('send_login')
                    ->label('Verifikasi & Kirim Login')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn($record) => !$record->is_verified)
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi & Kirim Login')
                    ->modalSubheading(fn($record) => "Kirim kredensial login ke {$record->name}?")
                    ->action(function (Customer $record) {

                        /* generate password acak */
                        $plainPassword = Str::random(8);

                        /* simpan hash */
                        $record->update([
                            'password' => bcrypt($plainPassword),
                            'is_verified' => 1,
                        ]);

                        if (!$record->phone_number) {
                            Notification::make()
                                ->title('Nomor telepon tidak tersedia')
                                ->warning()
                                ->send();
                            return;
                        }

                        $phone = '62' . ltrim($record->phone_number, '0');

                        $message = urlencode(
                            "Assalamu'alaikum {$record->name} 😊

Akun FTM Society Anda sudah aktif!

Login Member:
Email: {$record->email}
Password: {$plainPassword}

Silakan login dan ganti password Anda.

Selamat berlatih 💪"
                        );

                        Notification::make()
                            ->title('Akun berhasil diverifikasi')
                            ->success()
                            ->send();

                        // Redirect ke WhatsApp
                        return redirect()->away("https://wa.me/{$phone}?text={$message}");
                    }),

                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus Customer')
                    ->modalSubheading(fn($record) => "Anda yakin ingin menghapus data {$record->name}? Tindakan ini tidak dapat dibatalkan.")
                    ->successNotificationTitle('Data berhasil dihapus.'),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus Customer Terpilih')
                    ->modalSubheading('Semua data customer yang dipilih akan dihapus permanen.')
                    ->successNotificationTitle('Data berhasil dihapus.'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Eager load package and the most recent order relation to avoid N+1
        // when rendering customer lists and placeholders. We intentionally
        // do not load every order's heavy relations here to avoid large
        // memory usage.
        return parent::getEloquentQuery()
            ->with(['package', 'orders' => function ($q) {
                $q->latest()->limit(3);
            }])
            ->latest();
    }

    /**
     * Helper: find the active order for a given customer record.
     * Will use already-loaded relations when available to avoid extra queries.
     */
    protected static function findActiveOrder($record)
    {
        $statusList = ['active', 'completed', 'paid'];

        // If orders relation is already loaded, search in-memory first.
        if ($record && $record->relationLoaded('orders')) {
            $orders = collect($record->orders)
                ->filter(function ($o) use ($statusList) {
                    $okStatus = in_array($o->status, $statusList, true);
                    $notExpired = is_null($o->expired_at) || $o->expired_at > now();
                    return $okStatus && $notExpired;
                })
                ->sortByDesc('created_at');

            return $orders->first() ?? null;
        }

        // Fallback: perform a constrained query for the active order.
        return $record->orders()
            ->whereIn('status', $statusList)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->first();
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone_number'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Email' => $record->email,
            'Phone' => $record->phone_number,
            'Membership' => $record->membership,
            'Package' => $record->package?->name ?? 'Belum beli package',
        ];
    }

    /**
     * ✅ SYNC QUOTA: Síncronizar customers.quota ↔ orders.remaining_quota
     * Ketika admin mengubah remaining_quota_to_update, kedua field akan update bersamaan
     */
    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Hanya proses jika ada nilai yang diubah
        if (!isset($data['remaining_quota_to_update'])) {
            return $data;
        }

        $newQuota = (int) $data['remaining_quota_to_update'];

        // ✅ UPDATE 1: Update customers.quota (legacy field)
        $data['quota'] = $newQuota;

        // ✅ UPDATE 2: Update orders.remaining_quota (active order)
        // Dilakukan di EditCustomer page untuk akses ke $record
        // (akan dihandle di hook page-level)

        return $data;
    }
}