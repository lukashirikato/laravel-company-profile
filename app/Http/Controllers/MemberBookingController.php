<?php

namespace App\Http\Controllers;

use App\Models\CustomerSchedule;
use App\Models\Schedule;
use App\Models\Order;
use App\Models\ClassModel;
use App\Models\Package;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    // ✅ GROUPING DINAMIS BERDASARKAN NAMA PAKET
    private const PACKAGE_GROUP_PATTERNS = [
        'reformer_pilates' => ['reformer pilates'],
        'single_visit' => ['single visit class'],
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

            // ✅ AMBIL FILTER PARAMS
            $filters = [
                'date' => $request->get('date'),
                'time' => $request->get('time'),
                'class_type' => $request->get('class_type'),
                'instructor' => $request->get('instructor'),
            ];

            // ✅ HITUNG WEEK START DARI SELECTED DATE
            $selectedDate = $filters['date'] ? Carbon::parse($filters['date']) : Carbon::today();
            $weekStart = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $selectedDate->copy()->endOfWeek(Carbon::SUNDAY);

            // ✅ AMBIL JADWAL TANPA FILTER DAY (biar JS yang filter)
            $allSchedules = $this->getSchedulesForPackage($customer, $selectedOrder, $package, $filters, $weekStart, $weekEnd);

            // ✅ AMBIL OPSI FILTER UNTUK DROPDOWN
            $filterOptions = $this->getFilterOptions($package);

            // ✅ CEK JIKA TIDAK ADA JADWAL SAMA SEKALI
            if ($allSchedules->isEmpty()) {
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

            // ✅ BUILD JSON DATA UNTUK CLIENT-SIDE FILTERING
            $dayOrder = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $schedulesJson = $allSchedules->map(function ($schedule) use ($weekStart, $dayOrder, $bookedScheduleIds) {
                $dayIdx = array_search($schedule->day, $dayOrder);
                $scheduleDate = $schedule->schedule_date
                    ? Carbon::parse($schedule->schedule_date)
                    : ($dayIdx !== false ? $weekStart->copy()->addDays($dayIdx) : null);

                $isBooked = in_array($schedule->id, $bookedScheduleIds ?? []);
                $capacity = $schedule->capacity ?? null;
                $bookedCount = $schedule->booked_count ?? null;
                $hasCapacity = !is_null($capacity) && !is_null($bookedCount);
                $remaining = $hasCapacity ? $capacity - $bookedCount : null;
                $isFull = $hasCapacity ? $remaining <= 0 : false;

                $classIcon = 'fa-dumbbell';
                $iconGradient = 'linear-gradient(135deg, #EE4E8B, #7A2B4A)';
                if ($schedule->classModel) {
                    $name = strtolower($schedule->classModel->class_name ?? '');
                    if (strpos($name, 'reformer pilates') !== false) { $classIcon = 'fa-dumbbell'; $iconGradient = 'linear-gradient(135deg, #1A7A5E, #0F766E)'; }
                    elseif (strpos($name, 'mat pilates') !== false) { $classIcon = 'fa-person-praying'; $iconGradient = 'linear-gradient(135deg, #1A7A5E, #059669)'; }
                    elseif (strpos($name, 'pilates') !== false) { $classIcon = 'fa-spa'; $iconGradient = 'linear-gradient(135deg, #1A7A5E, #1D5A4B)'; }
                    elseif (strpos($name, 'muaythai') !== false) { $classIcon = 'fa-hand-fist'; $iconGradient = 'linear-gradient(135deg, #7A2B4A, #EE4E8B)'; }
                    elseif (strpos($name, 'boxing') !== false) { $classIcon = 'fa-fire'; $iconGradient = 'linear-gradient(135deg, #7A2B4A, #EA580C)'; }
                    elseif (strpos($name, 'body shaping') !== false) { $classIcon = 'fa-heart-pulse'; $iconGradient = 'linear-gradient(135deg, #EC4899, #E11D48)'; }
                    elseif (strpos($name, 'mix') !== false) { $classIcon = 'fa-layer-group'; $iconGradient = 'linear-gradient(135deg, #8B5CF6, #7C3AED)'; }
                    elseif (strpos($name, 'yoga') !== false) { $classIcon = 'fa-person-praying'; $iconGradient = 'linear-gradient(135deg, #1A7A5E, #1D5A4B)'; }
                    elseif (strpos($name, 'private') !== false) { $classIcon = 'fa-crown'; $iconGradient = 'linear-gradient(135deg, #F59E0B, #CA8A04)'; }
                    elseif (strpos($name, 'single') !== false || strpos($name, 'visit') !== false) { $classIcon = 'fa-ticket'; $iconGradient = 'linear-gradient(135deg, #3B82F6, #4F46E5)'; }
                    elseif (strpos($name, 'exclusive') !== false) { $classIcon = 'fa-gem'; $iconGradient = 'linear-gradient(135deg, #06B6D4, #2563EB)'; }
                }

                return [
                    'id' => $schedule->id,
                    'className' => $schedule->className ?? 'Class',
                    'day' => $schedule->day,
                    'dayIdx' => $dayIdx !== false ? $dayIdx : -1,
                    'classTime' => $schedule->class_time,
                    'instructor' => $schedule->instructor,
                    'scheduleDate' => $scheduleDate ? $scheduleDate->format('Y-m-d') : null,
                    'displayDate' => $scheduleDate ? $scheduleDate->format('d M Y') : '-',
                    'capacity' => $capacity,
                    'bookedCount' => $bookedCount,
                    'isBooked' => $isBooked,
                    'isFull' => $isFull,
                    'hasCapacity' => $hasCapacity,
                    'remaining' => $remaining,
                    'classIcon' => $classIcon,
                    'iconGradient' => $iconGradient,
                    'level' => $schedule->classModel->level ?? null,
                    'classModelName' => $schedule->classModel->class_name ?? $schedule->schedule_label ?? 'Class',
                ];
            })->values();

            Log::info('📅 Schedules loaded successfully for JS filtering', [
                'customer_id' => $customer->id,
                'order_id' => $selectedOrder->id,
                'package_name' => $package->name,
                'total_schedules' => $allSchedules->count(),
                'classes_remaining' => $selectedOrder->remaining_classes,
                'quota_remaining' => $selectedOrder->remaining_quota,
            ]);

            return view('member.book-class', [
                'customer' => $customer,
                'schedulesJson' => $schedulesJson,
                'bookedScheduleIds' => $bookedScheduleIds,
                'activeOrders' => $activeOrders,
                'selectedOrderId' => $selectedOrderId,
                'selectedPackage' => $package,
                'selectedDate' => $selectedDate->format('Y-m-d'),
                'remainingClasses' => $selectedOrder->remaining_classes ?? 0,
                'remainingQuota' => $selectedOrder->remaining_quota ?? 0,
                'filters' => $filters,
                'filterOptions' => $filterOptions,
                'weekStart' => $weekStart,
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
                return $this->bookingResponse($request, false, 'Silakan login ulang');
            }

            // ✅ AMBIL SCHEDULE
            $schedule = Schedule::find($validated['schedule_id']);

            if (!$schedule) {
                return $this->bookingResponse($request, false, 'Jadwal tidak ditemukan');
            }

            // ✅ CARI ORDER YANG VALID (SEBELUM CEK CLASSES)
            $validOrder = $this->findValidOrderForSchedule($customer, $schedule);

            if (!$validOrder) {
                Log::warning('⚠️ No valid order for schedule', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $schedule->id,
                    'schedule_packages' => $schedule->packages->pluck('id')->toArray(),
                ]);

                return $this->bookingResponse($request, false, 'Anda tidak memiliki paket yang sesuai untuk jadwal ini.');
            }

            // ✅ CEK CLASSES REMAINING (dari order yang valid, untuk booking)
            if ($validOrder->remaining_classes <= 0) {
                Log::warning('⚠️ Customer classes remaining exhausted', [
                    'customer_id' => $customer->id,
                    'order_id' => $validOrder->id,
                    'remaining_classes' => $validOrder->remaining_classes,
                ]);

                return $this->bookingResponse($request, false, 'Classes remaining kamu sudah habis. Tidak bisa booking lagi');
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

                return $this->bookingResponse($request, false, 'Kamu sudah booking kelas ini');
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

                // 🎫 Decrement CLASSES REMAINING (untuk booking)
                // ❌ JANGAN decrement quota (itu hanya untuk check-in)
                $validOrder->decrement('remaining_classes');

                // 📅 AKTIVASI MASA AKTIF SAAT BOOKING PERTAMA
                // Jika expired_at masih NULL dan package punya duration_days → mulai hitung masa aktif
                if (!$validOrder->expired_at && $validOrder->package && $validOrder->package->duration_days) {
                    $expiredAt = Carbon::now()->addDays($validOrder->package->duration_days);
                    $validOrder->update(['expired_at' => $expiredAt]);

                    // Update juga quota_expired_at di Customer
                    $customer->update([
                        'quota_expired_at' => $expiredAt,
                    ]);

                    Log::info('📅 Masa aktif dimulai dari booking pertama', [
                        'customer_id' => $customer->id,
                        'order_id' => $validOrder->id,
                        'package' => $validOrder->package->name,
                        'duration_days' => $validOrder->package->duration_days,
                        'expired_at' => $expiredAt->format('Y-m-d H:i:s'),
                    ]);
                }

                $validOrder->refresh();

                Log::info('✅ Booking successful', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $validated['schedule_id'],
                    'order_id' => $validOrder->id,
                    'package' => $validOrder->package->name,
                    'day' => $schedule->day,
                    'time' => $schedule->class_time,
                    'classes_remaining' => $validOrder->remaining_classes,
                    'quota_remaining' => $validOrder->remaining_quota,
                ]);
            });

            // 📱 Kirim notifikasi WhatsApp setelah booking berhasil
            $this->sendMemberBookingNotification($customer, $validOrder);

            return $this->bookingResponse($request, true, 'Class berhasil dibooking!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('❌ Error booking class', [
                'customer_id' => Auth::guard('customer')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->bookingResponse($request, false, 'Gagal melakukan booking. Silakan coba lagi.');
        }
    }

    /**
     * Return JSON for AJAX requests, or redirect with flash for normal requests.
     */
    private function bookingResponse(Request $request, bool $success, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

        if ($success) {
            return back()->with('success', $message);
        }

        return back()->with('error', $message);
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
            ->whereIn('status', ['active', 'paid', 'settlement', 'success'])
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
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
    private function getSchedulesForPackage($customer, $order, $package, $filters = [], $weekStart = null, $weekEnd = null)
    {
        if ($package->is_exclusive) {
            return $this->getExclusiveSchedules($customer, $order);
        }

        return $this->getRegularSchedules($package, $filters, $weekStart, $weekEnd);
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
     * ✅ UPDATED: Get schedules for regular package - SUPPORT MULTI-PACKAGE SCHEDULES
     * Menampilkan SEMUA jadwal yang terhubung ke package (tidak filter visibility)
     * Mendukung filter: date, time, class_type, instructor
     */
    private function getRegularSchedules($package, $filters = [], $weekStart = null, $weekEnd = null)
    {
        // ✅ Query schedules dari gabungan package terpilih + seluruh package dalam group
        $schedulePackageIds = collect([$package->id])
            ->merge($this->getSchedulePackageIds($package))
            ->unique()
            ->values()
            ->all();

        $query = Schedule::whereHas('packages', function ($query) use ($schedulePackageIds) {
            $query->whereIn('packages.id', $schedulePackageIds);
        })
        ->with(['classModel', 'packages']);

        // ✅ FILTER BY DAY (dari SELECT DATE)
        if (!empty($filters['day'])) {
            $query->where('day', $filters['day']);
        }

        // ✅ FILTER BY TIME RANGE
        if (!empty($filters['time'])) {
            switch ($filters['time']) {
                case 'morning':
                    $query->whereTime('class_time', '>=', '00:00:00')
                          ->whereTime('class_time', '<', '12:00:00');
                    break;
                case 'afternoon':
                    $query->whereTime('class_time', '>=', '12:00:00')
                          ->whereTime('class_time', '<', '17:00:00');
                    break;
                case 'evening':
                    $query->whereTime('class_time', '>=', '17:00:00')
                          ->whereTime('class_time', '<=', '23:59:00');
                    break;
            }
        }

        // ✅ FILTER BY CLASS TYPE (via classModel relationship)
        if (!empty($filters['class_type'])) {
            $query->whereHas('classModel', function ($q) use ($filters) {
                $q->where('class_name', $filters['class_type']);
            });
        }

        // ✅ FILTER BY INSTRUCTOR
        if (!empty($filters['instructor'])) {
            $query->where('instructor', $filters['instructor']);
        }

        $schedules = $query->orderBy('schedule_date')
            ->orderBy('class_time')
            ->get();

        // ✅ Attach classModel data manually untuk display jika tidak ada
        $schedules = $schedules->map(function ($schedule) {
            if (!$schedule->classModel && $schedule->class_id) {
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

        Log::info('✅ Loaded regular package schedules with filters', [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'schedule_package_ids' => $schedulePackageIds,
            'count' => $schedules->count(),
            'filters' => $filters,
        ]);

        return $schedules;
    }

    /**
     * ✅ NEW: Determine which package_ids to query for schedules
     */
    private function getSchedulePackageIds($package)
    {
        $groupName = $this->resolvePackageGroupName($package);

        if (!$groupName) {
            return [$package->id];
        }

        $patterns = self::PACKAGE_GROUP_PATTERNS[$groupName] ?? [];

        $groupPackageIds = Package::query()
            ->where(function ($query) use ($patterns) {
                foreach ($patterns as $pattern) {
                    $query->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($pattern) . '%']);
                }
            })
            ->pluck('id')
            ->values()
            ->all();

        if (!empty($groupPackageIds)) {
            Log::info('📦 Package is part of dynamic group', [
                'package_id' => $package->id,
                'group' => $groupName,
                'group_package_ids' => $groupPackageIds,
            ]);

            return $groupPackageIds;
        }

        return [$package->id];
    }

    /**
     * Resolve package group name using package title patterns.
     */
    private function resolvePackageGroupName($package): ?string
    {
        $packageName = strtolower((string) ($package->name ?? ''));

        if ($packageName === '') {
            return null;
        }

        foreach (self::PACKAGE_GROUP_PATTERNS as $groupName => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($packageName, strtolower($pattern))) {
                    return $groupName;
                }
            }
        }

        return null;
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
     * Get available filter options for dropdowns
     */
    private function getFilterOptions($package)
    {
        $schedulePackageIds = collect([$package->id])
            ->merge($this->getSchedulePackageIds($package))
            ->unique()
            ->values()
            ->all();

        $classTypes = Schedule::whereHas('packages', function ($query) use ($schedulePackageIds) {
            $query->whereIn('packages.id', $schedulePackageIds);
        })
        ->whereHas('classModel')
        ->with('classModel')
        ->get()
        ->pluck('classModel.class_name')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

        $instructors = Schedule::whereHas('packages', function ($query) use ($schedulePackageIds) {
            $query->whereIn('packages.id', $schedulePackageIds);
        })
        ->whereNotNull('instructor')
        ->distinct()
        ->pluck('instructor')
        ->filter()
        ->values()
        ->toArray();

        return [
            'class_types' => $classTypes,
            'instructors' => $instructors,
            'time_slots' => [
                'morning' => 'Morning (00:00 - 11:59)',
                'afternoon' => 'Afternoon (12:00 - 16:59)',
                'evening' => 'Evening (17:00 - 23:59)',
            ],
        ];
    }

    /**
     * ✅ UPDATED: Find valid order for schedule - SUPPORT MULTI-PACKAGE SCHEDULES
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
            // ✅ UNTUK PAKET REGULAR - check via packages pivot table
            else {
                // Cek apakah schedule memiliki relasi dengan package ini via pivot
                $packageConnected = $schedule->packages()
                    ->where('packages.id', $package->id)
                    ->exists();
                
                if ($packageConnected) {
                    Log::info('✅ Found matching order via packages pivot', [
                        'order_id' => $order->id,
                        'package_id' => $package->id,
                        'schedule_id' => $schedule->id,
                    ]);
                    return $order;
                }
                
                // ✅ Fallback: Cek apakah package bagian dari group
                $schedulePackageIds = $this->getSchedulePackageIds($package);
                foreach ($schedulePackageIds as $schedulePackageId) {
                    $groupConnected = $schedule->packages()
                        ->where('packages.id', $schedulePackageId)
                        ->exists();
                    
                    if ($groupConnected) {
                        Log::info('✅ Found matching order via package group', [
                            'order_id' => $order->id,
                            'package_id' => $package->id,
                            'schedule_package_id' => $schedulePackageId,
                            'schedule_id' => $schedule->id,
                        ]);
                        return $order;
                    }
                }
            }
        }

        Log::warning('⚠️ No matching order found', [
            'customer_id' => $customer->id,
            'schedule_id' => $schedule->id,
            'schedule_packages' => $schedule->packages->pluck('id')->toArray(),
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
            'schedulesJson' => collect(),
            'bookedScheduleIds' => [],
            'activeOrders' => collect(),
            'selectedOrderId' => null,
            'selectedPackage' => null,
            'selectedDate' => now()->format('Y-m-d'),
            'remainingClasses' => 0,
            'remainingQuota' => 0,
            'filters' => [],
            'filterOptions' => ['class_types' => [], 'instructors' => [], 'time_slots' => []],
            'weekStart' => now()->startOfWeek(),
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
            'schedulesJson' => collect(),
            'bookedScheduleIds' => [],
            'activeOrders' => $activeOrders,
            'selectedOrderId' => $selectedOrderId,
            'selectedPackage' => $package,
            'selectedDate' => now()->format('Y-m-d'),
            'remainingClasses' => 0,
            'remainingQuota' => 0,
            'filters' => [],
            'filterOptions' => ['class_types' => [], 'instructors' => [], 'time_slots' => []],
            'weekStart' => now()->startOfWeek(),
        ])->with('error', $errorMessage);
    }

    /**
     * Send WhatsApp notification untuk member yang baru booking (dari halaman book class)
     * 
     * @param $customer
     * @param Order $order
     */
    private function sendMemberBookingNotification($customer, Order $order): void
    {
        try {
            if (!$customer->phone_number) {
                Log::warning('⚠️ Customer has no phone number for booking notification', [
                    'customer_id' => $customer->id,
                ]);
                return;
            }

            // Ambil semua schedule yang di-assign untuk order ini
            $customerSchedules = CustomerSchedule::where('order_id', $order->id)
                ->with(['schedule.classModel'])
                ->get();

            if ($customerSchedules->isEmpty()) {
                Log::warning('⚠️ No schedules found for booking notification', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                ]);
                return;
            }

            // Build array dari semua jadwal untuk dikirim ke WhatsApp
            $schedulesList = [];
            foreach ($customerSchedules as $cs) {
                $schedule = $cs->schedule;
                $class = $schedule->classModel;
                
                $schedulesList[] = [
                    'day' => ucfirst($schedule->day ?? 'N/A'),
                    'time' => $schedule->class_time ? Carbon::parse($schedule->class_time)->format('H:i') : '-',
                    'class_name' => $class->class_name ?? 'Class',
                    'level' => $class->level ?? '',
                    'instructor' => $schedule->instructor ?? 'Instructor',
                ];
            }

            $whatsapp = new WhatsAppService();
            
            $result = $whatsapp->sendBookingConfirmationNotification(
                $customer->phone_number,
                [
                    'customer_name' => $customer->name,
                    'package_name' => $order->package->name ?? 'Package',
                    'schedules' => $schedulesList,
                    'total_schedules' => count($schedulesList),
                ]
            );

            if ($result['success']) {
                Log::info('✅ Member booking WhatsApp notification sent', [
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'phone' => $customer->phone_number,
                    'schedules' => count($schedulesList),
                ]);
            } else {
                Log::warning('⚠️ Failed to send member booking notification', [
                    'customer_id' => $customer->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('❌ Member booking notification error: ' . $e->getMessage(), [
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}