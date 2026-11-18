<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\CustomerSchedule;
use App\Models\Attendance;
use App\Models\InstructorRating;
use App\Models\Order;
use App\Services\AdminNotificationService;
use App\Services\BookingCancellationService;
use App\Services\WhatsAppService;
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
            // Cek apakah jadwal sudah benar-benar selesai (lewat jam akhir kelas)
            $isPast = false;
            $classEndAt = $item->schedule->getClassEndTime();

            if ($classEndAt) {
                $isPast = $classEndAt->isPast();
            } else {
                $scheduleDate = $item->schedule->schedule_date ? Carbon::parse($item->schedule->schedule_date)->toDateString() : null;
                $classTime = $item->schedule->class_time ? Carbon::parse($item->schedule->class_time)->format('H:i:s') : '23:59:59';

                if ($scheduleDate) {
                    if ($scheduleDate < $today) {
                        $isPast = true;
                    } elseif ($scheduleDate === $today && $classTime < $currentTime) {
                        $isPast = true;
                    }
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

        // o. Lampirkan rating yang sudah pernah dikirim member (per jadwal)
        $ratingsBySchedule = InstructorRating::where('customer_id', $customer->id)
            ->get()
            ->keyBy('schedule_id');

        // o. Lampirkan status kehadiran (check-in / check-out) per jadwal.
        // "Selesai" hanya jika member benar-benar sudah check-out.
        $attendancesBySchedule = Attendance::where('customer_id', $customer->id)
            ->whereNotNull('schedule_id')
            ->get()
            ->groupBy('schedule_id');

        $processedSchedules->each(function ($item) use ($ratingsBySchedule, $attendancesBySchedule) {
            $item->user_rating = $ratingsBySchedule->get($item->schedule_id);

            $records = $attendancesBySchedule->get($item->schedule_id, collect());
            $item->has_checked_in = $records->contains(fn ($a) => $a->check_in_at !== null);
            $item->has_checked_out = $records->contains(fn ($a) => $a->check_out_at !== null);
            $item->is_completed = $item->has_checked_out;

            // Check-out menandakan sesi selesai, walau jam akhir kelas belum lewat.
            if ($item->has_checked_out) {
                $item->is_past = true;
            }
        });

        // ✅ Status pembatalan mandiri per booking (kelas mendatang saja)
        $cancellation = app(BookingCancellationService::class);
        $cutoffHours = $cancellation->cutoffHours();

        $processedSchedules->each(function ($item) use ($cancellation, $cutoffHours) {
            $state = $cancellation->evaluate($item, $cutoffHours);
            $item->can_cancel = $state['can_cancel'];
            $item->cancel_deadline = $state['deadline'];
            $item->cancel_block_reason = $state['reason'];
            $item->is_exclusive_booking = $state['is_exclusive'];
            $item->cancel_cutoff_hours = $cutoffHours;
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
            'cancelCutoffHours' => $cutoffHours,
        ]);
    }

    /**
     * Batalkan booking kelas milik member yang sedang login.
     */
    public function cancel(Request $request, CustomerSchedule $customerSchedule)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer || (int) $customerSchedule->customer_id !== (int) $customer->id) {
            abort(403, 'Anda tidak berhak membatalkan booking ini.');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $respond = function (bool $success, string $message, array $extra = []) use ($request) {
            if ($request->expectsJson()) {
                return response()->json(array_merge([
                    'success' => $success,
                    'message' => $message,
                ], $extra), $success ? 200 : 422);
            }

            return $success
                ? back()->with('success', $message)
                : back()->with('error', $message);
        };

        try {
            $result = app(\App\Services\BookingCancellationService::class)->cancel(
                $customerSchedule,
                $validated['reason'] ?? null
            );
        } catch (ValidationException $e) {
            return $respond(false, collect($e->errors())->flatten()->first() ?: 'Booking tidak dapat dibatalkan.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            abort($e->getStatusCode(), $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Failed to cancel booking', [
                'customer_id' => $customer->id,
                'customer_schedule_id' => $customerSchedule->id,
                'error' => $e->getMessage(),
            ]);

            return $respond(false, 'Gagal membatalkan booking. Silakan coba lagi.');
        }

        /** @var CustomerSchedule $booking */
        $booking = $result['booking'];
        $order = $result['order'];

        $this->sendCancellationNotifications($customer, $booking, $order, $result['credit_returned']);

        $message = $result['credit_returned']
            ? 'Booking dibatalkan. 1 kredit kelas dikembalikan ke paket Anda.'
            : 'Booking dibatalkan.' . ($result['is_exclusive'] ? ' Sesi exclusive tidak dikembalikan.' : '');

        return $respond(true, $message, [
            'status' => 'cancelled',
            'remaining_classes' => $result['remaining_classes'],
            'credit_returned' => $result['credit_returned'],
        ]);
    }

    /**
     * Kirim WhatsApp ke member dan notifikasi ke admin setelah pembatalan.
     */
    private function sendCancellationNotifications($customer, CustomerSchedule $booking, ?Order $order, bool $creditReturned): void
    {
        $schedule = $booking->schedule;
        $className = $schedule?->classModel?->class_name ?? $schedule?->schedule_label ?? 'Class';
        $date = $schedule?->schedule_date ? Carbon::parse($schedule->schedule_date)->format('d M Y') : '-';
        $time = $schedule?->class_time ? Carbon::parse($schedule->class_time)->format('H:i') : '-';
        $packageName = $order?->package?->name ?? 'Package';

        try {
            if ($customer->phone_number) {
                $whatsapp = new WhatsAppService();
                $whatsapp->sendBookingCancellationNotification($customer->phone_number, [
                    'customer_name' => $customer->name,
                    'package_name' => $packageName,
                    'class_name' => $className,
                    'day' => $schedule?->day ?? '-',
                    'date' => $date,
                    'time' => $time,
                    'credit_returned' => $creditReturned,
                    'remaining_classes' => $order?->remaining_classes,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send cancellation WhatsApp notification', [
                'customer_id' => $customer->id,
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        AdminNotificationService::notify(
            'booking_cancelled',
            'Booking kelas dibatalkan',
            sprintf(
                '%s membatalkan %s (%s %s) - paket %s.%s',
                $customer->name,
                $className,
                $date,
                $time,
                $packageName,
                $creditReturned ? ' Kredit kelas dikembalikan.' : ''
            ),
            [
                'data' => [
                    'customer_id' => $customer->id,
                    'customer_schedule_id' => $booking->id,
                    'schedule_id' => $booking->schedule_id,
                    'order_id' => $booking->order_id,
                    'reason' => $booking->cancellation_reason,
                ],
                'related_id' => $booking->id,
                'related_type' => CustomerSchedule::class,
            ]
        );
    }
}