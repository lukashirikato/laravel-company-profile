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
 * - Time window validation (class_time sampai +30 menit)
 * - Auto-detect booking berdasarkan time window
 * - Auto-checkout setelah 60 menit
 * - Manual checkout sebelum 60 menit
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
     * 4. Auto-detect booking based on time window (class_time sampai +30 menit)
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
            if ((int) $order->remaining_quota <= 0) {
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
                ->with(['schedule.classModel', 'order.package'])
                ->get();

            if ($todayBookings->isEmpty()) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Tidak ada booking kelas hari ini. Booking kelas terlebih dahulu.',
                    'type' => 'no_booking_today',
                ];
            }

            // ── STEP 5: Auto-Detect Booking Based on Time Window ─────────
            $validBooking = null;
            foreach ($todayBookings as $booking) {
                if ($booking->schedule->isWithinTimeWindow()) {
                    $validBooking = $booking;
                    break;
                }
            }

            if (!$validBooking && $todayBookings->count() > 1) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bukan waktu check-in untuk kelas manapun. Time window kelas sudah tutup (>30 menit dari jam mulai).',
                    'type' => 'outside_time_window',
                    'bookings' => $todayBookings->map(fn($b) => [
                        'id' => $b->schedule_id,
                        'name' => $b->schedule->classModel->name ?? 'Kelas',
                        'time' => $b->schedule->class_time?->format('H:i') ?? '-',
                    ])->toArray(),
                ];
            }

            if (!$validBooking) {
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
                // Sudah check-in, belum checkout
                $elapsedMinutes = (int) Carbon::now()->diffInMinutes($existingAttendance->check_in_at);

                if ($elapsedMinutes >= 60) {
                    // Sudah lewat 60 menit, lakukan auto-checkout
                    $existingAttendance->performAutoCheckout();
                    $existingAttendance->refresh();

                    DB::commit();

                    return [
                        'success' => true,
                        'type' => 'auto_checkout_performed',
                        'message' => 'Auto-checkout selesai karena sudah 60 menit latihan.',
                        'data' => [
                            'member_id' => $customer->id,
                            'member_name' => $customer->name,
                            'class_name' => $schedule->classModel->name ?? 'Kelas',
                            'check_in_time' => $existingAttendance->check_in_at->format('H:i'),
                            'auto_checkout_time' => $existingAttendance->auto_checkout_at->format('H:i'),
                            'duration' => 60,
                            'status' => 'auto_checkout',
                        ],
                    ];
                } else {
                    // Masih dalam 60 menit, tampilkan info sudah check-in
                    DB::commit();

                    return [
                        'success' => true,
                        'type' => 'already_checked_in',
                        'message' => 'Member sudah check-in, belum check-out.',
                        'data' => [
                            'member_id' => $customer->id,
                            'member_name' => $customer->name,
                            'class_name' => $schedule->classModel->name ?? 'Kelas',
                            'check_in_time' => $existingAttendance->check_in_at->format('H:i'),
                            'elapsed_minutes' => $elapsedMinutes,
                            'auto_checkout_in' => (60 - $elapsedMinutes),
                            'status' => 'active',
                        ],
                    ];
                }
            }

            // ── STEP 8: Create New Attendance ────────────────────────────
            $className = $schedule->classModel->name 
                ?? $schedule->classModel->class_name 
                ?? $order->package->name 
                ?? 'General Fitness';

            $autoCheckoutAt = Carbon::now()->addMinutes(60);

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
                'quota_deducted' => 1,
            ]);

            // ── STEP 9: Deduct Quota ────────────────────────────────────
            $order->decrement('remaining_quota');
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
                    'schedule_time' => $schedule->class_time?->format('H:i') ?? '-',
                    'auto_checkout_time' => $autoCheckoutAt->format('H:i'),
                    'package_name' => $order->package->name,
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
     * Process Manual Check-Out sebelum 60 menit
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

            $elapsedMinutes = (int) Carbon::now()->diffInMinutes($attendance->check_in_at);

            return [
                'success' => true,
                'data' => [
                    'attendance_id' => $attendance->id,
                    'check_in_time' => $attendance->check_in_at->format('H:i'),
                    'elapsed_minutes' => $elapsedMinutes,
                    'auto_checkout_in' => max(0, 60 - $elapsedMinutes),
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
}
