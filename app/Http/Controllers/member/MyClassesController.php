<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerSchedule;
use App\Models\Order;
use Carbon\Carbon;

class MyClassesController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        Log::info('📚 My Classes - Loading', [
            'customer_id' => $customer->id,
        ]);

        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        // ✅ QUERY SEMUA customer_schedules (confirmed) milik user
        $allCustomerSchedules = CustomerSchedule::with([
                'schedule.classModel',  // Untuk nama class & instructor
                'order.package'         // DIRECT RELATION ke order & package
            ])
            ->where('customer_schedules.customer_id', $customer->id)
            ->where('customer_schedules.status', 'confirmed')
            ->join('schedules', 'schedules.id', '=', 'customer_schedules.schedule_id')
            ->select('customer_schedules.*')
            ->get();

        // ✅ GET CUSTOMER'S ORDERS untuk fallback matching & stats
        $customerOrders = Order::with('package')
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
            ->get();

        $activeOrders = $customerOrders->filter(function($order) {
            return is_null($order->expired_at) || $order->expired_at > now();
        });

        // ✅ MAP package info & status kadaluarsa jadwal ke setiap schedule
        $processedSchedules = $allCustomerSchedules->map(function($item) use ($customerOrders, $activeOrders, $customer, $today, $currentTime) {
            // Cek apakah jadwal sudah lewat (Past / Selesai)
            $scheduleDate = $item->schedule->schedule_date ? Carbon::parse($item->schedule->schedule_date)->toDateString() : null;
            $classTime = $item->schedule->class_time ? Carbon::parse($item->schedule->class_time)->format('H:i:s') : '23:59:59';

            $isPast = false;
            if ($scheduleDate) {
                if ($scheduleDate < $today) {
                    $isPast = true;
                } elseif ($scheduleDate === $today && $classTime < $currentTime) {
                    $isPast = true;
                }
            }

            $item->is_past = $isPast;

            // Matching order & package info
            if ($item->order && $item->order->package) {
                $item->package_info = [
                    'id' => $item->order->package_id,
                    'name' => $item->order->package->name,
                    'order_id' => $item->order->id,
                    'order_code' => $item->order->order_code,
                    'expired_at' => $item->order->expired_at,
                    'status' => $item->order->status,
                ];
            } else {
                $fallbackOrder = $activeOrders->first() ?? $customerOrders->first();
                if ($fallbackOrder) {
                    $item->package_info = [
                        'id' => $fallbackOrder->package_id,
                        'name' => $fallbackOrder->package->name ?? 'Membership',
                        'order_id' => $fallbackOrder->id,
                        'order_code' => $fallbackOrder->order_code,
                        'expired_at' => $fallbackOrder->expired_at,
                        'status' => $fallbackOrder->status,
                    ];
                } else {
                    $item->package_info = [
                        'id' => null,
                        'name' => 'Membership',
                        'order_id' => null,
                        'order_code' => null,
                        'expired_at' => null,
                        'status' => null,
                    ];
                }
            }

            return $item;
        });

        // ✅ Pisahkan kelas aktif (Upcoming) dan kelas lewat (Past)
        // 1. Upcoming Classes: Urutkan dari tanggal terdekat ke depan
        $myClasses = $processedSchedules->filter(fn($item) => !$item->is_past)->sortBy(function($item) {
            $date = $item->schedule->schedule_date ? Carbon::parse($item->schedule->schedule_date)->format('Y-m-d') : '9999-12-31';
            $time = $item->schedule->class_time ?? '00:00:00';
            return $date . ' ' . $time;
        })->values();

        // 2. Past Classes: Urutkan dari yang paling baru lewat ke yang lama
        $pastClasses = $processedSchedules->filter(fn($item) => $item->is_past)->sortByDesc(function($item) {
            $date = $item->schedule->schedule_date ? Carbon::parse($item->schedule->schedule_date)->format('Y-m-d') : '1970-01-01';
            $time = $item->schedule->class_time ?? '00:00:00';
            return $date . ' ' . $time;
        })->values();

        // ✅ STATS
        $stats = [
            'total_classes' => $myClasses->count(),
            'past_classes_count' => $pastClasses->count(),
            'unique_packages' => $customerOrders->pluck('package_id')->unique()->count(),
        ];

        Log::info('📊 My Classes - Results', [
            'upcoming_classes' => $stats['total_classes'],
            'past_classes' => $stats['past_classes_count'],
            'unique_packages' => $stats['unique_packages'],
        ]);

        return view('member.my-classes', [
            'myClasses' => $myClasses,
            'pastClasses' => $pastClasses,
            'stats' => $stats,
            'activePackages' => $activeOrders,
        ]);
    }
}