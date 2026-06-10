<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MemberAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();

        $baseQuery = $customer->attendances()->with(['schedule.classModel', 'order.package']);
        $completedQuery = (clone $baseQuery)->whereNotNull('check_out_at');

        $totalAttendances = (clone $baseQuery)->count();
        $monthAttendances = (clone $baseQuery)
            ->whereDate('check_in_at', '>=', now()->startOfMonth())
            ->count();
        $weekAttendances = (clone $baseQuery)
            ->whereDate('check_in_at', '>=', now()->startOfWeek())
            ->count();
        $activeAttendance = (clone $baseQuery)
            ->whereNull('check_out_at')
            ->latest('check_in_at')
            ->first();

        $totalDurationMinutes = (int) (clone $completedQuery)->sum('duration_minutes');
        $averageDurationMinutes = (int) round((clone $completedQuery)->avg('duration_minutes') ?? 0);
        $lastAttendance = (clone $baseQuery)->latest('check_in_at')->first();

        $attendances = (clone $baseQuery)
            ->latest('check_in_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('member.attendance', [
            'customer' => $customer,
            'attendances' => $attendances,
            'totalAttendances' => $totalAttendances,
            'monthAttendances' => $monthAttendances,
            'weekAttendances' => $weekAttendances,
            'activeAttendance' => $activeAttendance,
            'totalDurationLabel' => $this->formatMinutes($totalDurationMinutes),
            'averageDurationLabel' => $averageDurationMinutes > 0 ? $this->formatMinutes($averageDurationMinutes) : '-',
            'lastAttendanceLabel' => $lastAttendance?->check_in_at
                ? Carbon::parse($lastAttendance->check_in_at)->diffForHumans()
                : 'Belum ada aktivitas',
        ]);
    }

    private function formatMinutes(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0 menit';
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "{$hours}j {$mins}m";
        }

        if ($hours > 0) {
            return "{$hours}j";
        }

        return "{$mins}m";
    }
}