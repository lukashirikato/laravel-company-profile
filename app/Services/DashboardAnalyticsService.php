<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Customer;
use App\Models\CustomerSchedule;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * DashboardAnalyticsService
 *
 * Menyediakan seluruh metrik analytics untuk Dashboard Admin FTM Society.
 * Semua data berasal langsung dari database (tanpa dummy / hardcode) dan
 * di-cache sangat singkat (30 detik) agar sinkron dengan polling real-time.
 */
class DashboardAnalyticsService
{
    /** Status order yang dianggap "aktif / terbayar". */
    public const ACTIVE_ORDER_STATUSES = ['paid', 'active', 'settlement', 'success', 'capture'];

    /** Status transaksi yang dianggap pembayaran berhasil. */
    public const SUCCESS_TX_STATUSES = ['paid', 'success', 'settlement', 'capture'];

    /** Status booking pada tabel customer_schedules. */
    public const BOOKING_STATUSES = ['confirmed', 'booked', 'active', 'paid'];

    /** Cache TTL (detik) — sama dengan interval polling dashboard. */
    protected const CACHE_TTL = 30;

    /**
     * Resolve rentang waktu dari filter.
     *
     * @param string|null $range  today|yesterday|7d|30d|3m|6m|1y|custom
     * @param string|null $from   (custom)
     * @param string|null $to     (custom)
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function resolveRange(?string $range, ?string $from = null, ?string $to = null): array
    {
        $now = now();

        switch ($range) {
            case 'today':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];

            case 'yesterday':
                $y = $now->copy()->subDay();
                return [$y->copy()->startOfDay(), $y->copy()->endOfDay()];

            case '7d':
                return [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()];

            case '3m':
                return [$now->copy()->subMonths(3)->startOfMonth(), $now->copy()->endOfDay()];

            case '6m':
                return [$now->copy()->subMonths(6)->startOfMonth(), $now->copy()->endOfDay()];

            case '1y':
                return [$now->copy()->subYear()->startOfMonth(), $now->copy()->endOfDay()];

            case 'custom':
                $start = $from ? Carbon::parse($from)->startOfDay() : $now->copy()->startOfMonth();
                $end   = $to   ? Carbon::parse($to)->endOfDay()   : $now->copy()->endOfDay();
                if ($end->lt($start)) {
                    [$start, $end] = [$end, $start];
                }
                return [$start, $end];

            default: // 30d
                return [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()];
        }
    }

    /**
     * Rentang periode sebelumnya dengan panjang yang sama.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    protected static function previousRange(Carbon $start, Carbon $end): array
    {
        $days = max(1, (int) $end->copy()->startOfDay()->diffInDays($start->copy()->startOfDay()) + 1);
        $prevEnd = $start->copy()->subSecond();

        return [
            $start->copy()->subDays($days)->startOfDay(),
            $prevEnd,
        ];
    }

    protected static function cacheKey(string $key, string $range, ?string $from = null, ?string $to = null): string
    {
        return 'dash.' . $key . '.' . $range . '.' . ($from ?? '') . '.' . ($to ?? '');
    }

    /**
     * Hitung persentase perubahan.
     */
    protected static function pct(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /* =================================================================
     * 1. OVERVIEW — KARTU STATISTIK
     * ================================================================= */

    /**
     * Ringkasan kartu KPI (total/aktif/inaktif/revenue + perbandingan).
     */
    public static function overview(?string $range = '30d', ?string $from = null, ?string $to = null): array
    {
        $range  = $range ?: '30d';
        [$start, $end] = self::resolveRange($range, $from, $to);
        [$pStart, $pEnd] = self::previousRange($start, $end);

        $key = self::cacheKey('overview', $range, $from, $to);

        return Cache::remember($key, self::CACHE_TTL, function () use ($start, $end, $pStart, $pEnd, $range) {

            // ── Member ──────────────────────────────────────────────
            $totalMembers    = Customer::count();
            $activeMembers   = self::activeMemberIds()->count();
            $inactiveMembers = max(0, $totalMembers - $activeMembers);

            $newMembers = Customer::whereBetween('created_at', [$start, $end])->count();
            $prevNew    = Customer::whereBetween('created_at', [$pStart, $pEnd])->count();

            // ── Revenue (transaksi berhasil) ─────────────────────────
            $rev = Transaction::whereIn('status', self::SUCCESS_TX_STATUSES)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('COALESCE(SUM(amount),0) as total, COUNT(*) as cnt')
                ->first();

            $prevRev = Transaction::whereIn('status', self::SUCCESS_TX_STATUSES)
                ->whereBetween('created_at', [$pStart, $pEnd])
                ->selectRaw('COALESCE(SUM(amount),0) as total, COUNT(*) as cnt')
                ->first();

            $revenue      = (float) $rev->total;
            $revenueCount = (int) $rev->cnt;
            $prevRevenue  = (float) $prevRev->total;

            // ── Booking kelas ────────────────────────────────────────
            $bookings = CustomerSchedule::whereIn('status', self::BOOKING_STATUSES)
                ->whereBetween('created_at', [$start, $end])
                ->count();
            $prevBookings = CustomerSchedule::whereIn('status', self::BOOKING_STATUSES)
                ->whereBetween('created_at', [$pStart, $pEnd])
                ->count();

            // ── Check-in ─────────────────────────────────────────────
            $checkins = Attendance::whereBetween('check_in_at', [$start, $end])->count();
            $prevCheckins = Attendance::whereBetween('check_in_at', [$pStart, $pEnd])->count();

            // ── Retention & Churn ────────────────────────────────────
            $retention = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0.0;
            $churn     = $totalMembers > 0 ? round(($inactiveMembers / $totalMembers) * 100, 1) : 0.0;

            $prevActive   = self::activeMemberIds('created_at', [$pStart, $pEnd])->count();
            $prevRetention = $totalMembers > 0 ? round(($prevActive / $totalMembers) * 100, 1) : 0.0;

            return [
                'range'             => $range,
                'rangeLabel'        => self::rangeLabel($start, $end),
                'totalMembers'      => $totalMembers,
                'activeMembers'     => $activeMembers,
                'inactiveMembers'   => $inactiveMembers,
                'newMembers'        => $newMembers,
                'newMembersPrev'    => $prevNew,
                'newMembersDelta'   => self::pct($newMembers, $prevNew),
                'revenue'           => $revenue,
                'revenuePrev'       => $prevRevenue,
                'revenueCount'      => $revenueCount,
                'revenuePrevCount'  => (int) $prevRev->cnt,
                'revenueAvg'        => $revenueCount > 0 ? round($revenue / $revenueCount) : 0,
                'revenueDelta'      => self::pct($revenue, $prevRevenue),
                'bookings'          => $bookings,
                'bookingsPrev'      => $prevBookings,
                'bookingsDelta'     => self::pct($bookings, $prevBookings),
                'checkins'          => $checkins,
                'checkinsPrev'      => $prevCheckins,
                'checkinsDelta'     => self::pct($checkins, $prevCheckins),
                'retention'         => $retention,
                'retentionPrev'     => $prevRetention,
                'churn'             => $churn,
            ];
        });
    }

    /**
     * Subquery / query builder ID member yang masih punya paket aktif.
     *
     * @param string|null $dateColumn kolom created_at order untuk rentang pembelian
     */
    protected static function activeMemberIds(?string $dateColumn = null, ?array $dateRange = null)
    {
        $q = Order::query()
            ->select('customer_id')
            ->distinct()
            ->whereIn('status', self::ACTIVE_ORDER_STATUSES)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->where(function ($query) {
                $query->where('remaining_classes', '>', 0)
                    ->orWhere('remaining_quota', '>', 0)
                    ->orWhere(function ($q2) {
                        $q2->whereNull('remaining_classes')
                            ->whereNull('remaining_quota');
                    });
            });

        if ($dateColumn && $dateRange) {
            $q->whereBetween($dateColumn, $dateRange);
        }

        return $q;
    }

    /* =================================================================
     * 2. SERIES — GRAFIK HARIAN/BULANAN
     * ================================================================= */

    /**
     * Data seri untuk grafik (member baru, revenue, booking, check-in).
     */
    public static function charts(?string $range = '30d', ?string $from = null, ?string $to = null): array
    {
        $range  = $range ?: '30d';
        [$start, $end] = self::resolveRange($range, $from, $to);

        $key = self::cacheKey('charts', $range, $from, $to);

        return Cache::remember($key, self::CACHE_TTL, function () use ($start, $end) {

            $monthly = $end->copy()->startOfDay()->diffInDays($start->copy()->startOfDay()) > 62;

            // ── Siapkan label ────────────────────────────────────────
            $labels = [];
            $buckets = [];
            if ($monthly) {
                $cur = $start->copy()->startOfMonth();
                while ($cur->lte($end)) {
                    $b = $cur->format('Y-m');
                    $labels[] = $cur->locale('id')->isoFormat('MMM YYYY');
                    $buckets[$b] = ['new' => 0, 'revenue' => 0, 'booking' => 0, 'checkin' => 0];
                    $cur->addMonth();
                }
            } else {
                $cur = $start->copy()->startOfDay();
                while ($cur->lte($end)) {
                    $b = $cur->format('Y-m-d');
                    $labels[] = $cur->locale('id')->isoFormat('D MMM');
                    $buckets[$b] = ['new' => 0, 'revenue' => 0, 'booking' => 0, 'checkin' => 0];
                    $cur->addDay();
                }
            }

            $dateExpr = $monthly ? "DATE_FORMAT(created_at, '%Y-%m')" : "DATE(created_at)";

            // Member baru
            Customer::whereBetween('created_at', [$start, $end])
                ->selectRaw("$dateExpr as d, COUNT(*) as c")
                ->groupBy('d')->get()
                ->each(function ($row) use (&$buckets) {
                    if (isset($buckets[$row->d])) $buckets[$row->d]['new'] = (int) $row->c;
                });

            // Revenue
            Transaction::whereIn('status', self::SUCCESS_TX_STATUSES)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw("$dateExpr as d, SUM(amount) as c")
                ->groupBy('d')->get()
                ->each(function ($row) use (&$buckets) {
                    if (isset($buckets[$row->d])) $buckets[$row->d]['revenue'] = (float) $row->c;
                });

            // Booking
            CustomerSchedule::whereIn('status', self::BOOKING_STATUSES)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw("$dateExpr as d, COUNT(*) as c")
                ->groupBy('d')->get()
                ->each(function ($row) use (&$buckets) {
                    if (isset($buckets[$row->d])) $buckets[$row->d]['booking'] = (int) $row->c;
                });

            // Check-in
            Attendance::whereBetween('check_in_at', [$start, $end])
                ->selectRaw("DATE_FORMAT(check_in_at, '" . ($monthly ? '%Y-%m' : '%Y-%m-%d') . "') as d, COUNT(*) as c")
                ->groupBy('d')->get()
                ->each(function ($row) use (&$buckets) {
                    if (isset($buckets[$row->d])) $buckets[$row->d]['checkin'] = (int) $row->c;
                });

            $newMembers = [];
            $revenue    = [];
            $bookings   = [];
            $checkins   = [];
            foreach ($buckets as $values) {
                $newMembers[] = $values['new'];
                $revenue[]    = (int) round($values['revenue']);
                $bookings[]   = $values['booking'];
                $checkins[]   = $values['checkin'];
            }

            return [
                'labels'    => $labels,
                'newMembers'=> $newMembers,
                'revenue'   => $revenue,
                'bookings'  => $bookings,
                'checkins'  => $checkins,
                'monthly'   => $monthly,
            ];
        });
    }

    /* =================================================================
     * 3. AKTIVITAS TERBARU (REAL-TIME)
     * ================================================================= */

    public static function activity(int $limit = 10): array
    {
        return Cache::remember('dash.activity.' . $limit, self::CACHE_TTL, function () use ($limit) {

            $items = [];

            // Member baru mendaftar
            Customer::with('package:id,name')
                ->latest('created_at')->take(15)->get()
                ->each(function ($c) use (&$items) {
                    $items[] = [
                        'time'  => $c->created_at,
                        'type'  => 'member',
                        'icon'  => 'user-plus',
                        'color' => 'pink',
                        'title' => 'Member baru mendaftar',
                        'text'  => $c->name . ($c->package ? ' — ' . $c->package->name : ''),
                    ];
                });

            // Pembelian paket
            Order::with(['customer:id,name', 'package:id,name'])
                ->latest('created_at')->take(15)->get()
                ->each(function ($o) use (&$items) {
                    $items[] = [
                        'time'  => $o->created_at,
                        'type'  => 'order',
                        'icon'  => 'shopping-bag',
                        'color' => 'cherry',
                        'title' => in_array($o->status, ['cancelled', 'failed']) ? 'Pembelian paket dibatalkan' : 'Pembelian paket',
                        'text'  => ($o->customer->name ?? $o->customer_name) . ' — ' . ($o->package->name ?? 'Paket') . ' · #' . $o->order_code,
                    ];
                });

            // Transaksi
            Transaction::with(['customer:id,name', 'package:id,name'])
                ->latest('created_at')->take(15)->get()
                ->each(function ($t) use (&$items) {
                    $ok = in_array($t->status, self::SUCCESS_TX_STATUSES);
                    $items[] = [
                        'time'  => $t->created_at,
                        'type'  => 'transaction',
                        'icon'  => $ok ? 'credit-card-check' : 'credit-card-x',
                        'color' => $ok ? 'green' : 'amber',
                        'title' => $ok ? 'Pembayaran berhasil' : 'Pembayaran gagal',
                        'text'  => ($t->customer_name ?? $t->customer->name ?? 'Member') . ' · Rp ' . number_format($t->amount, 0, ',', '.') . ' (' . $t->status . ')',
                    ];
                });

            // Booking kelas
            CustomerSchedule::with(['customer:id,name', 'schedule:id,schedule_label,schedule_date,class_time'])
                ->latest('created_at')->take(15)->get()
                ->each(function ($b) use (&$items) {
                    $label = $b->schedule->schedule_label
                        ?? optional($b->schedule->schedule_date)->format('d M')
                        ?? 'Kelas';
                    $items[] = [
                        'time'  => $b->created_at,
                        'type'  => 'booking',
                        'icon'  => 'calendar-plus',
                        'color' => 'cherry',
                        'title' => 'Booking kelas',
                        'text'  => ($b->customer->name ?? 'Member') . ' memesan ' . $label,
                    ];
                });

            // Check-in
            Attendance::with(['customer:id,name'])
                ->whereNotNull('check_in_at')
                ->latest('check_in_at')->take(15)->get()
                ->each(function ($a) use (&$items) {
                    $items[] = [
                        'time'  => $a->check_in_at,
                        'type'  => 'attendance',
                        'icon'  => 'qr-code',
                        'color' => 'green',
                        'title' => 'Member check-in',
                        'text'  => $a->customer->name ?? 'Member',
                    ];
                });

            usort($items, fn ($a, $b) => $b['time'] <=> $a['time']);

            return array_slice($items, 0, $limit);
        });
    }

    /* =================================================================
     * 4. NOTIFIKASI / ALERT OTOMATIS
     * ================================================================= */

    public static function alerts(): array
    {
        return Cache::remember('dash.alerts', self::CACHE_TTL, function () {
            $now = now();

            // Paket akan expired dalam 3 / 7 / 14 hari
            $expiring = Order::whereIn('status', self::ACTIVE_ORDER_STATUSES)
                ->whereNotNull('expired_at')
                ->whereBetween('expired_at', [$now->copy()->startOfDay(), $now->copy()->addDays(14)->endOfDay()])
                ->with('customer:id,name')
                ->get(['id', 'customer_id', 'expired_at']);

            $expire3  = $expiring->filter(fn ($o) => $o->expired_at->lte($now->copy()->addDays(3)->endOfDay()))->count();
            $expire7  = $expiring->filter(fn ($o) => $o->expired_at->lte($now->copy()->addDays(7)->endOfDay()))->count();
            $expire14 = $expiring->count();
            $expireSoon = $expiring->sortBy('expired_at')->take(5)
                ->map(fn ($o) => [
                    'name' => $o->customer->name ?? 'Member',
                    'at'   => $o->expired_at->format('d M'),
                ])->values();

            // Member yang sudah beli paket tapi belum pernah booking
            $neverBooked = Customer::whereNotNull('package_id')
                ->whereDoesntHave('schedules')
                ->count();

            // Member yang belum check-in lebih dari 14 hari (punya booking sebelumnya)
            $noCheckin14 = Customer::whereHas('attendances')
                ->whereDoesntHave('attendances', function ($q) use ($now) {
                    $q->where('check_in_at', '>=', $now->copy()->subDays(14));
                })
                ->count();

            // Kelas hampir penuh / penuh (hari ini)
            $classInfo = self::classOccupancy();
            $classFull = $classInfo['full'];
            $classNearlyFull = $classInfo['nearlyFull'];

            // Transaksi gagal terbaru
            $failedTx = Transaction::whereIn('status', ['failed', 'deny', 'expire', 'cancel'])
                ->where('created_at', '>=', $now->copy()->subDays(7))
                ->count();

            return [
                'expire3'        => $expire3,
                'expire7'        => $expire7,
                'expire14'       => $expire14,
                'expireSoon'     => $expireSoon,
                'neverBooked'    => $neverBooked,
                'noCheckin14'    => $noCheckin14,
                'classFull'      => $classFull,
                'classNearlyFull'=> $classNearlyFull,
                'failedTx'       => $failedTx,
                'total'          => $expire7 + $neverBooked + $noCheckin14 + count($classFull) + count($classNearlyFull) + $failedTx,
            ];
        });
    }

    /**
     * Okupansi kelas untuk hari ini (penuh / hampir penuh).
     */
    protected static function classOccupancy(): array
    {
        $todaySchedules = Schedule::whereDate('schedule_date', today())
            ->with(['packages:id,participant_count,quota', 'classModel:id,class_name'])
            ->withCount(['customers as booked_count' => function ($q) {
                $q->whereIn('customer_schedules.status', self::BOOKING_STATUSES);
            }])
            ->orderBy('class_time')
            ->take(40)
            ->get();

        $full = [];
        $nearlyFull = [];

        foreach ($todaySchedules as $s) {
            $capacity = $s->packages->max('participant_count')
                ?? $s->packages->max('quota')
                ?? 0;
            if ($capacity <= 0) {
                continue;
            }

            $booked = (int) $s->booked_count;
            $ratio = $booked / $capacity;

            $label = ($s->schedule_label
                ?? optional($s->classModel)->class_name
                ?? 'Kelas') . ' · ' . ($s->class_time ?? '');

            if ($ratio >= 1) {
                $full[] = ['label' => trim($label), 'booked' => $booked, 'capacity' => $capacity];
            } elseif ($ratio >= 0.8) {
                $nearlyFull[] = ['label' => trim($label), 'booked' => $booked, 'capacity' => $capacity];
            }
        }

        return [
            'full'       => $full,
            'nearlyFull' => $nearlyFull,
        ];
    }

    /* =================================================================
     * 5. QUICK INSIGHT (RINGKASAN OTOMATIS)
     * ================================================================= */

    public static function insights(?string $range = '30d', ?string $from = null, ?string $to = null): array
    {
        $o   = self::overview($range, $from, $to);
        $a   = self::alerts();
        $rev = Transaction::whereIn('status', self::SUCCESS_TX_STATUSES)
            ->whereDate('created_at', today())
            ->sum('amount');
        $bookedToday = CustomerSchedule::whereIn('status', self::BOOKING_STATUSES)
            ->whereDate('created_at', today())
            ->count();
        $checkinToday = Attendance::whereDate('check_in_at', today())->count();

        $lines = [];

        $lines[] = "Hari ini terdapat {$bookedToday} booking dan {$checkinToday} check-in.";
        $lines[] = $rev > 0
            ? 'Pendapatan hari ini Rp ' . number_format($rev, 0, ',', '.') . '.'
            : 'Belum ada transaksi berhasil hari ini.';

        $d = $o['revenueDelta'];
        $lines[] = $d >= 0
            ? "Pendapatan periode ini meningkat {$d}% dibanding periode sebelumnya."
            : "Pendapatan periode ini turun " . abs($d) . "% dibanding periode sebelumnya.";

        if ($a['expire7'] > 0) {
            $lines[] = "{$a['expire7']} member akan segera expired dalam 7 hari ke depan.";
        }

        if ($a['classFull']) {
            $first = $a['classFull'][0]['label'];
            $lines[] = "Kelas {$first} sudah penuh hari ini.";
        } elseif ($a['classNearlyFull']) {
            $first = $a['classNearlyFull'][0]['label'];
            $lines[] = "Kelas {$first} hampir penuh hari ini.";
        }

        $lines[] = "Retention periode ini mencapai {$o['retention']}% dan churn rate {$o['churn']}%.";

        return $lines;
    }

    /**
     * Member terbaru untuk tabel dashboard (real-time).
     */
    public static function recentCustomers(int $limit = 5): array
    {
        return Customer::with('package:id,name')
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(fn ($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'email'     => $c->email,
                'phone'     => $c->phone_number,
                'package'   => $c->package->name ?? null,
                'is_active' => (bool) $c->is_login_active,
                'created_at'=> $c->created_at?->format('Y-m-d'),
                'created_at_label' => $c->created_at?->locale('id')->isoFormat('D MMM YYYY'),
                'url'       => url('/admin/resources/customers/' . $c->id),
            ])
            ->all();
    }

    /* =================================================================
     * 6. SEARCH REAL-TIME
     * ================================================================= */    public static function search(string $q): array
    {
        $q = trim($q);
        if (mb_strlen($q) < 2) {
            return ['customers' => [], 'orders' => [], 'bookings' => [], 'classes' => [], 'total' => 0];
        }

        $term = '%' . $q . '%';

        $customers = Customer::where('name', 'like', $term)
            ->orWhere('email', 'like', $term)
            ->orWhere('phone_number', 'like', $term)
            ->with('package:id,name')
            ->latest('created_at')
            ->take(6)
            ->get(['id', 'name', 'email', 'phone_number', 'package_id'])
            ->map(fn ($c) => [
                'id'    => $c->id,
                'name'  => $c->name,
                'email' => $c->email,
                'phone' => $c->phone_number,
                'url'   => url('/admin/resources/customers/' . $c->id),
            ]);

        $orders = Order::where('order_code', 'like', $term)
            ->orWhere('customer_name', 'like', $term)
            ->with(['customer:id,name', 'package:id,name'])
            ->latest('created_at')
            ->take(6)
            ->get(['id', 'order_code', 'customer_name', 'package_id', 'status', 'amount', 'customer_id'])
            ->map(fn ($o) => [
                'id'    => $o->id,
                'code'  => $o->order_code,
                'name'  => $o->customer_name ?? $o->customer->name ?? '-',
                'package'=> $o->package->name ?? '-',
                'status'=> $o->status,
                'amount'=> $o->amount,
                'url'   => url('/admin/resources/orders/' . $o->id),
            ]);

        $bookings = CustomerSchedule::whereHas('customer', fn ($q2) => $q2->where('name', 'like', $term))
            ->orWhereHas('schedule', fn ($q2) => $q2->where('schedule_label', 'like', $term))
            ->with(['customer:id,name', 'schedule:id,schedule_label,schedule_date,class_time'])
            ->latest('created_at')
            ->take(6)
            ->get()
            ->map(fn ($b) => [
                'id'    => $b->id,
                'name'  => $b->customer->name ?? '-',
                'label' => $b->schedule->schedule_label ?? 'Kelas',
                'date'  => optional($b->schedule->schedule_date)->format('d M') ?? '-',
                'url'   => url('/admin/resources/customer-schedules/' . $b->id),
            ]);

        $classes = Schedule::where('schedule_label', 'like', $term)
            ->orWhere('instructor', 'like', $term)
            ->orWhereHas('classModel', fn ($q2) => $q2->where('class_name', 'like', $term))
            ->with('classModel:id,class_name')
            ->whereDate('schedule_date', '>=', today())
            ->latest('schedule_date')
            ->take(6)
            ->get(['id', 'schedule_label', 'class_id', 'schedule_date', 'class_time', 'instructor'])
            ->map(fn ($s) => [
                'id'    => $s->id,
                'label' => $s->schedule_label ?? optional($s->classModel)->class_name ?? 'Kelas',
                'date'  => optional($s->schedule_date)->format('d M Y'),
                'time'  => $s->class_time,
                'instructor' => $s->instructor,
                'url'   => url('/admin/resources/schedules/' . $s->id),
            ]);

        return [
            'customers' => $customers,
            'orders'    => $orders,
            'bookings'  => $bookings,
            'classes'   => $classes,
            'total'     => $customers->count() + $orders->count() + $bookings->count() + $classes->count(),
        ];
    }

    /* =================================================================
     * Helper
     * ================================================================= */

    protected static function rangeLabel(Carbon $start, Carbon $end): string
    {
        if ($start->isSameDay($end)) {
            return $start->locale('id')->isoFormat('D MMM YYYY');
        }
        return $start->locale('id')->isoFormat('D MMM') . ' – ' . $end->locale('id')->isoFormat('D MMM YYYY');
    }
}
