<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Route;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    /**
     * Page title yang ditampilkan
     */
    protected function getTitle(): string
    {
        return 'Customers Data';
    }

    /**
     * Header actions (tombol-tombol di kanan atas)
     */
    protected function getActions(): array
    {
        return array_filter([
            $this->getFeedbackAction(),
            $this->getSchedulesAction(),
            $this->getBookingsAction(),
            $this->getCreateAction(),
        ]);
    }

    /**
     * Tombol Feedback
     */
    protected function getFeedbackAction(): ?Actions\Action
    {
        if (!Route::has('feedback.index')) {
            return null;
        }

        return Actions\Action::make('feedback')
            ->label('Feedback')
            ->icon('heroicon-o-chat-alt-2')
            ->color('success')
            ->url(fn() => route('feedback.index'))
            ->openUrlInNewTab()
            ->tooltip('Lihat semua feedback dari member');
    }

    /**
     * Tombol Schedules
     */
    protected function getSchedulesAction(): ?Actions\Action
    {
        if (!Route::has('schedules.index')) {
            return null;
        }

        return Actions\Action::make('schedules')
            ->label('Schedules')
            ->icon('heroicon-o-clock')
            ->color('secondary')
            ->url(fn() => route('schedules.index'))
            ->openUrlInNewTab()
            ->tooltip('Kelola jadwal kelas');
    }

    /**
     * Tombol Bookings
     */
    protected function getBookingsAction(): ?Actions\Action
    {
        if (!Route::has('bookings.index')) {
            return null;
        }

        return Actions\Action::make('bookings')
            ->label('Daftar Booking')
            ->icon('heroicon-o-calendar')
            ->color('primary')
            ->url(fn() => route('bookings.index'))
            ->openUrlInNewTab()
            ->tooltip('Lihat semua booking customer');
    }

    /**
     * Tombol Create Customer
     */
    protected function getCreateAction(): Actions\CreateAction
    {
        return Actions\CreateAction::make()
            ->label('Add Customer')
            ->icon('heroicon-o-plus')
            ->tooltip('Tambah customer baru');
    }

    /**
     * Widgets yang ditampilkan di header (opsional)
     * Uncomment jika ingin menambahkan widgets
     */
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         CustomerResource\Widgets\CustomerStatsOverview::class,
    //     ];
    // }

    /**
     * Widgets yang ditampilkan di footer (opsional)
     */
    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         CustomerResource\Widgets\CustomerChart::class,
    //     ];
    // }

    /**
     * Heading untuk halaman
     */
    // protected function getHeading(): string
    // {
    //     return 'Manage Customers';
    // }

    /**
     * Subheading untuk halaman
     */
    // protected function getSubheading(): ?string
    // {
    //     return 'Kelola data member FTM Society';
    // }
}