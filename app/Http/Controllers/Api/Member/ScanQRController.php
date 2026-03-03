<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Attendance;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScanQRController extends Controller
{
    /**
     * Scan QR Code untuk Check-in
     * POST /api/member/scan-qr
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanQR(Request $request)
    {
        try {
            $request->validate([
                'qr_token' => 'required|string',  // Token dari QR code
                'program' => 'nullable|string',   // Program/class yang diambil dari scanner
                'location' => 'nullable|string',  // Lokasi check-in (optional)
            ]);

            DB::beginTransaction();

            // 1. Cari customer berdasarkan QR token
            $customer = Customer::where('qr_token', $request->qr_token)
                ->where('qr_active', true)
                ->firstOrFail();

            // 2. Cek apakah member sudah check-in hari ini
            $existingAttendance = Attendance::where('customer_id', $customer->id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if ($existingAttendance) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-in hari ini',
                    'data' => [
                        'checked_in_at' => $existingAttendance->created_at->format('H:i'),
                        'check_in_time' => $existingAttendance->created_at
                    ]
                ], 422);
            }

            // 3. Cari active order dengan remaining quota > 0
            $order = Order::where('customer_id', $customer->id)
                ->where('status', 'paid')
                ->where(function($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
                })
                ->where('remaining_quota', '>', 0)
                ->first();

            if (!$order) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada paket aktif dengan quota tersisa',
                    'data' => null
                ], 422);
            }

            // 4. Create attendance record
            $attendance = Attendance::create([
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'program' => $request->program ?? $order->package->name,
                'check_in_at' => now(),
                'check_in_type' => 'qr',
                'attendance_status' => 'present',
                'location' => $request->location ?? null,
            ]);

            // 5. Kurangi remaining quota
            $order->decrement('remaining_quota');
            $order->refresh();

            // 6. Get updated remaining quota
            $remainingQuota = $order->remaining_quota;
            $package = $order->package;

            DB::commit();

            // 7. Return success response dengan data
            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'data' => [
                    'member_id' => str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                    'member_name' => $customer->name,
                    'program' => $attendance->program,
                    'check_in_time' => $attendance->created_at->format('H:i'),
                    'check_in_date' => $attendance->created_at->format('d M Y'),
                    'package_name' => $package->name,
                    'remaining_quota' => $remainingQuota,
                    'total_quota' => $package->quota,
                    'quota_used_today' => $package->quota - $remainingQuota,
                    'status' => 'present',
                    'notification' => [
                        'title' => '✅ Check-in Berhasil',
                        'message' => "Selamat datang {$customer->name}! Quota tersisa: {$remainingQuota}",
                        'type' => 'success'
                    ]
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau sudah tidak aktif',
                'data' => null
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('ScanQR Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses check-in',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }

    /**
     * Get member quota info
     * GET /api/member/quota-info
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuotaInfo(Request $request)
    {
        try {
            $customer = auth('customer')->user();
            
            // Get all active orders with quota
            $orders = Order::where('customer_id', $customer->id)
                ->where('status', 'paid')
                ->where(function($q) {
                    $q->whereNull('expired_at')
                      ->orWhere('expired_at', '>', now());
                })
                ->with('package')
                ->get();

            $quotaData = $orders->map(function($order) {
                $package = $order->package;
                return [
                    'order_id' => $order->id,
                    'package_name' => $package->name,
                    'total_quota' => $package->quota ?? 0,
                    'remaining_quota' => $order->remaining_quota ?? 0,
                    'used_quota' => ($package->quota ?? 0) - ($order->remaining_quota ?? 0),
                    'usage_percentage' => $package->quota ? round((($package->quota - ($order->remaining_quota ?? 0)) / $package->quota) * 100) : 0,
                    'expired_at' => $order->expired_at?->format('d M Y'),
                    'is_active' => !$order->isExpired(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_active_packages' => $orders->count(),
                    'packages' => $quotaData,
                    'total_remaining_quota' => $quotaData->sum('remaining_quota'),
                    'total_used_quota' => $quotaData->sum('used_quota'),
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('GetQuotaInfo Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data quota',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get recent check-in status
     * GET /api/member/recent-checkin
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentCheckin()
    {
        try {
            $customer = auth('customer')->user();
            
            $recentAttendance = Attendance::where('customer_id', $customer->id)
                ->latest()
                ->first();

            if (!$recentAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada check-in',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'check_in_date' => $recentAttendance->created_at->format('d M Y'),
                    'check_in_time' => $recentAttendance->created_at->format('H:i'),
                    'program' => $recentAttendance->program,
                    'status' => $recentAttendance->attendance_status,
                    'checked_in_ago' => $recentAttendance->created_at->diffForHumans(),
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('RecentCheckin Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }
}
