<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomerPackageController extends Controller
{
    /**
     * Display customer's packages dashboard
     */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        Log::info('🔍 Customer Packages Index', [
            'customer_id' => $customerId,
        ]);

        // ✅ ACTIVE PACKAGES - Ambil dari tabel orders dengan status paid/active
        $activePackages = Order::with(['package', 'transaction'])
            ->where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'active', 'settlement', 'success']) // ✅ Multiple status
            ->where(function($query) {
                // Paket masih aktif jika:
                // 1. expired_at NULL (unlimited), ATAU
                // 2. expired_at masih di masa depan
                $query->whereNull('expired_at')
                      ->orWhere('expired_at', '>', Carbon::now());
            })
            ->orderByDesc('created_at')
            ->get();

        // ✅ PAST/EXPIRED PACKAGES
        $pastPackages = Order::with(['package', 'transaction'])
            ->where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'active', 'settlement', 'success', 'expired'])
            ->where(function($query) {
                // Paket expired jika:
                // expired_at sudah lewat
                $query->whereNotNull('expired_at')
                      ->where('expired_at', '<=', Carbon::now());
            })
            ->orderByDesc('expired_at')
            ->limit(10)
            ->get();

        Log::info('📦 Packages Found', [
            'active_count' => $activePackages->count(),
            'past_count' => $pastPackages->count(),
            'active_packages' => $activePackages->pluck('id')->toArray(),
            'past_packages' => $pastPackages->pluck('id')->toArray(),
        ]);

        return view('member.packages.index', [
            'activePackages' => $activePackages,
            'pastPackages' => $pastPackages
        ]);
    }

    /**
     * Show package detail
     * 
     * @param int $id Order ID
     */
    public function show($id)
    {
        try {
            $customerId = Auth::guard('customer')->id();
            
            // Load order dengan authorization check
            $order = Order::with(['package', 'transaction', 'customer'])
                ->where('id', $id)
                ->where('customer_id', $customerId)
                ->firstOrFail();
            
            // ✅ GUNAKAN METHOD DARI MODEL
            $isExpired = $order->isExpired();
            $remainingDays = $order->getRemainingDays();
            $remainingTime = $order->getRemainingTime();
            
            // Get session history
            $sessionHistory = $this->getSessionHistory($order->id);
            
            // Calculate progress
            $totalSessions = $order->package->quota ?? 0;
            $remainingSessions = $order->remaining_sessions ?? $totalSessions;
            $usedSessions = $totalSessions - $remainingSessions;
            $progressPercentage = $totalSessions > 0 
                ? round(($usedSessions / $totalSessions) * 100) 
                : 0;
            
            return view('member.packages.detail', compact(
                'order', 
                'isExpired', 
                'remainingDays',
                'remainingTime',
                'sessionHistory',
                'usedSessions',
                'progressPercentage'
            ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('member.packages.index')
                ->with('error', 'Paket tidak ditemukan atau Anda tidak memiliki akses.');
                
        } catch (\Exception $e) {
            Log::error('Error loading package detail', [
                'order_id' => $id,
                'customer_id' => Auth::guard('customer')->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('member.packages.index')
                ->with('error', 'Gagal memuat detail paket.');
        }
    }
    
    /**
     * ✅ API endpoint to get available packages untuk pembelian
     * For AJAX call from modal or button
     */
    public function availablePackages()
    {
        try {
            // Get all active packages yang bisa dibeli
            $packages = Package::where('status', 'active')
                ->orderBy('price', 'asc')
                ->get()
                ->map(function($package) {
                    return [
                        'id' => $package->id,
                        'slug' => $package->slug ?? null,
                        'name' => $package->name,
                        'description' => $package->description ?? '',
                        'price' => $package->price,
                        'price_formatted' => 'Rp ' . number_format($package->price, 0, ',', '.'),
                        'quota' => $package->quota ?? 0,
                        'duration_days' => $package->duration_days ?? 30,
                        'is_exclusive' => $package->is_exclusive ?? false,
                        'auto_apply' => $package->auto_apply ?? false,
                        'schedule_mode' => $package->schedule_mode ?? 'booking',
                        'features' => $package->features ?? [],
                    ];
                });
            
            Log::info('Available packages loaded', [
                'customer_id' => Auth::guard('customer')->id(),
                'packages_count' => $packages->count()
            ]);
            
            return response()->json([
                'success' => true,
                'packages' => $packages,
                'message' => 'Packages loaded successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading available packages', [
                'customer_id' => Auth::guard('customer')->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat paket. Silakan coba lagi.',
                'packages' => []
            ], 500);
        }
    }
    
    /**
     * Get session history for a package
     * 
     * @param int $orderId
     */
    private function getSessionHistory($orderId)
    {
        // TODO: Implement jika sudah ada tabel bookings/attendance
        // return Booking::where('order_id', $orderId)
        //     ->with('schedule')
        //     ->orderBy('booking_date', 'desc')
        //     ->get();
        
        return collect([]);
    }
    
    /**
     * Check if customer has any previous purchase
     * 
     * @return bool
     */
    public function hasPreviousPurchase()
    {
        $customerId = Auth::guard('customer')->id();
        
        $count = Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'success', 'settlement', 'active'])
            ->count();
        
        return $count > 0;
    }
    
    /**
     * Get customer's package statistics
     * 
     * @return array
     */
    public function getPackageStats()
    {
        $customerId = Auth::guard('customer')->id();
        
        // ✅ MENGGUNAKAN PROPER QUERY
        $activeCount = Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'success', 'settlement', 'active'])
            ->where(function($query) {
                $query->whereNull('expired_at')
                      ->orWhere('expired_at', '>', Carbon::now());
            })
            ->count();
        
        $totalSpent = Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'success', 'settlement', 'active'])
            ->sum('amount');
        
        $totalSessions = Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'success', 'settlement', 'active'])
            ->where(function($query) {
                $query->whereNull('expired_at')
                      ->orWhere('expired_at', '>', Carbon::now());
            })
            ->sum('remaining_sessions');
        
        return [
            'active_packages' => $activeCount,
            'total_spent' => $totalSpent,
            'remaining_sessions' => $totalSessions,
        ];
    }

    /**
     * ✅ Method untuk cek package akan expired dalam X hari
     * 
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExpiringSoon($days = 7)
    {
        $customerId = Auth::guard('customer')->id();
        
        return Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'success', 'settlement', 'active'])
            ->whereNotNull('expired_at')
            ->whereBetween('expired_at', [
                Carbon::now(),
                Carbon::now()->addDays($days)
            ])
            ->with('package')
            ->get();
    }

    /**
     * ✅ Extend package (perpanjang masa aktif)
     * 
     * @param int $orderId
     * @param int $additionalDays
     */
    public function extendPackage(Request $request, $orderId)
    {
        try {
            $customerId = Auth::guard('customer')->id();
            
            $order = Order::where('id', $orderId)
                ->where('customer_id', $customerId)
                ->firstOrFail();
            
            $additionalDays = $request->input('additional_days', 30);
            
            if ($order->expired_at) {
                // Jika sudah expired, extend dari sekarang
                if ($order->isExpired()) {
                    $newExpiredAt = Carbon::now()->addDays($additionalDays);
                } else {
                    // Jika belum expired, extend dari tanggal expired saat ini
                    $newExpiredAt = Carbon::parse($order->expired_at)->addDays($additionalDays);
                }
                
                $order->update(['expired_at' => $newExpiredAt]);
                
                Log::info('Package extended', [
                    'order_id' => $orderId,
                    'additional_days' => $additionalDays,
                    'new_expired_at' => $newExpiredAt
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => "Package berhasil diperpanjang {$additionalDays} hari",
                    'new_expired_at' => $newExpiredAt->format('d M Y')
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Package ini unlimited, tidak perlu diperpanjang'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Error extending package', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperpanjang package'
            ], 500);
        }
    }
}