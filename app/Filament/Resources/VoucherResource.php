<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use App\Models\Package;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationLabel = 'Vouchers';
    
    protected static ?string $navigationGroup = 'Promotions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ✅ INFO SECTION
                Forms\Components\Section::make('Informasi Voucher')
                    ->description('Kode, nama, dan deskripsi voucher')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->maxLength(50)
                            ->label('Kode Voucher')
                            ->placeholder('Contoh: SEHATSATU'),
                        
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Voucher'),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpan('full')
                            ->label('Deskripsi'),
                    ])
                    ->columns(2),

                // ✅ DISCOUNT SECTION
                Forms\Components\Section::make('Konfigurasi Diskon')
                    ->description('Jenis, nilai, dan batasan diskon')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'percent' => 'Diskon Persentase (%)',
                                'nominal' => 'Diskon Nominal (Rp)',
                            ])
                            ->required()
                            ->default('percent')
                            ->reactive()
                            ->label('Jenis Diskon'),
                        
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->label('Nilai Diskon')
                            ->helperText(fn ($get) => $get('type') === 'percent' 
                                ? 'Masukkan angka tanpa % (contoh: 10)' 
                                : 'Masukkan jumlah Rp (contoh: 50000)'),
                        
                        Forms\Components\TextInput::make('max_discount')
                            ->numeric()
                            ->label('Diskon Maksimal')
                            ->helperText('Batasan diskon maksimal (opsional)'),
                        
                        Forms\Components\TextInput::make('usage_limit')
                            ->numeric()
                            ->label('Batas Penggunaan')
                            ->helperText('Berapa kali voucher boleh digunakan (kosong = tak terbatas)'),
                    ])
                    ->columns(2),

                // ✅ PACKAGE RESTRICTION SECTION
                Forms\Components\Section::make('Pembatasan Paket')
                    ->description('Pilih tipe pembatasan dan paket yang boleh menggunakan voucher')
                    ->schema([
                        Forms\Components\Select::make('applicable_to')
                            ->options([
                                'all' => 'Berlaku untuk Semua Paket',
                                'specific' => 'Berlaku untuk Paket Tertentu Saja',
                            ])
                            ->required()
                            ->default('specific')
                            ->reactive()
                            ->label('Tipe Pembatasan'),

                        
                            Forms\Components\CheckboxList::make('packages')
                            ->relationship('packages', 'name')
                            ->visible(fn ($get) => $get('applicable_to') === 'specific')
                            ->required(fn ($get) => $get('applicable_to') === 'specific')
                            ->label('Pilih Paket yang Boleh Menggunakan Voucher')
                            ->helperText('Pilih 1 atau lebih paket. Voucher hanya bisa digunakan untuk paket-paket ini saja.')
                            ->columnSpan('full'),
                    ]),

                // ✅ VALIDITY PERIOD SECTION
                Forms\Components\Section::make('Periode Berlaku')
                    ->description('Tanggal mulai dan berakhir voucher')
                    ->schema([
                        Forms\Components\DateTimePicker::make('valid_from')
                            ->required()
                            ->default(now())
                            ->label('Mulai Berlaku'),
                        
                        Forms\Components\DateTimePicker::make('valid_until')
                            ->required()
                            ->label('Berakhir')
                            ->after('valid_from'),
                    ])
                    ->columns(2),

                // ✅ STATUS SECTION
                Forms\Components\Section::make('Status')
                    ->description('Aktifkan atau nonaktifkan voucher')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(),
                        
                        Forms\Components\TextInput::make('used_count')
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->label('Sudah Digunakan')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label('Kode'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama')
                    ->limit(30),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'percent',
                        'success' => 'nominal',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'percent' ? 'Persentase' : 'Nominal')
                    ->label('Jenis'),
                
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(function ($record) {
                        if ($record->type === 'percent') {
                            return $record->value . '%';
                        }
                        return 'Rp ' . number_format($record->value, 0, ',', '.');
                    }),
                
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Jumlah Paket')
                    ->counts('packages'),
                
                Tables\Columns\TextColumn::make('usage_stats')
                    ->label('Pemakaian')
                    ->formatStateUsing(function ($record) {
                        $max = $record->usage_limit ?? '∞';
                        return "{$record->used_count} / {$max}";
                    }),
                
                Tables\Columns\IconColumn::make('active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('valid_until')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Berlaku Sampai'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Dibuat'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Status')
                    ->placeholder('Semua voucher')
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Nonaktif saja'),
                
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'percent' => 'Persentase',
                        'nominal' => 'Nominal',
                    ])
                    ->label('Jenis Diskon'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }    
}