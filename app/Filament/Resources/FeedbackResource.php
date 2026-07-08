<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Feedback';

    protected static ?string $pluralLabel = 'Feedback';

    protected static ?string $slug = 'feedback';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('subject')
                    ->label('Subjek')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->rows(5)
                    ->disabled(),
                Forms\Components\Placeholder::make('received_at')
                    ->label('Diterima')
                    ->content(fn (?Feedback $record) => $record?->created_at?->format('d M Y H:i') ?? '-'),
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
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-mail'),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')
                    ->limit(60)
                    ->tooltip(fn (Feedback $record) => $record->message),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
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
                Tables\Filters\Filter::make('has_email')
                    ->label('Punya Email')
                    ->query(fn (Builder $q) => $q->whereNotNull('email')),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detail Feedback')
                    ->form([
                        Forms\Components\TextInput::make('name')->label('Nama')->disabled(),
                        Forms\Components\TextInput::make('email')->label('Email')->disabled(),
                        Forms\Components\TextInput::make('subject')->label('Subjek'),
                        Forms\Components\Textarea::make('message')->label('Pesan')->rows(5)->disabled(),
                        Forms\Components\Placeholder::make('created_at')->label('Diterima')
                            ->content(fn (?Feedback $record) => $record?->created_at?->format('d M Y H:i') ?? '-'),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records) {
                        $csv = "Nama,Email,Subjek,Pesan,Tanggal\n";
                        foreach ($records as $record) {
                            $csv .= implode(',', [
                                '"' . str_replace('"', '""', $record->name) . '"',
                                '"' . ($record->email ?? '') . '"',
                                '"' . str_replace('"', '""', $record->subject) . '"',
                                '"' . str_replace('"', '""', $record->message) . '"',
                                $record->created_at->format('d/m/Y H:i'),
                            ]) . "\n";
                        }
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'feedback-export-' . now()->format('Ymd-His') . '.csv', [
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
            'index' => Pages\ListFeedbacks::route('/'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }
}
