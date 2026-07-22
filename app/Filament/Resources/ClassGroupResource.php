<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassGroupResource\Pages;
use App\Models\ClassGroup;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClassGroupResource extends Resource
{
    protected static ?string $model = ClassGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Class Groups';

    protected static ?string $pluralLabel = 'Class Groups';

    protected static ?string $slug = 'class-groups';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Group')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level')
                    ->label('Level')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Group')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classes_count')
                    ->label('Jumlah Class')
                    ->sortable()
                    ->counts('classes'),

                Tables\Columns\TextColumn::make('schedules_count')
                    ->label('Jumlah Schedule')
                    ->sortable()
                    ->counts('schedules'),
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
            'index' => Pages\ListClassGroups::route('/'),
            'create' => Pages\CreateClassGroup::route('/create'),
            'edit' => Pages\EditClassGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('class_groups.*')
            ->withCount([
                'classes',
                'schedules',
            ]);
    }
}
