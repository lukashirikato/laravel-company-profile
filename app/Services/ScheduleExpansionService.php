<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ScheduleExpansionService
{
    /**
     * Parse string hari menjadi array hari ter-normalisasi.
     *
     * Contoh:
     * - "Wednesday"
     * - "Wednesday & Friday"
     * - "Wednesday, Friday"
     * - "Wednesday / Friday"
     */
    public function parseMultipleDays(string $dayString): array
    {
        $rawDays = preg_split('/[,&\/\n]+|\band\b/i', $dayString) ?: [];

        $normalized = [];
        $map = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        foreach ($rawDays as $day) {
            $cleanDay = strtolower(trim($day));

            if ($cleanDay === '') {
                continue;
            }

            if (isset($map[$cleanDay])) {
                $normalized[] = $map[$cleanDay];
                continue;
            }

            $normalized[] = ucfirst($cleanDay);
        }

        return array_values(array_unique($normalized));
    }

    /**
     * Generate semua tanggal yang cocok dari start date sampai akhir bulan.
     */
    public function expandToMonth(string $dayString, ?Carbon $startDate = null): Collection
    {
        $startDate = $startDate ? $startDate->copy() : Carbon::now();
        $startDate = $startDate->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();
        $days = $this->parseMultipleDays($dayString);

        if (empty($days)) {
            return collect();
        }

        $period = CarbonPeriod::create($startDate, '1 day', $endDate);

        return collect($period)
            ->filter(function (Carbon $date) use ($days) {
                return in_array($date->format('l'), $days, true);
            })
            ->values();
    }

    /**
     * Hitung jumlah schedule yang akan dibuat.
     */
    public function countExpandedSchedules(string $dayString, ?Carbon $startDate = null): int
    {
        return $this->expandToMonth($dayString, $startDate)->count();
    }

    /**
     * Format rentang tanggal preview.
     */
    public function getMonthRangeLabel(?Carbon $startDate = null): string
    {
        $startDate = $startDate ? $startDate->copy() : Carbon::now();

        return $startDate->format('d M Y') . ' - ' . $startDate->copy()->endOfMonth()->format('d M Y');
    }

    /**
     * Build preview data untuk form.
     */
    public function buildPreview(string $dayString, ?Carbon $startDate = null): array
    {
        $dates = $this->expandToMonth($dayString, $startDate);

        return [
            'days' => $this->parseMultipleDays($dayString),
            'dates' => $dates->map(fn (Carbon $date) => [
                'date' => $date->format('d/m/Y'),
                'day' => $date->format('l'),
            ])->all(),
            'count' => $dates->count(),
            'range' => $this->getMonthRangeLabel($startDate),
            'first_date' => $dates->first()?->format('Y-m-d'),
        ];
    }
}
