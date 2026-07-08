<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFollowUpResource\Pages;
use App\Models\CustomerFollowUp;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use App\Helpers\MessageTemplate;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerFollowUpResource extends Resource
{
    protected static ?string $model = CustomerFollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Follow Up';

    protected static ?string $pluralLabel = 'Follow Up';

    protected static ?string $slug = 'follow-up';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required()
                    ->label('Member'),
                Forms\Components\Select::make('follow_up_type')
                    ->label('Tipe Follow Up')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'call' => 'Telepon',
                        'email' => 'Email',
                        'visit' => 'Kunjungan',
                    ])
                    ->required(),
                Forms\Components\Select::make('template_used')
                    ->label('Template')
                    ->options([
                        'default' => 'Default',
                        'promotion' => 'Promotion',
                        'newclass' => 'New Class',
                        'checkup' => 'Check Up',
                    ])
                    ->default('default'),
                Forms\Components\Textarea::make('message_sent')
                    ->label('Pesan')
                    ->rows(3)
                    ->nullable(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->nullable(),
                Forms\Components\Select::make('result')
                    ->label('Hasil')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Sukses',
                        'no_response' => 'Tidak Direspon',
                        'reopened' => 'Re-opened',
                    ])
                    ->default('pending'),
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
                    ->weight('bold')
                    ->url(fn (CustomerFollowUp $record) => $record->customer ? route('filament.resources.customers.edit', $record->customer) : null),
                Tables\Columns\TextColumn::make('customer.phone_number')
                    ->label('Telepon')
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                Tables\Columns\BadgeColumn::make('follow_up_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'whatsapp',
                        'primary' => 'call',
                        'warning' => 'email',
                        'info' => 'visit',
                    ]),
                Tables\Columns\BadgeColumn::make('result')
                    ->label('Hasil')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success',
                        'danger' => 'no_response',
                        'primary' => 'reopened',
                    ]),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Follow Up Oleh')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('follow_up_type')
                    ->label('Tipe')
                    ->options([
                        'whatsapp' => 'WhatsApp',
                        'call' => 'Telepon',
                        'email' => 'Email',
                        'visit' => 'Kunjungan',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('result')
                    ->label('Hasil')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Sukses',
                        'no_response' => 'Tidak Direspon',
                        'reopened' => 'Re-opened',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Member')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->multiple(),
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
                Action::make('send_whatsapp')
                    ->label('Kirim WhatsApp')
                    ->icon('heroicon-o-chat')
                    ->color('success')
                    ->visible(fn (CustomerFollowUp $record) => $record->customer && $record->customer->phone_number)
                    ->modalHeading('Kirim WhatsApp ke Member')
                    ->modalWidth('lg')
                    ->form(function (CustomerFollowUp $record) {
                        $customer = $record->customer;
                        return [
                            Placeholder::make('customer_info')
                                ->label('Member')
                                ->content("{$customer->name} — {$customer->phone_number}"),
                            Select::make('template_key')
                                ->label('Template Pesan')
                                ->options(MessageTemplate::getOptions())
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set, $state) =>
                                    $set('message', MessageTemplate::render($state, $customer))
                                )
                                ->default('followup_reengagement'),
                            Textarea::make('message')
                                ->label('Pesan')
                                ->required()
                                ->rows(6)
                                ->helperText('Anda bisa edit pesan sebelum dikirim.'),
                        ];
                    })
                    ->action(function (CustomerFollowUp $record, array $data) {
                        $customer = $record->customer;
                        $message = trim($data['message'] ?? '');

                        if (!$message) {
                            Notification::make()->title('Pesan tidak boleh kosong')->warning()->send();
                            return;
                        }

                        $templateKey = $data['template_key'] ?? 'custom';

                        // Update FollowUp record
                        $record->update([
                            'follow_up_type' => 'whatsapp',
                            'template_used' => $templateKey,
                            'message_sent' => $message,
                            'result' => 'success',
                            'notes' => ($record->notes ? $record->notes . "\n\n" : '') . "WA dikirim: " . now()->format('d/m/Y H:i'),
                        ]);

                        // Build wa.me link
                        $phone = '62' . ltrim($customer->phone_number, '0');
                        $encoded = urlencode($message);
                        $waUrl = "https://wa.me/{$phone}?text={$encoded}";

                        // Try Fonnte API first
                        try {
                            $service = app(WhatsAppService::class);
                            $result = $service->send($customer->phone_number, $message);
                            if ($result['success'] ?? false) {
                                Log::info("[FollowUp] WA terkirim via API ke {$customer->name}");
                                Notification::make()
                                    ->title('WhatsApp berhasil dikirim!')
                                    ->success()
                                    ->send();
                                return;
                            }
                        } catch (\Throwable $e) {
                            Log::warning("[FollowUp] API gagal, fallback wa.me: " . $e->getMessage());
                        }

                        // Fallback: redirect to wa.me
                        Notification::make()
                            ->title('Klik link WhatsApp yang terbuka untuk mengirim')
                            ->info()
                            ->send();

                        return redirect()->away($waUrl);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-download')
                    ->action(function (Collection $records) {
                        $csv = "Member,Telepon,Tipe,Hasil,Catatan,Tanggal\n";
                        foreach ($records as $record) {
                            $csv .= implode(',', [
                                '"' . ($record->customer->name ?? '') . '"',
                                '"' . ($record->customer->phone_number ?? '') . '"',
                                $record->follow_up_type,
                                $record->result,
                                '"' . str_replace('"', '""', $record->notes ?? '') . '"',
                                $record->created_at->format('d/m/Y H:i'),
                            ]) . "\n";
                        }
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv;
                        }, 'followup-export-' . now()->format('Ymd-His') . '.csv', [
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
            'index' => Pages\ListCustomerFollowUps::route('/'),
            'create' => Pages\CreateCustomerFollowUp::route('/create'),
            'edit' => Pages\EditCustomerFollowUp::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'admin']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('result', 'pending')->count() ?: null;
    }
}
