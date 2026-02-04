<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerSchedule;
use App\Models\Order;

class MyClassesController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        Log::info('📚 My Classes - Loading', [
            'customer_id' => $customer->id,
        ]);

        // ✅ QUERY customer_schedules dengan eager loading untuk relasi order & schedule
        $myClasses = CustomerSchedule::with([
                'schedule.classModel',  // Untuk nama class & instructor
                'order.package'         // ✅ DIRECT RELATION ke order & package
            ])
            ->where('customer_schedules.customer_id', $customer->id)
            ->where('customer_schedules.status', 'confirmed')
            ->join('schedules', 'schedules.id', '=', 'customer_schedules.schedule_id')
            ->select('customer_schedules.*')
            ->orderByRaw("
                FIELD(schedules.day,
                    'Monday','Tuesday','Wednesday',
                    'Thursday','Friday','Saturday','Sunday'
                )
            ")
            ->orderBy('schedules.class_time')
            ->get();

        // ✅ GET CUSTOMER'S ACTIVE ORDERS/PACKAGES untuk stats
        $customerOrders = Order::with('package')
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
            ->where(function($query) {
                $query->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
            })
            ->get();

        // ✅ MAP package info ke setiap class
        // PERFECT SOLUTION: Langsung ambil dari order_id yang sudah ada di customer_schedules
        $myClasses = $myClasses->map(function($item) use ($customerOrders, $customer) {
            
            // ✅ PERFECT MATCH: Langsung dari relasi order
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
                // ⚠️ FALLBACK: Jika order_id null atau order sudah dihapus
                // Cari order yang aktif sebagai fallback
                $fallbackOrder = $customerOrders->first();
                
                if ($fallbackOrder) {
                    $item->package_info = [
                        'id' => $fallbackOrder->package_id,
                        'name' => $fallbackOrder->package->name ?? 'Unknown Package',
                        'order_id' => $fallbackOrder->id,
                        'order_code' => $fallbackOrder->order_code,
                        'expired_at' => $fallbackOrder->expired_at,
                        'status' => $fallbackOrder->status,
                    ];
                    
                    Log::warning('⚠️ Using fallback order for customer_schedule', [
                        'customer_schedule_id' => $item->id,
                        'schedule_id' => $item->schedule_id,
                        'order_id_in_db' => $item->order_id,
                        'fallback_order_id' => $fallbackOrder->id,
                    ]);
                } else {
                    // Tidak ada order aktif sama sekali
                    $item->package_info = [
                        'id' => null,
                        'name' => 'No Active Package',
                        'order_id' => null,
                        'order_code' => null,
                        'expired_at' => null,
                        'status' => null,
                    ];
                    
                    Log::error('❌ No active order found for customer_schedule', [
                        'customer_schedule_id' => $item->id,
                        'schedule_id' => $item->schedule_id,
                        'customer_id' => $customer->id,  // ✅ FIX: Gunakan $customer dari use()
                    ]);
                }
            }

            return $item;
        });

        // ✅ STATS
        $stats = [
            'total_classes' => $myClasses->count(),
            'unique_packages' => $customerOrders->count(),
        ];

        // ✅ FIX: Tambahkan null check sebelum toArray()
        $packageBreakdown = $myClasses->groupBy('package_info.name')->map(function($group) {
            return $group->count();
        });

        Log::info('📊 My Classes - Results', [
            'total_classes' => $stats['total_classes'],
            'unique_packages' => $stats['unique_packages'],
            'package_breakdown' => $packageBreakdown->toArray(),  // ✅ FIX: Sudah dipastikan Collection
        ]);

        return view('member.my-classes', [
            'myClasses' => $myClasses,
            'stats' => $stats,
            'activePackages' => $customerOrders,
        ]);
    }
}