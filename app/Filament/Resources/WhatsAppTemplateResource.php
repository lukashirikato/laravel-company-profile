<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppTemplateResource\Pages;
use App\Models\WhatsAppTemplate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class WhatsAppTemplateResource extends Resource
{
    protected static ?string $model = WhatsAppTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'WA Templates';

    protected static ?string $pluralLabel = 'WA Templates';

    protected static ?string $slug = 'wa-templates';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Template')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Identifier unik template (tidak bisa diubah)'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2)
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('message')
                    ->label('Pesan WhatsApp')
                    ->rows(16)
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Gunakan {placeholder} untuk variabel dinamis. Lihat daftar placeholder yang tersedia di bawah.'),
                Forms\Components\Placeholder::make('placeholders_preview')
                    ->label('Placeholders Tersedia')
                    ->hiddenOn('create')
                    ->content(function ($record) {
                        $placeholders = $record?->placeholders ?? [];
                        return view('filament.components.placeholders-table', [
                            'placeholders' => $placeholders,
                        ]);
                    })
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->color('secondary')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->toggleable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Aktif')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label('Hanya Aktif')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->defaultSort('key')
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWhatsAppTemplates::route('/'),
            'edit' => Pages\EditWhatsAppTemplate::route('/{record}/edit'),
        ];
    }
}
