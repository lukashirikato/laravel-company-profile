<?php

namespace App\Http\Controllers\QRScan;

use App\Http\Controllers\Controller;

use App\Models\Attendance;
use App\Models\Order;
use App\Models\CustomerSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Enhanced QR Check-In/Check-Out Controller
 * 
 * Features:
 * - Time window validation (-60 menit sampai +30 menit)
 * - Auto-detect booking berdasarkan time window
 * - Auto-checkout mengikuti jam selesai kelas + 15 menit
 * - Manual checkout via scan ulang / tombol checkout
 * - Double check-in prevention per schedule per hari
 * - Quota management (dikurangi untuk semua packages)
 * - Transaction safety (DB rollback on error)
 */
class QRCheckInController extends Controller
{
    /**
     * Process QR Scan untuk Check-in
     * 
     * Flow:
     * 1. Validate member & order
     * 2. Check paket aktif & quota tersedia
     * 3. Find bookings hari ini
     * 4. Auto-detect booking based on time window (-60 menit sampai +30 menit)
     * 5. Check time window validity
     * 6. Prevent double check-in per schedule
     * 7. Create attendance & deduct quota
     * 
     * @param int $customerId
     * @return array
     */
    public function scanCheckIn(int $customerId): array
    {
        try {
            DB::beginTransaction();

            // ── STEP 1: Validate Member ─────────────────────────────────
            $order = Order::where('customer_id', $customerId)
                ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', Carbon::now());
                })
                ->latest()
                ->first();

