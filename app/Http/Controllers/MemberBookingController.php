<?php

namespace App\Http\Controllers;

use App\Models\CustomerSchedule;
use App\Models\Schedule;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberBookingController extends Controller
{
    /**
     * =========================
     * HALAMAN BOOK CLASS
     * =========================
     */
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => collect(),
                'bookedScheduleIds' => [],
                'activeOrders' => collect(),
                'selectedOrderId' => null,
                'selectedPackage' => null,
            ]);
        }

        // ✅ AMBIL SEMUA ORDER AKTIF
        $activeOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['active', 'paid'])
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($activeOrders->isEmpty()) {
            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => collect(),
                'bookedScheduleIds' => [],
                'activeOrders' => collect(),
                'selectedOrderId' => null,
                'selectedPackage' => null,
            ])->with('error', 'Anda belum memiliki paket aktif. Silakan beli paket terlebih dahulu.');
        }

        // ✅ TENTUKAN ORDER MANA YANG DIPILIH
        $selectedOrderId = $request->get('order_id');
        
        // Jika tidak ada yang dipilih, ambil order pertama (terbaru)
        if (!$selectedOrderId) {
            $selectedOrderId = $activeOrders->first()->id;
        }

        // ✅ AMBIL ORDER YANG DIPILIH
        $selectedOrder = $activeOrders->firstWhere('id', $selectedOrderId);

        if (!$selectedOrder || !$selectedOrder->package) {
            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => collect(),
                'bookedScheduleIds' => [],
                'activeOrders' => $activeOrders,
                'selectedOrderId' => $selectedOrderId,
                'selectedPackage' => null,
            ])->with('error', 'Paket tidak ditemukan.');
        }

        $package = $selectedOrder->package;

        Log::info('🔍 Processing selected order', [
            'order_id' => $selectedOrder->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'is_exclusive' => $package->is_exclusive,
            'class_id' => $package->class_id,
        ]);

        // 🔥 AMBIL JADWAL YANG SUDAH DI-BOOK UNTUK ORDER INI
        $bookedScheduleIds = CustomerSchedule::where('customer_id', $customer->id)
            ->where('order_id', $selectedOrder->id)
            ->where('status', 'confirmed')
            ->pluck('schedule_id')
            ->toArray();

        // ========================================
        // ✅ AMBIL JADWAL BERDASARKAN TIPE PAKET
        // ========================================
        
        $schedules = collect();

        // ========================================
        // UNTUK EXCLUSIVE CLASS (PRIVATE PROGRAM)
        // ========================================
        if ($package->is_exclusive) {
            
            // ✅ AMBIL JADWAL YANG SUDAH DI-ASSIGN KE CUSTOMER INI
            // (jadwal yang sudah di-insert saat payment success)
            $assignedScheduleIds = CustomerSchedule::where('customer_id', $customer->id)
                ->where('order_id', $selectedOrder->id)
                ->pluck('schedule_id')
                ->toArray();

            if (empty($assignedScheduleIds)) {
                Log::warning('📭 Exclusive class belum punya jadwal assigned', [
                    'order_id' => $selectedOrder->id,
                    'customer_id' => $customer->id,
                ]);

                return view('member.book-class', [
                    'customer' => $customer,
                    'schedules' => collect(),
                    'bookedScheduleIds' => [],
                    'activeOrders' => $activeOrders,
                    'selectedOrderId' => $selectedOrderId,
                    'selectedPackage' => $package,
                ])->with('error', 'Jadwal exclusive class Anda belum di-setup. Silakan hubungi admin.');
            }

            // ✅ AMBIL DETAIL SCHEDULE BERDASARKAN ID YANG SUDAH DI-ASSIGN
            $schedules = Schedule::whereIn('id', $assignedScheduleIds)
                ->where('show_on_landing', 1)
                ->with('classModel')
                ->get();

            Log::info('✅ Loaded exclusive class schedules', [
                'order_id' => $selectedOrder->id,
                'package_name' => $package->name,
                'assigned_schedule_ids' => $assignedScheduleIds,
                'loaded_count' => $schedules->count(),
            ]);

        } 
        // ========================================
        // UNTUK PAKET REGULAR (REFORMER PILATES, dll)
        // ========================================
        else {
            
            // ✅ GUNAKAN class_id DARI PACKAGE
            $classId = $package->class_id;

            // Safety: Jika class_id masih NULL, detect dari nama
            if (!$classId) {
                $packageName = strtolower($package->name);
                
                if (str_contains($packageName, 'reformer pilates')) {
                    $reformerClass = \App\Models\ClassModel::where('class_name', 'Reformer Pilates')->first();
                    $classId = $reformerClass ? $reformerClass->id : 12;
                } elseif (str_contains($packageName, 'single visit')) {
                    $singleClass = \App\Models\ClassModel::where('class_name', 'Single Visit Class')->first();
                    $classId = $singleClass ? $singleClass->id : 17;
                } else {
                    $classId = 12; // Default fallback
                }
                
                Log::warning('⚠️ Package class_id is NULL, using detection', [
                    'package_id' => $package->id,
                    'detected_class_id' => $classId,
                ]);
            }

            // ✅ QUERY JADWAL BERDASARKAN class_id
            $schedules = Schedule::where('class_id', $classId)
                ->where('show_on_landing', 1)
                ->with('classModel')
                ->get();
            
            Log::info('✅ Loaded regular package schedules', [
                'order_id' => $selectedOrder->id,
                'package_id' => $package->id,
                'package_name' => $package->name,
                'class_id' => $classId,
                'count' => $schedules->count(),
            ]);
        }

        // ========================================
        // CEK JIKA TIDAK ADA JADWAL
        // ========================================
        if ($schedules->isEmpty()) {
            Log::warning('📭 Tidak ada jadwal untuk paket ini', [
                'customer_id' => $customer->id,
                'order_id' => $selectedOrder->id,
                'package_id' => $package->id,
                'is_exclusive' => $package->is_exclusive,
            ]);

            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => collect(),
                'bookedScheduleIds' => [],
                'activeOrders' => $activeOrders,
                'selectedOrderId' => $selectedOrderId,
                'selectedPackage' => $package,
            ])->with('error', 'Tidak ada jadwal tersedia untuk paket ini. Silakan hubungi admin.');
        }

        // ========================================
        // GROUP BY DAY DENGAN URUTAN BENAR
        // ========================================
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        // Sort dulu berdasarkan day order
        $schedules = $schedules->sortBy(function ($schedule) use ($dayOrder) {
            $dayIndex = array_search($schedule->day, $dayOrder);
            return ($dayIndex !== false ? $dayIndex : 999) . $schedule->class_time;
        });
        
        // Baru group by day
        $grouped = $schedules->groupBy('day');

        Log::info('📅 Schedules loaded successfully', [
            'customer_id' => $customer->id,
            'order_id' => $selectedOrder->id,
            'package_name' => $package->name,
            'total_schedules' => $schedules->count(),
            'days' => $grouped->keys()->toArray(),
            'quota_remaining' => $customer->quota,
        ]);

        return view('member.book-class', [
            'customer' => $customer,
            'schedules' => $grouped,
            'bookedScheduleIds' => $bookedScheduleIds,
            'activeOrders' => $activeOrders,
            'selectedOrderId' => $selectedOrderId,
            'selectedPackage' => $package,
        ]);
    }

    /**
     * =========================
     * PROSES BOOKING CLASS
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
        ]);

        /** @var \App\Models\Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return back()->with('error', 'Silakan login ulang');
        }

        if ($customer->quota <= 0) {
            return back()->with('error', 'Quota kamu sudah habis');
        }

        // Cegah double booking
        $alreadyBooked = CustomerSchedule::where('customer_id', $customer->id)
            ->where('schedule_id', $request->schedule_id)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Kamu sudah booking kelas ini');
        }

        $schedule = Schedule::with('classModel')->find($request->schedule_id);
        
        if (!$schedule) {
            return back()->with('error', 'Jadwal tidak ditemukan');
        }

        // ✅ CARI ORDER YANG VALID
        $validOrder = $this->findValidOrderForSchedule($customer, $schedule);

        if (!$validOrder) {
            return back()->with('error', 'Anda tidak memiliki paket yang sesuai untuk jadwal ini.');
        }

        /**
         * TRANSACTION
         */
        DB::transaction(function () use ($customer, $request, $schedule, $validOrder) {

            CustomerSchedule::create([
                'customer_id' => $customer->id,
                'schedule_id' => $request->schedule_id,
                'order_id'    => $validOrder->id,
                'status'      => 'confirmed',
                'joined_at'   => now(),
            ]);

            $customer->decrement('quota');

            Log::info('✅ Customer booking berhasil', [
                'customer_id' => $customer->id,
                'schedule_id' => $request->schedule_id,
                'order_id' => $validOrder->id,
                'package' => $validOrder->package->name,
                'day' => $schedule->day,
                'time' => $schedule->class_time,
                'quota_remaining' => $customer->quota - 1,
            ]);
        });

        return back()->with('success', 'Class berhasil dibooking!');
    }

    /**
     * =========================
     * CARI ORDER YANG VALID
     * =========================
     */
    private function findValidOrderForSchedule($customer, $schedule)
    {
        $activeOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['active', 'paid'])
            ->with('package')
            ->get();

        foreach ($activeOrders as $order) {
            $package = $order->package;

            if (!$package) {
                continue;
            }

            // Untuk exclusive class
            if ($package->is_exclusive) {
                // Cek apakah schedule ini sudah di-assign ke order exclusive ini
                $isAssigned = CustomerSchedule::where('customer_id', $customer->id)
                    ->where('order_id', $order->id)
                    ->where('schedule_id', $schedule->id)
                    ->exists();
                
                if ($isAssigned) {
                    return $order;
                }
            } 
            // Untuk paket regular
            else {
                // Match berdasarkan class_id
                if (isset($package->class_id) && $package->class_id) {
                    if ($schedule->class_id === $package->class_id) {
                        return $order;
                    }
                } 
                // Fallback: assume semua schedule cocok (legacy support)
                else {
                    return $order;
                }
            }
        }

        return null;
    }
}