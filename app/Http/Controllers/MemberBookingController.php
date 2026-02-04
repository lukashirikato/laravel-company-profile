<?php

namespace App\Http\Controllers;

use App\Models\CustomerSchedule;
use App\Models\Schedule;
use App\Models\Order;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberBookingController extends Controller
{
    // ✅ KONSTANTA UNTUK DAY ORDER
    private const DAY_ORDER = [
        'Monday', 
        'Tuesday', 
        'Wednesday', 
        'Thursday', 
        'Friday', 
        'Saturday', 
        'Sunday'
    ];

    // ✅ MAPPING PACKAGE GROUP - Packages yang share jadwal yang sama
    private const PACKAGE_GROUPS = [
        // Reformer Pilates: Single, Double, Triple semua akses jadwal package_id 2
        'reformer_pilates' => [
            'package_ids' => [2, 3, 4], // Single, Double, Triple
            'schedule_package_id' => 2, // Ambil schedules dari package_id 2
        ],
        // Single Visit Class: semua variant akses jadwal yang sama
        'single_visit' => [
            'package_ids' => [5, 6, 7], // Single, Bundle 2, Bundle 4
            'schedule_package_id' => 5, // Atau bisa ambil dari semua (5, 6, 7)
        ],
    ];

    /**
     * =========================
     * HALAMAN BOOK CLASS
     * =========================
     */
    public function index(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();

            // ✅ CEK AUTHENTICATION
            if (!$customer) {
                Log::warning('❌ Unauthenticated access to book-class page');
                return $this->returnEmptyView(null, 'Silakan login terlebih dahulu.');
            }

            // ✅ AMBIL SEMUA ORDER AKTIF
            $activeOrders = $this->getActiveOrders($customer);

            if ($activeOrders->isEmpty()) {
                Log::info('📭 Customer has no active orders', [
                    'customer_id' => $customer->id,
                ]);

                return $this->returnEmptyView(
                    $customer, 
                    'Anda belum memiliki paket aktif. Silakan beli paket terlebih dahulu.'
                );
            }

            // ✅ TENTUKAN ORDER YANG DIPILIH
            $selectedOrderId = $request->get('order_id') ?? $activeOrders->first()->id;
            $selectedOrder = $activeOrders->firstWhere('id', $selectedOrderId);

            if (!$selectedOrder || !$selectedOrder->package) {
                Log::warning('⚠️ Selected order not found or has no package', [
                    'selected_order_id' => $selectedOrderId,
                    'customer_id' => $customer->id,
                ]);

                return $this->returnViewWithOrders(
                    $customer,
                    $activeOrders,
                    $selectedOrderId,
                    'Paket tidak ditemukan.'
                );
            }

            $package = $selectedOrder->package;

            Log::info('🔍 Processing selected order', [
                'order_id' => $selectedOrder->id,
                'package_id' => $package->id,
                'package_name' => $package->name,
                'is_exclusive' => $package->is_exclusive,
                'class_id' => $package->class_id,
            ]);

            // ✅ AMBIL JADWAL YANG SUDAH DI-BOOK
            $bookedScheduleIds = $this->getBookedScheduleIds($customer, $selectedOrder);

            // ✅ AMBIL JADWAL BERDASARKAN TIPE PAKET
            $schedules = $this->getSchedulesForPackage($customer, $selectedOrder, $package);

            // ✅ CEK JIKA TIDAK ADA JADWAL
            if ($schedules->isEmpty()) {
                Log::warning('📭 No schedules available for this package', [
                    'customer_id' => $customer->id,
                    'order_id' => $selectedOrder->id,
                    'package_id' => $package->id,
                    'is_exclusive' => $package->is_exclusive,
                ]);

                return $this->returnViewWithOrders(
                    $customer,
                    $activeOrders,
                    $selectedOrderId,
                    'Tidak ada jadwal tersedia untuk paket ini. Silakan hubungi admin.',
                    $package
                );
            }

            // ✅ SORT & GROUP SCHEDULES
            $groupedSchedules = $this->sortAndGroupSchedules($schedules);

            Log::info('📅 Schedules loaded successfully', [
                'customer_id' => $customer->id,
                'order_id' => $selectedOrder->id,
                'package_name' => $package->name,
                'total_schedules' => $schedules->count(),
                'days' => $groupedSchedules->keys()->toArray(),
                'quota_remaining' => $customer->quota,
            ]);

            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => $groupedSchedules,
                'bookedScheduleIds' => $bookedScheduleIds,
                'activeOrders' => $activeOrders,
                'selectedOrderId' => $selectedOrderId,
                'selectedPackage' => $package,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error in book-class index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->returnEmptyView(
                Auth::guard('customer')->user(),
                'Terjadi kesalahan. Silakan coba lagi.'
            );
        }
    }

    /**
     * =========================
     * PROSES BOOKING CLASS
     * =========================
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'schedule_id' => ['required', 'exists:schedules,id'],
            ]);

            /** @var \App\Models\Customer $customer */
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return back()->with('error', 'Silakan login ulang');
            }

            // ✅ CEK QUOTA
            if ($customer->quota <= 0) {
                Log::warning('⚠️ Customer quota exhausted', [
                    'customer_id' => $customer->id,
                    'quota' => $customer->quota,
                ]);

                return back()->with('error', 'Quota kamu sudah habis');
            }

            // ✅ CEK DOUBLE BOOKING
            $alreadyBooked = CustomerSchedule::where('customer_id', $customer->id)
                ->where('schedule_id', $validated['schedule_id'])
                ->where('status', 'confirmed')
                ->exists();

            if ($alreadyBooked) {
                Log::info('ℹ️ Customer already booked this schedule', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $validated['schedule_id'],
                ]);

                return back()->with('error', 'Kamu sudah booking kelas ini');
            }

            // ✅ AMBIL SCHEDULE
            $schedule = Schedule::find($validated['schedule_id']);

            if (!$schedule) {
                return back()->with('error', 'Jadwal tidak ditemukan');
            }

            // ✅ CARI ORDER YANG VALID
            $validOrder = $this->findValidOrderForSchedule($customer, $schedule);

            if (!$validOrder) {
                Log::warning('⚠️ No valid order for schedule', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $schedule->id,
                    'schedule_package_id' => $schedule->package_id,
                ]);

                return back()->with('error', 'Anda tidak memiliki paket yang sesuai untuk jadwal ini.');
            }

            // ✅ TRANSACTION BOOKING
            DB::transaction(function () use ($customer, $validated, $schedule, $validOrder) {
                CustomerSchedule::create([
                    'customer_id' => $customer->id,
                    'schedule_id' => $validated['schedule_id'],
                    'order_id'    => $validOrder->id,
                    'status'      => 'confirmed',
                    'joined_at'   => now(),
                ]);

                $customer->decrement('quota');

                Log::info('✅ Booking successful', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $validated['schedule_id'],
                    'order_id' => $validOrder->id,
                    'package' => $validOrder->package->name,
                    'day' => $schedule->day,
                    'time' => $schedule->class_time,
                    'quota_remaining' => $customer->quota - 1,
                ]);
            });

            return back()->with('success', 'Class berhasil dibooking!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('❌ Error booking class', [
                'customer_id' => Auth::guard('customer')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal melakukan booking. Silakan coba lagi.');
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get active orders for customer
     */
    private function getActiveOrders($customer)
    {
        return Order::where('customer_id', $customer->id)
            ->whereIn('status', ['active', 'paid'])
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get booked schedule IDs for customer and order
     */
    private function getBookedScheduleIds($customer, $order)
    {
        return CustomerSchedule::where('customer_id', $customer->id)
            ->where('order_id', $order->id)
            ->where('status', 'confirmed')
            ->pluck('schedule_id')
            ->toArray();
    }

    /**
     * Get schedules based on package type
     */
    private function getSchedulesForPackage($customer, $order, $package)
    {
        if ($package->is_exclusive) {
            return $this->getExclusiveSchedules($customer, $order);
        }

        return $this->getRegularSchedules($package);
    }

    /**
     * Get schedules for exclusive class
     */
    private function getExclusiveSchedules($customer, $order)
    {
        $assignedScheduleIds = CustomerSchedule::where('customer_id', $customer->id)
            ->where('order_id', $order->id)
            ->pluck('schedule_id')
            ->toArray();

        if (empty($assignedScheduleIds)) {
            Log::warning('📭 Exclusive class has no assigned schedules', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
            ]);

            return collect();
        }

        $schedules = Schedule::whereIn('id', $assignedScheduleIds)
            ->where('show_on_landing', 1)
            ->with('classModel')
            ->get();

        Log::info('✅ Loaded exclusive class schedules', [
            'order_id' => $order->id,
            'assigned_count' => count($assignedScheduleIds),
            'loaded_count' => $schedules->count(),
        ]);

        return $schedules;
    }

    /**
     * ✅ UPDATED: Get schedules for regular package - SUPPORT PACKAGE GROUPS
     */
    private function getRegularSchedules($package)
    {
        // ✅ 1. Cek apakah package ini bagian dari package group
        $schedulePackageIds = $this->getSchedulePackageIds($package);

        // ✅ 2. Query schedules berdasarkan package_id
        $schedules = Schedule::whereIn('package_id', $schedulePackageIds)
            ->where('show_on_landing', 1)
            ->get();

        // ✅ 3. Attach classModel data manually untuk display
        $schedules = $schedules->map(function ($schedule) {
            if ($schedule->class_id) {
                $schedule->classModel = ClassModel::find($schedule->class_id);
            }
            
            if (!$schedule->classModel) {
                $schedule->classModel = (object) [
                    'id' => $schedule->class_id,
                    'class_name' => $schedule->schedule_label ?? 'Class',
                ];
            }
            
            return $schedule;
        });

        Log::info('✅ Loaded regular package schedules', [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'schedule_package_ids' => $schedulePackageIds,
            'count' => $schedules->count(),
        ]);

        return $schedules;
    }

    /**
     * ✅ NEW: Determine which package_ids to query for schedules
     */
    private function getSchedulePackageIds($package)
    {
        // Cek apakah package ini ada dalam package groups
        foreach (self::PACKAGE_GROUPS as $groupName => $groupConfig) {
            if (in_array($package->id, $groupConfig['package_ids'])) {
                Log::info('📦 Package is part of group', [
                    'package_id' => $package->id,
                    'group' => $groupName,
                    'will_use_schedules_from' => $groupConfig['schedule_package_id'],
                ]);
                
                // Kembalikan schedule_package_id dari config
                // Bisa single ID atau array
                return is_array($groupConfig['schedule_package_id']) 
                    ? $groupConfig['schedule_package_id']
                    : [$groupConfig['schedule_package_id']];
            }
        }

        // Jika tidak ada di group, gunakan package_id sendiri
        return [$package->id];
    }

    /**
     * Sort and group schedules by day
     */
    private function sortAndGroupSchedules($schedules)
    {
        $sorted = $schedules->sortBy(function ($schedule) {
            $dayIndex = array_search($schedule->day, self::DAY_ORDER);
            return ($dayIndex !== false ? $dayIndex : 999) . $schedule->class_time;
        });

        return $sorted->groupBy('day');
    }

    /**
     * ✅ UPDATED: Find valid order for schedule - SUPPORT PACKAGE GROUPS
     */
    private function findValidOrderForSchedule($customer, $schedule)
    {
        $activeOrders = $this->getActiveOrders($customer);

        foreach ($activeOrders as $order) {
            $package = $order->package;

            if (!$package) {
                continue;
            }

            // ✅ UNTUK EXCLUSIVE CLASS
            if ($package->is_exclusive) {
                $isAssigned = CustomerSchedule::where('customer_id', $customer->id)
                    ->where('order_id', $order->id)
                    ->where('schedule_id', $schedule->id)
                    ->exists();

                if ($isAssigned) {
                    return $order;
                }
            }
            // ✅ UNTUK PAKET REGULAR
            else {
                // Cek apakah schedule package_id valid untuk package ini
                $validSchedulePackageIds = $this->getSchedulePackageIds($package);
                
                if (in_array($schedule->package_id, $validSchedulePackageIds)) {
                    Log::info('✅ Found matching order', [
                        'order_id' => $order->id,
                        'package_id' => $package->id,
                        'schedule_package_id' => $schedule->package_id,
                    ]);
                    return $order;
                }
            }
        }

        Log::warning('⚠️ No matching order found', [
            'customer_id' => $customer->id,
            'schedule_id' => $schedule->id,
            'schedule_package_id' => $schedule->package_id ?? 'NULL',
        ]);

        return null;
    }

    /**
     * Return empty view with error message
     */
    private function returnEmptyView($customer, $errorMessage = null)
    {
        $viewData = [
            'customer' => $customer,
            'schedules' => collect(),
            'bookedScheduleIds' => [],
            'activeOrders' => collect(),
            'selectedOrderId' => null,
            'selectedPackage' => null,
        ];

        if ($errorMessage) {
            return view('member.book-class', $viewData)->with('error', $errorMessage);
        }

        return view('member.book-class', $viewData);
    }

    /**
     * Return view with orders and error
     */
    private function returnViewWithOrders($customer, $activeOrders, $selectedOrderId, $errorMessage, $package = null)
    {
        return view('member.book-class', [
            'customer' => $customer,
            'schedules' => collect(),
            'bookedScheduleIds' => [],
            'activeOrders' => $activeOrders,
            'selectedOrderId' => $selectedOrderId,
            'selectedPackage' => $package,
        ])->with('error', $errorMessage);
    }
}