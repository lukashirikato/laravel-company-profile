<?php

namespace App\Http\Controllers;

use App\Models\CustomerSchedule;
use App\Models\Schedule;
use App\Models\Order;
use App\Models\ClassModel;
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

    // ✅ MAPPING PACKAGE GROUP - Packages yang share jadwal yang sama
    private const PACKAGE_GROUPS = [
        // Reformer Pilates: Single Visit (Single/Double/Triple) + Reformer Pilates Package (1 Visit, 4/15, 4/30, 8/30, 8/60)
        'reformer_pilates' => [
            'package_ids' => [2, 3, 4, 8, 9, 10, 11, 12], // Single Visit + Reformer Pilates Packages
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
                'classes_remaining' => $selectedOrder->remaining_classes,
                'quota_remaining' => $customer->quota,
            ]);

            return view('member.book-class', [
                'customer' => $customer,
                'schedules' => $groupedSchedules,
                'bookedScheduleIds' => $bookedScheduleIds,
                'activeOrders' => $activeOrders,
                'selectedOrderId' => $selectedOrderId,
                'selectedPackage' => $package,
                'remainingClasses' => $selectedOrder->remaining_classes ?? 0,
                'remainingQuota' => $customer->quota,
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

            // ✅ AMBIL SCHEDULE
            $schedule = Schedule::find($validated['schedule_id']);

            if (!$schedule) {
                return back()->with('error', 'Jadwal tidak ditemukan');
            }

            // ✅ CARI ORDER YANG VALID (SEBELUM CEK CLASSES)
            $validOrder = $this->findValidOrderForSchedule($customer, $schedule);

            if (!$validOrder) {
                Log::warning('⚠️ No valid order for schedule', [
                    'customer_id' => $customer->id,
                    'schedule_id' => $schedule->id,
                    'schedule_packages' => $schedule->packages->pluck('id')->toArray(),
                ]);

                return back()->with('error', 'Anda tidak memiliki paket yang sesuai untuk jadwal ini.');
            }

            // ✅ CEK CLASSES REMAINING (dari order yang valid, untuk booking)
            if ($validOrder->remaining_classes <= 0) {
                Log::warning('⚠️ Customer classes remaining exhausted', [
                    'customer_id' => $customer->id,
                    'order_id' => $validOrder->id,
                    'remaining_classes' => $validOrder->remaining_classes,
                ]);

                return back()->with('error', 'Classes remaining kamu sudah habis. Tidak bisa booking lagi');
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
                    'quota_remaining' => $customer->quota,
                ]);
            });

            // 📱 Kirim notifikasi WhatsApp setelah booking berhasil
            $this->sendMemberBookingNotification($customer, $validOrder);

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
     */
    private function getRegularSchedules($package)
    {
        // ✅ 1. Query schedules yang terhubung ke package ini via pivot table packages_schedules
        // TIDAK filter show_on_landing - tampilkan semua jadwal
        $schedules = Schedule::whereHas('packages', function ($query) use ($package) {
            $query->where('package_id', $package->id);
        })
        ->with(['classModel', 'packages'])
        ->orderBy('schedule_date')
        ->orderBy('class_time')
        ->get();

        // ✅ 2. Fallback: jika paket bagian dari group, query dari grouped packages
        if ($schedules->isEmpty()) {
            $schedulePackageIds = $this->getSchedulePackageIds($package);
            
            $schedules = Schedule::whereHas('packages', function ($query) use ($schedulePackageIds) {
                $query->whereIn('package_id', $schedulePackageIds);
            })
            ->with(['classModel', 'packages'])
            ->orderBy('schedule_date')
            ->orderBy('class_time')
            ->get();
        }

        // ✅ 3. Attach classModel data manually untuk display jika tidak ada
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

        Log::info('✅ Loaded regular package schedules', [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'count' => $schedules->count(),
            'note' => 'All schedules including hidden ones (tidak filter show_on_landing)',
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
                    ->where('package_id', $package->id)
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
                        ->where('package_id', $schedulePackageId)
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
                    'order_id' => $order->id,
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