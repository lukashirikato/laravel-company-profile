<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Attendance;
use App\Models\CustomerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QRScannerController extends Controller
{
    /**
     * Show QR scanner page
     */
    public function index()
    {
        return view('qr-scanner.index');
    }

    /**
     * Process scanned QR code
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scan(Request $request)
    {
        try {
            $validated = $request->validate([
                'qr_data' => 'required|string',
            ]);

            $qrData = $validated['qr_data'];

            Log::info('🔍 QR scan attempt', [
                'qr_data' => substr($qrData, 0, 20) . '...',
                'timestamp' => now(),
            ]);

            // ✅ VALIDASI 1: QR Token valid?
            $customer = Customer::findByQRToken($qrData);

            if (!$customer) {
                Log::warning('❌ Invalid QR token', ['qr_data' => $qrData]);
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau sudah kadaluarsa',
                    'type' => 'error',
                ], 400);
            }

            Log::info('✅ QR token valid', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
            ]);

            // ✅ VALIDASI 2: Paket member masih aktif?
            if (!$customer->isPackageActive()) {
                Log::warning('❌ Package expired', [
                    'customer_id' => $customer->id,
                    'expired_at' => $customer->package_expired_at,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Paket membership Anda sudah expired. Silakan perpanjang',
                    'type' => 'expired_package',
                ]);
            }

            // ✅ VALIDASI 3: Quota tersedia?
            if ($customer->quota <= 0) {
                Log::warning('❌ Quota exhausted', [
                    'customer_id' => $customer->id,
                    'quota' => $customer->quota,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Quota kelas habis. Silakan upgrade paket',
                    'type' => 'no_quota',
                ]);
            }

            // ✅ VALIDASI 4: Ada booking hari ini?
            $todaySchedules = CustomerSchedule::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->with('schedule')
                ->get()
                ->filter(function ($cs) {
                    return $this->isScheduleToday($cs->schedule);
                });

            if ($todaySchedules->isEmpty()) {
                Log::warning('❌ No booking today', [
                    'customer_id' => $customer->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki booking kelas hari ini',
                    'type' => 'no_booking',
                ]);
            }

            // ✅ VALIDASI 5: Belum check-in?
            $existingCheckin = Attendance::where('customer_id', $customer->id)
                ->today()
                ->whereNotNull('check_in_at')
                ->first();

            if ($existingCheckin) {
                Log::warning('❌ Already checked in today', [
                    'customer_id' => $customer->id,
                    'check_in_at' => $existingCheckin->check_in_at,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-in hari ini pada ' . $existingCheckin->check_in_at->format('H:i'),
                    'type' => 'already_checkin',
                ]);
            }

            // ✅ VALIDASI PASSED! Create attendance
            $attendance = $this->createAttendance($customer, $todaySchedules->first());

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat attendance record',
                    'type' => 'error',
                ], 500);
            }

            // Kurangi quota
            $customer->decrement('quota');

            Log::info('✅ CHECK-IN SUCCESSFUL', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'attendance_id' => $attendance->id,
                'schedule_id' => $attendance->schedule_id,
                'quota_remaining' => $customer->quota - 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Selamat datang ' . $customer->name . '! Check-in berhasil',
                'type' => 'success',
                'data' => [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'class_name' => $attendance->schedule->classModel->class_name ?? 'Class',
                    'check_in_time' => $attendance->check_in_at->format('H:i:s'),
                    'quota_remaining' => $customer->quota,
                    'attendance_id' => $attendance->id,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('❌ QR Scanner Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi',
                'type' => 'error',
            ], 500);
        }
    }

    /**
     * Check out (end of class)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOut(Request $request)
    {
        try {
            $validated = $request->validate([
                'attendance_id' => 'required|exists:attendances,id',
            ]);

            $attendance = Attendance::findOrFail($validated['attendance_id']);

            if ($attendance->check_out_at !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-out sebelumnya',
                    'type' => 'already_checkout',
                ]);
            }

            $attendance->update([
                'check_out_at' => now(),
                'attendance_status' => 'present',
            ]);

            Log::info('✅ CHECK-OUT SUCCESSFUL', [
                'customer_id' => $attendance->customer_id,
                'attendance_id' => $attendance->id,
                'duration' => $attendance->getFormattedDuration(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil. Terima kasih telah berolahraga!',
                'type' => 'success',
                'data' => [
                    'duration' => $attendance->getFormattedDuration(),
                    'check_out_time' => $attendance->check_out_at->format('H:i:s'),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Check-out Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal check-out',
                'type' => 'error',
            ], 500);
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Check if schedule is today
     * 
     * @param $schedule
     * @return bool
     */
    private function isScheduleToday($schedule): bool
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $today = Carbon::now();
        $todayName = $dayNames[$today->dayOfWeek];

        return strtolower($schedule->day) === strtolower($todayName);
    }

    /**
     * Create attendance record
     * 
     * @param Customer $customer
     * @param CustomerSchedule $customerSchedule
     * @return Attendance|null
     */
    private function createAttendance(Customer $customer, CustomerSchedule $customerSchedule): ?Attendance
    {
        try {
            return Attendance::create([
                'customer_id' => $customer->id,
                'schedule_id' => $customerSchedule->schedule_id,
                'program' => $customer->program,
                'check_in_at' => now(),
                'check_in_type' => 'qr',
                'attendance_status' => 'present',
                'quota_deducted' => 1,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create attendance: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get active attendance (untuk checkout button)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveAttendance()
    {
        $today = Carbon::today();
        $activeAttendances = Attendance::whereDate('check_in_at', $today)
            ->whereNull('check_out_at')
            ->with(['customer', 'schedule.classModel'])
            ->orderByDesc('check_in_at')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'customer_name' => $attendance->customer->name,
                    'class_name' => $attendance->schedule->classModel->class_name ?? 'Class',
                    'check_in_time' => $attendance->check_in_at->format('H:i:s'),
                    'duration' => now()->diffInMinutes($attendance->check_in_at),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activeAttendances,
        ]);
    }
}
