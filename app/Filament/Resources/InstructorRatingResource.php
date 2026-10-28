<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorRatingResource\Pages;
use App\Models\InstructorRating;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InstructorRatingResource extends Resource
{
    protected static ?string $model = InstructorRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Penilaian Pelatih';

    protected static ?string $pluralLabel = 'Penilaian Pelatih';

    protected static ?string $slug = 'penilaian-pelatih';

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Member')
                    ->relationship('customer', 'name')
                    ->disabled()
                    ->searchable(),
                Forms\Components\TextInput::make('instructor_name')
                    ->label('Pelatih')
                    ->disabled(),
                Forms\Components\TextInput::make('class_name')
                    ->label('Kelas')
                    ->disabled(),
                Forms\Components\Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 - Kurang',
                        2 => '2 - Cukup',
                        3 => '3 - Baik',
                        4 => '4 - Sangat baik',
                        5 => '5 - Luar biasa',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('Komentar')
                    ->rows(4)
                    ->maxLength(1000),
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
                Tables\Columns\TextColumn::make('instructor_name')
                    ->label('Pelatih')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('class_name')
                    ->label('Kelas')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn (InstructorRating $record) => $record->class_name),
                Tables\Columns\TextColumn::make('schedule.schedule_date')
                    ->label('Tanggal Kelas')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state) . str_repeat('☆', max(0, 5 - (int) $state)))
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(50)
                    ->tooltip(fn (InstructorRating $record) => $record->comment)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 bintang',
                        2 => '2 bintang',
                        3 => '3 bintang',
                        4 => '4 bintang',
                        5 => '5 bintang',
                    ]),
                Tables\Filters\SelectFilter::make('instructor_name')
                    ->label('Pelatih')
                    ->options(fn () => InstructorRating::query()
                        ->whereNotNull('instructor_name')
                        ->distinct()
                        ->orderBy('instructor_name')
                        ->pluck('instructor_name', 'instructor_name')
                        ->toArray()),
                Tables\Filters\Filter::make('created_at')
                    ->label('Periode')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detail Penilaian Pelatih')
                    ->form([
                        Forms\Components\Placeholder::make('member_name')
                            ->label('Member')
                            ->content(fn (?InstructorRating $record) => $record?->customer?->name ?? '-'),
                        Forms\Components\TextInput::make('instructor_name')->label('Pelatih')->disabled(),
                        Forms\Components\TextInput::make('class_name')->label('Kelas')->disabled(),
                        Forms\Components\TextInput::make('rating')->label('Rating')
                            ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state) . str_repeat('☆', max(0, 5 - (int) $state)))
                            ->disabled(),
                        Forms\Components\Textarea::make('comment')->label('Komentar')->rows(4)->disabled(),
                        Forms\Components\Placeholder::make('created_at')->label('Dikirim')
                            ->content(fn (?InstructorRating $record) => $record?->created_at?->format('d M Y H:i') ?? '-'),
                    ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records) {
                        $csv = "Member,Pelatih,Kelas,Tanggal Kelas,Rating,Komentar,Dikirim\n";
                        foreach ($records as $record) {
                            $csv .= implode(',', [
                                '"' . str_replace('"', '""', $record->customer?->name ?? '') . '"',
                                '"' . str_replace('"', '""', $record->instructor_name ?? '') . '"',
                                '"' . str_replace('"', '""', $record->class_name ?? '') . '"',
                                '"' . ($record->schedule?->schedule_date?->format('d/m/Y') ?? '') . '"',
                                (int) $record->rating,
                                '"' . str_replace('"', '""', $record->comment ?? '') . '"',
                                $record->created_at?->format('d/m/Y H:i') ?? '',
                            ]) . "\n";
                        }
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'penilaian-pelatih-' . now()->format('Ymd-His') . '.csv', [
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
            'index' => Pages\ListInstructorRatings::route('/'),
            'edit' => Pages\EditInstructorRating::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }
}