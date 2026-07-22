<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleLabelMappingResource\Pages;
use App\Models\ScheduleLabelMapping;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ScheduleLabelMappingResource extends Resource
{
    protected static ?string $model = ScheduleLabelMapping::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Schedule Labels';

    protected static ?string $pluralLabel = 'Schedule Labels';

    protected static ?string $slug = 'schedule-labels';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->helperText('Nama yang tampil di dropdown Schedule, poster, dan checkout member. Contoh: Mix Class (5)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('class_group_id')
                    ->label('Class Group')
                    ->relationship('classGroup', 'name')
                    ->helperText('Pilih group yang sesuai. Bisa dikosongkan jika belum ada group.')
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('classGroup.name')
                    ->label('Class Group')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->toggleable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduleLabelMappings::route('/'),
            'create' => Pages\CreateScheduleLabelMapping::route('/create'),
            'edit' => Pages\EditScheduleLabelMapping::route('/{record}/edit'),
        ];
    }
}
