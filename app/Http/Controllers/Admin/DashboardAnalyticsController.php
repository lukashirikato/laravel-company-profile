<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;

class DashboardAnalyticsController extends Controller
{
    /**
     * Snapshot lengkap analytics dashboard (KPI + grafik + aktivitas +
     * notifikasi + insight) — di-poll oleh frontend tiap 30 detik.
     */
    public function analytics(Request $request)
    {
        $range = $request->query('range', '30d');
        $from  = $request->query('from');
        $to    = $request->query('to');

        return response()->json([
            'success' => true,
            'generated_at' => now()->toIso8601String(),
            'overview' => DashboardAnalyticsService::overview($range, $from, $to),
            'charts'   => DashboardAnalyticsService::charts($range, $from, $to),
            'activity' => DashboardAnalyticsService::activity(10),
            'recentMembers' => DashboardAnalyticsService::recentCustomers(5),
        ]);
    }

    /**
     * Pencarian real-time: member, invoice, booking, kelas.
     */
    public function search(Request $request)
    {
        $q = $request->query('q', '');

        return response()->json([
            'success' => true,
            'query'   => $q,
            'results' => DashboardAnalyticsService::search($q),
        ]);
    }
}