            if (!$order) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Member ID tidak ditemukan atau paket tidak aktif',
                    'type' => 'member_not_found',
                ];
            }

            $customer = $order->customer;
            if (!$customer) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Data customer tidak ditemukan',
                    'type' => 'customer_not_found',
                ];
            }

            // ── STEP 2: Validate Paket ─────────────────────────────────
            if ($order->expired_at && $order->expired_at < Carbon::now()) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Paket sudah expired pada ' . $order->expired_at->format('d/m/Y H:i'),
                    'type' => 'package_expired',
                ];
            }

            // ── STEP 3: Validate Quota ─────────────────────────────────
            $isExclusive = (bool) ($order->package->is_exclusive ?? false);

            if (!$isExclusive && (int) $order->remaining_quota <= 0) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Quota paket habis. Perpanjang paket untuk melanjutkan.',
                    'type' => 'quota_exhausted',
                ];
            }

            // ── STEP 4: Find Bookings Hari Ini ─────────────────────────
            $todayBookings = CustomerSchedule::where('customer_id', $customer->id)
                ->whereHas('schedule', fn($q) => $q->whereDate('schedule_date', Carbon::now()->toDateString()))
                ->with(['schedule.classModel.instructor', 'order.package'])
                ->get();

            if ($todayBookings->isEmpty()) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Tidak ada booking kelas hari ini. Booking kelas terlebih dahulu.',
                    'type' => 'no_booking_today',
                ];
            }

            // ── STEP 5: Auto-detect booking in the current time window ────────
            if ($todayBookings->count() > 1) {
                // Filter out already checked-in schedules today
                $availableBookings = [];
                foreach ($todayBookings as $booking) {
                    $alreadyCheckedIn = Attendance::where('customer_id', $customer->id)
                        ->where('schedule_id', $booking->schedule_id)
                        ->whereDate('check_in_at', Carbon::now()->toDateString())
                        ->exists();
                    
                    if (!$alreadyCheckedIn) {
                        $classModel = $booking->schedule->classModel;
                        $availableBookings[] = [
                            'schedule_id' => $booking->schedule_id,
                            'class_name' => $classModel->name ?? $classModel->class_name ?? 'Kelas',
                            'class_time' => $this->safeFormatTime($booking->schedule->class_time),
                            'location' => $booking->schedule->location ?? 'FTM SOCIETY',
                            'instructor' => $classModel->instructor->name ?? '-',
                            'capacity' => $booking->schedule->capacity ?? 0,
                            'booked' => $booking->schedule->customerSchedules()->count(),
                            'is_within_window' => $booking->schedule->isWithinTimeWindow(),
                        ];
                    }
                }

                if (empty($availableBookings)) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Semua kelas hari ini sudah di-check-in.',
                        'type' => 'all_classes_checked_in',
                    ];
                }

                $bookingsInWindow = collect($availableBookings)
                    ->where('is_within_window', true)
                    ->values();

                if ($bookingsInWindow->count() === 1) {
                    $validBooking = $todayBookings->firstWhere('schedule_id', $bookingsInWindow->first()['schedule_id']);
                } elseif (count($availableBookings) > 1) {
                    DB::rollBack();
                    return [
                        'success' => true,
                        'type' => 'multiple_bookings_found',
                        'message' => $bookingsInWindow->count() > 1
                            ? 'Member memiliki beberapa kelas yang sedang dalam time window. Pilih kelas untuk check-in.'
                            : 'Member memiliki ' . count($availableBookings) . ' kelas hari ini. Pilih kelas untuk check-in.',
                        'data' => [
                            'member_id' => $customer->id,
                            'member_name' => $customer->name,
                            'bookings' => $availableBookings,
                        ],
                    ];
                } else {
                    // Only 1 available booking left
                    $validBooking = $todayBookings->firstWhere('schedule_id', $availableBookings[0]['schedule_id']);
                }
            } else {
                // Single booking - proceed directly
                $validBooking = $todayBookings->first();
            }

            $schedule = $validBooking->schedule;

            // ── STEP 6: Check Time Window ────────────────────────────────
            if (!$schedule->isWithinTimeWindow()) {
                DB::rollBack();
                $windowFormatted = $schedule->getTimeWindowFormatted();

                return [
                    'success' => false,
                    'message' => 'Di luar time window check-in. Window: ' . $windowFormatted,
                    'type' => 'outside_time_window',
                ];
            }

            // ── STEP 7: Check Existing Active Attendance ─────────────────
            $existingAttendance = Attendance::where('customer_id', $customer->id)
                ->where('schedule_id', $schedule->id)
                ->whereDate('check_in_at', Carbon::now()->toDateString())
                ->first();

            if ($existingAttendance && $existingAttendance->check_out_at === null) {
                $autoCheckoutAt = $existingAttendance->auto_checkout_at
                    ?? $schedule->getAutoCheckoutTime()
                    ?? $existingAttendance->check_in_at->copy()->addMinutes(75);

                if (Carbon::now()->greaterThanOrEqualTo($autoCheckoutAt)) {
                    $existingAttendance->performAutoCheckout();
                    $existingAttendance->refresh();

                    DB::commit();

                    return [
                        'success' => true,
                        'type' => 'auto_checkout_performed',
                        'message' => 'Auto-checkout selesai karena sesi sudah melewati batas checkout otomatis.',
                        'data' => [
                            'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                            'member_name' => $customer->name,
                            'class_name' => $schedule->classModel->name ?? 'Kelas',
                            'check_in_time' => $existingAttendance->check_in_at->format('H:i'),
                            'auto_checkout_time' => ($existingAttendance->check_out_at ?? $autoCheckoutAt)->format('H:i'),
                            'duration' => $existingAttendance->duration_minutes,
                            'status' => 'auto_checkout',
                        ],
                    ];
                } else {
                    $existingAttendance->performManualCheckout();
                    $existingAttendance->refresh();

                    DB::commit();

                    return [
                        'success' => true,
                        'type' => 'check_out_success',
                        'message' => 'Scan ulang terdeteksi sebagai check-out.',
                        'data' => [
                            'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                            'member_name' => $customer->name,
                            'class_name' => $schedule->classModel->name ?? 'Kelas',
                            'package_name' => $order->package->name ?? '-',
                            'program' => $existingAttendance->program ?? ($schedule->classModel->name ?? 'Kelas'),
                            'location' => $existingAttendance->location ?? '-',
                            'check_in_time' => $existingAttendance->check_in_at->format('H:i'),
                            'check_out_time' => $existingAttendance->check_out_at->format('H:i'),
                            'duration' => $existingAttendance->getFormattedDuration(),
                            'duration_minutes' => $existingAttendance->duration_minutes,
                            'remaining_quota' => $order->remaining_quota,
                            'total_quota' => $order->package->quota ?? 0,
                            'status' => 'checked_out',
                        ],
                    ];
                }
            }

            // ── STEP 8: Create New Attendance ────────────────────────────
            $className = $schedule->classModel->name 
                ?? $schedule->classModel->class_name 
                ?? $order->package->name 
                ?? 'General Fitness';

            $autoCheckoutAt = $schedule->getAutoCheckoutTime() ?? Carbon::now()->addMinutes(75);

            // ── Check if package is exclusive ────────────────────────────
            $quotaDeducted = !$isExclusive;

            $attendance = Attendance::create([
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'schedule_id' => $schedule->id,
                'program' => $className,
                'location' => 'FTM SOCIETY',
                'check_in_at' => Carbon::now(),
                'check_in_time' => Carbon::now(),
                'auto_checkout_at' => $autoCheckoutAt,
                'check_in_type' => 'qr',
                'attendance_status' => 'present',
                'quota_deducted' => $quotaDeducted,
            ]);

            // ✅ Ensure schedule_date and class_time are proper objects
            $scheduleDate = $schedule->schedule_date instanceof Carbon 
                ? $schedule->schedule_date 
                : Carbon::parse($schedule->schedule_date);
            
            $classTime = $schedule->class_time instanceof \DateTime
                ? $schedule->class_time
                : (is_string($schedule->class_time) ? Carbon::createFromFormat('H:i:s', $schedule->class_time) : $schedule->class_time);

            // ── STEP 9: Deduct Quota (only for non-exclusive packages) ────
            if (!$isExclusive) {
                $order->decrement('remaining_quota');
            }
            $order->refresh();

            DB::commit();

            Log::info('✅ QR Check-in successful', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'attendance_id' => $attendance->id,
                'schedule_id' => $schedule->id,
                'remaining_quota' => $order->remaining_quota,
            ]);

            return [
                'success' => true,
                'type' => 'check_in_success',
                'message' => 'Check-in berhasil! Silakan mulai latihan.',
                'data' => [
                    'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                    'member_name' => $customer->name,
                    'class_name' => $className,
                    'program' => $className,
                    'check_in_time' => $attendance->check_in_at->format('H:i'),
                    'check_in_date' => $attendance->check_in_at->format('d/m/Y'),
                    'schedule_time' => $classTime ? $classTime->format('H:i') : '-',
                    'auto_checkout_time' => $autoCheckoutAt->format('H:i'),
                    'package_name' => $order->package->name,
                    'is_exclusive' => $isExclusive,
                    'remaining_quota' => $order->remaining_quota,
                    'total_quota' => $order->package->quota ?? 0,
                    'attendance_id' => $attendance->id,
                ],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ QR Check-in Error', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses check-in',
                'type' => 'system_error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process manual check-out.
     * 
     * @param int $attendanceId
     * @return array
     */
    public function scanCheckOut(int $attendanceId): array
    {
        try {
            DB::beginTransaction();

            $attendance = Attendance::with(['order', 'customer', 'schedule.classModel'])
                ->find($attendanceId);

            if (!$attendance) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Attendance record tidak ditemukan',
                    'type' => 'attendance_not_found',
                ];
            }

            if ($attendance->check_out_at !== null) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Member sudah check-out sebelumnya pada ' . $attendance->check_out_at->format('H:i'),
                    'type' => 'already_checked_out',
                ];
            }

            // Perform manual checkout
            $attendance->performManualCheckout();
            $attendance->refresh();

            $customer = $attendance->customer;
            $order = $attendance->order;
            $schedule = $attendance->schedule;

            DB::commit();

            Log::info('✅ Manual Check-out successful', [
                'attendance_id' => $attendance->id,
                'customer_id' => $customer->id,
                'duration' => $attendance->duration_minutes,
            ]);

            return [
                'success' => true,
                'type' => 'check_out_success',
                'message' => 'Check-out berhasil!',
                'data' => [
                    'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                    'member_name' => $customer->name,
                    'package_name' => $order->package->name ?? '-',
                    'program' => $attendance->program ?? '-',
                    'location' => $attendance->location ?? '-',
                    'class_name' => $schedule->classModel->name ?? 'Kelas',
                    'check_in_time' => $attendance->check_in_at->format('H:i'),
                    'check_out_time' => $attendance->check_out_at->format('H:i'),
                    'duration' => $attendance->getFormattedDuration(),
                    'duration_minutes' => $attendance->duration_minutes,
                    'remaining_quota' => $order->remaining_quota,
                    'total_quota' => $order->package->quota ?? 0,
                ],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Manual Check-out Error', [
                'attendance_id' => $attendanceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses check-out',
                'type' => 'system_error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get info tentang attendance yang active hari ini
     * 
     * @param int $customerId
     * @return array
     */
    public function getActiveAttendance(int $customerId): array
    {
        try {
            $attendance = Attendance::where('customer_id', $customerId)
                ->whereDate('check_in_at', Carbon::now()->toDateString())
                ->whereNull('check_out_at')
                ->with(['schedule.classModel', 'order'])
                ->first();

            if (!$attendance) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada attendance aktif',
                    'data' => null,
                ];
            }

            $autoCheckoutIn = $attendance->auto_checkout_at
                ? max(0, Carbon::now()->diffInMinutes($attendance->auto_checkout_at, false) * -1)
                : 0;

            return [
                'success' => true,
                'data' => [
                    'attendance_id' => $attendance->id,
                    'check_in_time' => $attendance->check_in_at->format('H:i'),
                    'elapsed_minutes' => (int) Carbon::now()->diffInMinutes($attendance->check_in_at),
                    'auto_checkout_in' => $autoCheckoutIn,
                    'class_name' => $attendance->schedule->classModel->name ?? 'Kelas',
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching active attendance',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper: Safely format time that might be string or Carbon/DateTime
     * 
     * @param mixed $time
     * @param string $format
     * @return string
     */
    private function safeFormatTime($time, string $format = 'H:i'): string
    {
        try {
            if (!$time) {
                return '-';
            }

            if ($time instanceof \DateTime) {
                return $time->format($format);
            }

            if (is_string($time)) {
                return Carbon::createFromFormat('H:i:s', $time)->format($format);
            }

            return '-';
        } catch (\Exception) {
            return '-';
        }
    }
}
