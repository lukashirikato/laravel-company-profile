<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\CustomerSchedule;
use App\Models\Schedule;
use App\Services\WhatsAppService;
use App\Http\Controllers\QRScan\QRCheckInController;
use Carbon\Carbon;

class QrScanner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'QR Scanner';
    protected static ?string $title = 'Member Check-in Scanner';
    protected static ?string $slug = 'member-check-in-scanner';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.qr-scanner';

    private WhatsAppService $whatsAppService;

    public ?string $qrToken = null;
    
    public ?string $selectedProgram = null;
    
    public ?string $selectedLocation = 'FTM SOCIETY';
    
    public array $scanResults = [];
    
    public array $todayStats = ['total' => 0, 'success' => 0, 'error' => 0];
    
    public array $recentScans = [];
    
    public ?string $errorMessage = null;

    public bool $isCheckOutMode = false;
    
    public array $checkOutResults = [];

    public array $todaySchedules = [];

    public ?int $selectedScheduleId = null;

    public bool $showScheduleSelector = false;

    public ?int $elapsedSeconds = null;

    public ?int $autoCheckoutInMinutes = null;

    public ?string $autoCheckoutMessage = null;

    public function __construct()
    {
        parent::__construct();
        $this->whatsAppService = new WhatsAppService();
    }

    public function mount(): void
    {
        $this->loadStats();
        $this->loadRecentScans();
    }

    public function submitScan(): void
    {
        $this->errorMessage = null;
        $this->scanResults = [];
        $this->showScheduleSelector = false;

        if (empty($this->qrToken)) {
            $this->errorMessage = 'Member ID harus diisi';
            return;
        }

        try {
            $token = trim($this->qrToken);
            $customerId = $this->resolveCustomerIdFromToken($token);

            if (!$customerId) {
                $this->errorMessage = 'Member ID, QR, atau kode order tidak valid';
                $this->qrToken = '';
                return;
            }

            // Gunakan enhanced QRCheckInController
            $controller = new QRCheckInController();
            $result = $controller->scanCheckIn($customerId);

            // Handle hasil scan
            if ($result['success']) {
                // ✅ SUCCESS CASES
                $data = $result['data'] ?? [];

                if ($result['type'] === 'multiple_bookings_found') {
                    // Member has multiple bookings today - show selector
                    $this->todaySchedules = $data['bookings'] ?? [];
                    $this->showScheduleSelector = true;

                    \Filament\Notifications\Notification::make()
                        ->title('ℹ️ Pilih Kelas')
                        ->body("{$data['member_name']} memiliki " . count($this->todaySchedules) . " kelas hari ini. Pilih kelas untuk check-in.")
                        ->info()
                        ->send();

                    return; // Don't clear qrToken yet, needed for confirmScheduleSelection

                } elseif ($result['type'] === 'check_in_success') {
                    // Normal check-in
                    $isExclusive = $data['is_exclusive'] ?? false;
                    $this->scanResults = [
                        'success' => true,
                        'member_name' => $data['member_name'] ?? '-',
                        'member_id' => $data['member_id'] ?? '-',
                        'program' => $data['program'] ?? '-',
                        'class_name' => $data['class_name'] ?? '-',
                        'package_name' => $data['package_name'] ?? '-',
                        'is_exclusive' => $isExclusive,
                        'total_quota' => $data['total_quota'] ?? 0,
                        'remaining_quota' => $data['remaining_quota'] ?? 0,
                        'check_in_time' => $data['check_in_time'] ?? '-',
                        'check_in_date' => $data['check_in_date'] ?? '-',
                        'schedule_time' => $data['schedule_time'] ?? '-',
                        'auto_checkout_time' => $data['auto_checkout_time'] ?? '-',
                        'status' => 'success',
                    ];

                    $notificationBody = $data['member_name'] . ' – ' . $data['class_name'];
                    if (!$isExclusive) {
                        $notificationBody .= ' – Quota: ' . $data['remaining_quota'];
                    } else {
                        $notificationBody .= ' (Exclusive)';
                    }

                    \Filament\Notifications\Notification::make()
                        ->title('✅ Check-in Berhasil!')
                        ->body($notificationBody)
                        ->success()
                        ->send();


                } elseif ($result['type'] === 'check_out_success') {
                    $this->checkOutResults = [
                        'success' => true,
                        'member_name' => $data['member_name'] ?? '-',
                        'member_id' => $data['member_id'] ?? '-',
                        'class_name' => $data['class_name'] ?? '-',
                        'package_name' => $data['package_name'] ?? '-',
                        'program' => $data['program'] ?? '-',
                        'check_in_time' => $data['check_in_time'] ?? '-',
                        'check_out_time' => $data['check_out_time'] ?? '-',
                        'duration' => $data['duration'] ?? '-',
                        'duration_minutes' => $data['duration_minutes'] ?? 0,
                        'remaining_quota' => $data['remaining_quota'] ?? 0,
                        'total_quota' => $data['total_quota'] ?? 0,
                    ];
                    $this->isCheckOutMode = true;

                    \Filament\Notifications\Notification::make()
                        ->title('✅ Check-out Berhasil!')
                        ->body("{$data['member_name']} - Durasi: {$data['duration']}")
                        ->success()
                        ->send();

                } elseif ($result['type'] === 'auto_checkout_performed') {
                    // Auto-checkout sudah dijalankan
                    $this->scanResults = [
                        'success' => true,
                        'member_name' => $data['member_name'] ?? '-',
                        'member_id' => $data['member_id'] ?? '-',
                        'class_name' => $data['class_name'] ?? '-',
                        'check_in_time' => $data['check_in_time'] ?? '-',
                        'auto_checkout_time' => $data['auto_checkout_time'] ?? '-',
                        'duration' => ($data['duration'] ?? 0) . ' menit',
                        'status' => 'auto_checkout',
                    ];

                    \Filament\Notifications\Notification::make()
                        ->title('⏱️ Auto-Checkout Selesai')
                        ->body("{$data['member_name']} – Sesi tercatat otomatis sampai {$data['auto_checkout_time']}")
                        ->success()
                        ->send();
                }

            } else {
                // ❌ ERROR CASES
                $this->errorMessage = $result['message'] ?? 'Terjadi kesalahan';

                \Filament\Notifications\Notification::make()
                    ->title('❌ ' . ($result['type'] ?? 'Error'))
                    ->body($this->errorMessage)
                    ->danger()
                    ->send();
            }

            $this->recordScan(null, $result['success'], $result['message'] ?? '');
            $this->qrToken = '';
            $this->loadStats();
            $this->loadRecentScans();

        } catch (\Exception $e) {
            $this->errorMessage = 'Error: ' . $e->getMessage();
            \Filament\Notifications\Notification::make()
                ->title('❌ Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
            $this->qrToken = '';
        }
    }

    /**
     * Staff picks a specific schedule from the multi-booking selector.
     */
    public function confirmScheduleSelection(int $scheduleId): void
    {
        $this->showScheduleSelector = false;
        $this->selectedScheduleId   = $scheduleId;

        try {
            $token = trim($this->qrToken);
            $customerId = $this->resolveCustomerIdFromToken($token);

            if (!$customerId) {
                $this->errorMessage = 'Member ID, QR, atau kode order tidak valid';
                $this->qrToken = '';
                return;
            }

            $order = Order::where(function ($q) use ($customerId, $token) {
                    $q->where('customer_id', $customerId)
                      ->orWhere('order_code', $token);
                })
                ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
                ->latest()
                ->first();

            if (!$order) {
                $this->errorMessage = 'Sesi berakhir. Silakan scan ulang.';
                $this->qrToken = '';
                return;
            }

            $customer = $order->customer;

            $booking = CustomerSchedule::where('customer_id', $customer->id)
                ->where('schedule_id', $scheduleId)
                ->with(['schedule.classModel', 'order.package'])
                ->first();

            if (!$booking) {
                $this->errorMessage = 'Booking tidak ditemukan untuk kelas yang dipilih.';
                $this->qrToken = '';
                return;
            }

            $this->processCheckIn($customer, $order, $booking);

        } catch (\Exception $e) {
            $this->errorMessage = 'Error: ' . $e->getMessage();
            \Filament\Notifications\Notification::make()
                ->title('❌ Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Core check-in logic — called after a booking has been selected.
     */
    private function processCheckIn($customer, $order, CustomerSchedule $booking): void
    {
        try {
            // ✅ Safe initialization
            $schedule    = $booking->schedule;
            $scheduleId  = $booking->schedule_id;
            $classStart  = null;
            
            // ✅ Safe check for package exclusivity with null coalescing
            $isExclusive = false;
            if ($booking->order && $booking->order->package) {
                $isExclusive = (bool) $booking->order->package->is_exclusive;
            } elseif ($order && $order->package) {
                $isExclusive = (bool) $order->package->is_exclusive;
            }

            // ── TIME WINDOW CHECK (±60 minutes from class start) ───────────
            if ($schedule && $schedule->class_time && $schedule->schedule_date) {
                try {
                    $classStart = Carbon::parse($schedule->schedule_date->format('Y-m-d') . ' ' . $schedule->class_time);
                    $windowStart = $classStart->copy()->subMinutes(60);
                    $windowEnd   = $classStart->copy()->addMinutes(30);

                    if (!now()->between($windowStart, $windowEnd)) {
                        $this->errorMessage = 'Di luar waktu check-in. ' .
                            'Kelas mulai ' . $classStart->format('H:i') . '. ' .
                            'Check-in dibuka ' . $windowStart->format('H:i') . ' – ' . $windowEnd->format('H:i') . '.';
                        $this->recordScan($order, false, 'Di luar waktu check-in');
                        $this->qrToken = '';
                        return;
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('⚠️ Time window check failed', [
                        'schedule_id' => $scheduleId,
                        'error' => $e->getMessage(),
                    ]);
                    // Continue without time window validation if parsing fails
                }
            }

            // ── DUPLICATE CHECK (per schedule per day) ─────────────────────
            $alreadyCheckedIn = Attendance::where('customer_id', $customer->id)
                ->where('schedule_id', $scheduleId)
                ->whereDate('check_in_at', today())
                ->exists();

            if ($alreadyCheckedIn) {
                $className = 'kelas ini';
                if ($schedule && $schedule->classModel) {
                    $className = $schedule->classModel->name ?? $schedule->classModel->class_name ?? $className;
                }
                $this->errorMessage = 'Member sudah check-in untuk ' . $className . ' hari ini.';
                $this->recordScan($order, false, 'Duplikat check-in per kelas');
                $this->qrToken = '';
                return;
            }

            // ── QUOTA CHECK (only for non-exclusive packages) ─────────────
            if (!$isExclusive && (int) $order->remaining_quota <= 0) {
                $this->errorMessage = 'Quota member habis. Perpanjang paket untuk check-in.';
                $this->recordScan($order, false, 'Quota habis');
                $this->qrToken = '';
                return;
            }

            // ── SUCCESS ────────────────────────────────────────────────────
            \DB::beginTransaction();
            
            // ✅ Safe class name extraction with multiple fallbacks
            $className = 'General Fitness';
            if ($schedule && $schedule->classModel) {
                $className = $schedule->classModel->name 
                    ?? $schedule->classModel->class_name 
                    ?? $className;
            } elseif ($order && $order->package && $order->package->name) {
                $className = $order->package->name;
            }

            $program = $this->selectedProgram ?: $className;

            Attendance::create([
                'order_id'          => $order->id,
                'customer_id'       => $customer->id,
                'schedule_id'       => $scheduleId,
                'check_in_time'     => now(),
                'check_in_at'       => now(),
                'program'           => $program,
                'location'          => $this->selectedLocation ?? 'FTM SOCIETY',
                'check_in_type'     => 'qr',
                'attendance_status' => 'present',
                'quota_deducted'    => !$isExclusive,
            ]);

            // ✅ Deduct quota only for non-exclusive packages
            if (!$isExclusive) {
                $order->decrement('remaining_quota');
                $order->refresh();
            }

            \DB::commit();

            $order->refresh();

            $this->scanResults = [
                'success'         => true,
                'member_name'     => $customer->name ?? 'Member',
                'member_id'       => $customer->id,
                'program'         => $program,
                'class_name'      => $className,
                'package_name'    => $order->package->name ?? 'Package',
                'is_exclusive'    => $isExclusive,
                'total_quota'     => $order->package->quota ?? 0,
                'remaining_quota' => $order->remaining_quota,
                'check_in_time'   => now()->format('H:i:s'),
                'check_in_date'   => now()->format('d/m/Y'),
                'schedule_time'   => $classStart ? $classStart->format('H:i') : '-',
                'status'          => 'success',
            ];

            $this->recordScan($order, true, 'Check-in berhasil - ' . $className);
            $this->sendCheckInWhatsAppNotification($order, $customer);

            \Filament\Notifications\Notification::make()
                ->title('✅ Check-in Berhasil!')
                ->body($customer->name . ' – ' . $className . ($isExclusive ? ' (Exclusive)' : ' – Quota: ' . $order->remaining_quota))
                ->success()
                ->send();

            \Illuminate\Support\Facades\Log::info('✅ Check-in successful', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'schedule_id' => $scheduleId,
                'class_name' => $className,
                'remaining_quota' => $order->remaining_quota,
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            
            // ✅ Enhanced error logging
            \Illuminate\Support\Facades\Log::error('❌ Check-in failed in processCheckIn', [
                'customer_id' => $customer->id ?? 'N/A',
                'order_id' => $order->id ?? 'N/A',
                'schedule_id' => $booking->schedule_id ?? 'N/A',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            $this->errorMessage = 'Error check-in: ' . $e->getMessage();
            
            \Filament\Notifications\Notification::make()
                ->title('❌ Error Check-in')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            // ✅ Always cleanup regardless of success or failure
            $this->qrToken = '';
            $this->selectedProgram    = null;
            $this->selectedScheduleId = null;
            $this->todaySchedules     = [];
            $this->loadStats();
            $this->loadRecentScans();
        }
    }

    /**
     * Submit check-out scan
     */
    public function submitCheckOut(): void
    {
        $this->errorMessage = null;
        $this->checkOutResults = [];

        if (empty($this->qrToken)) {
            $this->errorMessage = 'Member ID harus diisi';
            return;
        }

        try {
            $token = trim($this->qrToken);
            $customerId = $this->resolveCustomerIdFromToken($token);

            if (!$customerId) {
                $this->errorMessage = 'Member ID, QR, atau kode order tidak valid';
                $this->qrToken = '';
                return;
            }

            // Find active attendance
            $activeAttendance = Attendance::where('customer_id', $customerId)
                ->whereDate('check_in_at', today())
                ->whereNull('check_out_at')
                ->with(['order', 'customer', 'schedule.classModel'])
                ->first();

            if (!$activeAttendance) {
                $this->errorMessage = 'Member tidak memiliki check-in aktif hari ini atau sudah check-out';
                $this->qrToken = '';
                return;
            }

            // Gunakan QRCheckInController untuk manual checkout
            $controller = new QRCheckInController();
            $result = $controller->scanCheckOut($activeAttendance->id);

            if ($result['success']) {
                // ✅ SUCCESS - Manual checkout
                $data = $result['data'] ?? [];

                $this->checkOutResults = [
                    'success' => true,
                    'member_name' => $data['member_name'] ?? '-',
                    'member_id' => $data['member_id'] ?? '-',
                    'class_name' => $data['class_name'] ?? '-',
                    'package_name' => $data['package_name'] ?? '-',
                    'program' => $data['program'] ?? '-',
                    'check_in_time' => $data['check_in_time'] ?? '-',
                    'check_out_time' => $data['check_out_time'] ?? '-',
                    'duration' => $data['duration'] ?? '-',
                    'duration_minutes' => $data['duration_minutes'] ?? 0,
                    'remaining_quota' => $data['remaining_quota'] ?? 0,
                    'total_quota' => $data['total_quota'] ?? 0,
                ];

                \Filament\Notifications\Notification::make()
                    ->title('✅ Check-out Berhasil!')
                    ->body("{$data['member_name']} - Durasi: {$data['duration']}")
                    ->success()
                    ->send();

                // Send WhatsApp notification
                $this->sendCheckOutNotification($activeAttendance->order, $activeAttendance->customer, $activeAttendance, $data['duration']);

            } else {
                // ❌ ERROR
                $this->errorMessage = $result['message'] ?? 'Terjadi kesalahan';

                \Filament\Notifications\Notification::make()
                    ->title('❌ Error')
                    ->body($this->errorMessage)
                    ->danger()
                    ->send();
            }

            $this->recordScan($activeAttendance->order ?? null, $result['success'], $result['message'] ?? '');
            $this->qrToken = '';
            $this->loadStats();
            $this->loadRecentScans();

        } catch (\Exception $e) {
            $this->errorMessage = 'Error: ' . $e->getMessage();
            \Filament\Notifications\Notification::make()
                ->title('❌ Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
            $this->qrToken = '';
        }
    }

    /**
     * Send WhatsApp check-out notification to member
     */
    private function sendCheckOutNotification($order, $customer, $attendance, $formattedDuration): void
    {
        try {
            $checkOutData = [
                'customer_name' => $customer->name,
                'package_name' => $order->package->name ?? 'Package',
                'program' => $attendance->program ?? 'General',
                'location' => $attendance->location ?? 'FTM SOCIETY',
                'check_in_time' => $attendance->check_in_at->format('H:i'),
                'check_out_time' => now()->format('H:i'),
                'duration' => $formattedDuration,
                'remaining_quota' => $order->remaining_quota,
                'total_quota' => $order->package->quota ?? 0,
            ];

            $phoneNumber = $customer->phone_number;

            if (!empty($phoneNumber)) {
                $result = $this->whatsAppService->sendCheckOutNotification($phoneNumber, $checkOutData);

                if ($result['success'] ?? false) {
                    \Illuminate\Support\Facades\Log::info('✅ Check-out WhatsApp notification sent', [
                        'customer_id' => $customer->id,
                        'customer_name' => $customer->name,
                        'duration' => $formattedDuration,
                    ]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('⚠️ Check-out WhatsApp notification failed', [
                        'customer_id' => $customer->id,
                        'message' => $result['message'] ?? 'Unknown error',
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ Error sending check-out notification', [
                'error' => $e->getMessage(),
                'customer_id' => $order->customer_id ?? 'N/A',
            ]);
        }
    }

    private function resolveCustomerIdFromToken(string $token): ?int
    {
        $token = trim($token);

        if ($token === '') {
            return null;
        }

        if (is_numeric($token)) {
            return (int) $token;
        }

        if (str_contains($token, 'MEMBER_') && preg_match('/MEMBER_(\d+)_?/', $token, $matches)) {
            return (int) $matches[1];
        }

        $customer = Customer::where('qr_token', $token)
            ->where('qr_active', true)
            ->first();

        if ($customer) {
            return $customer->id;
        }

        $order = Order::where('order_code', $token)
            ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
            ->latest()
            ->first();

        return $order?->customer_id;
    }

    /**
     * Toggle between check-in and check-out mode
     */
    public function toggleCheckOutMode(): void
    {
        $this->isCheckOutMode       = !$this->isCheckOutMode;
        $this->qrToken              = '';
        $this->errorMessage         = null;
        $this->scanResults          = [];
        $this->checkOutResults      = [];
        $this->todaySchedules       = [];
        $this->selectedScheduleId   = null;
        $this->showScheduleSelector = false;
    }

    /**
     * Reset form
     */
    public function resetForm(): void
    {
        $this->qrToken              = '';
        $this->selectedProgram      = null;
        $this->selectedLocation     = 'FTM SOCIETY';
        $this->scanResults          = [];
        $this->checkOutResults      = [];
        $this->errorMessage         = null;
        $this->isCheckOutMode       = false;
        $this->todaySchedules       = [];
        $this->selectedScheduleId   = null;
        $this->showScheduleSelector = false;
    }

    /**
     * Send WhatsApp check-in notification to member
     */
    private function sendCheckInWhatsAppNotification($order, $customer): void
    {
        try {
            // Prepare check-in data for WhatsApp notification
            $checkInData = [
                'customer_name' => $customer->name,
                'package_name' => $order->package->name ?? 'Package',
                'program' => $this->selectedProgram ?? 'General',
                'location' => $this->selectedLocation,
                'check_in_time' => now()->format('H:i (d/m/Y)'),
                'remaining_quota' => $order->remaining_quota,
                'total_quota' => $order->package->quota ?? 0,
            ];

            // Get customer phone number
            $phoneNumber = $customer->phone_number;

            if (!empty($phoneNumber)) {
                // Send WhatsApp notification
                $result = $this->whatsAppService->sendCheckInNotification($phoneNumber, $checkInData);

                if ($result['success']) {
                    \Illuminate\Support\Facades\Log::info('✅ Check-in WhatsApp notification sent successfully', [
                        'customer_id' => $customer->id,
                        'customer_name' => $customer->name,
                        'phone' => $phoneNumber,
                    ]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('⚠️ Check-in WhatsApp notification failed', [
                        'customer_id' => $customer->id,
                        'message' => $result['message'] ?? 'Unknown error',
                    ]);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('⚠️ Customer phone number not found', [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('❌ Error sending check-in WhatsApp notification', [
                'error' => $e->getMessage(),
                'customer_id' => $order->customer_id ?? 'N/A',
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function recordScan($order, bool $success, string $message): void
    {
        $memberName = 'Unknown';
        if ($order && $order->customer) {
            $memberName = $order->customer->name;
        }

        $scan = [
            'time' => now()->format('H:i:s'),
            'member' => $memberName,
            'status' => $success ? 'success' : 'error',
            'message' => $message,
        ];

        array_unshift($this->recentScans, $scan);
        $this->recentScans = array_slice($this->recentScans, 0, 20);

        if ($success) {
            $this->todayStats['success']++;
        } else {
            $this->todayStats['error']++;
        }
        $this->todayStats['total']++;
    }

    private function loadStats(): void
    {
        $this->todayStats = [
            'total' => Attendance::whereDate('created_at', today())->count(),
            'success' => Attendance::whereDate('created_at', today())->where('attendance_status', 'present')->count(),
            'error' => 0,
        ];
    }

    private function loadRecentScans(): void
    {
        $scans = Attendance::with(['order.customer', 'order.package', 'schedule.classModel'])
            ->whereDate('created_at', today())
            ->latest()
            ->limit(20)
            ->get();

        $this->recentScans = $scans->map(fn($scan) => [
            'time'           => $scan->check_in_at?->format('H:i') ?? $scan->created_at->format('H:i'),
            'member'         => $scan->order?->customer->name ?? ($scan->customer?->name ?? 'Unknown'),
            'class_name'     => $scan->schedule?->classModel?->name
                ?? $scan->schedule?->classModel?->class_name
                ?? $scan->program
                ?? '-',
            'status'         => $scan->check_out_at ? 'completed' : 'active',
            'message'        => $scan->check_out_at
                ? '✓ Check-in & Check-out (' . $scan->getFormattedDuration() . ')'
                : '📥 Aktif (belum check-out)',
            'check_out_time' => $scan->check_out_at?->format('H:i') ?? '-',
            'duration'       => $scan->getFormattedDuration(),
            'quota_deducted' => $scan->quota_deducted,
        ])->toArray();
    }
}