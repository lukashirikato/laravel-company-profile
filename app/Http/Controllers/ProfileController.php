<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Customer;
use App\Models\CustomerSchedule;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil customer beserta transaksi, presensi, dan jadwal.
     * ✅ FIXED: Sum semua active orders untuk support multiple package purchases
     */
    public function show(): View|RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect('/login')->withErrors('Silakan login terlebih dahulu.');
        }

        // Ambil semua transaksi customer
        $transactions = $customer->transactions()->latest()->get() ?? collect();

        // Ambil semua presensi customer
        $attendances = $customer->attendances()->latest()->get() ?? collect();

        // Ambil semua jadwal customer
        $schedules = $customer->schedules()->latest()->get() ?? collect();

        // ✅ GET ALL ACTIVE ORDERS - support multiple package purchases
        // Sum semua remaining_quota dan remaining_classes dari semua active orders
        $activeOrders = $customer->orders()
            ->whereIn('status', ['paid', 'active', 'settlement', 'success'])
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->get();

        // ✅ CALCULATE TOTALS FROM ALL ACTIVE ORDERS
        $remainingQuota = 0;
        $remainingClasses = 0;
        $totalQuota = 0;
        $totalClasses = 0;
        
        foreach ($activeOrders as $order) {
            $remainingQuota += (int) ($order->remaining_quota ?? 0);
            $remainingClasses += (int) ($order->remaining_classes ?? 0);
            $totalQuota += (int) ($order->total_quota ?? $order->package?->quota ?? 0);
            $totalClasses += (int) ($order->total_classes ?? $order->package?->quota ?? 0);
        }
        
        \Illuminate\Support\Facades\Log::info('📊 Dashboard quota calculation', [
            'customer_id' => $customer->id,
            'active_orders_count' => $activeOrders->count(),
            'total_remaining_quota' => $remainingQuota,
            'total_remaining_classes' => $remainingClasses,
            'orders' => $activeOrders->map(fn($o) => [
                'order_id' => $o->id,
                'order_code' => $o->order_code,
                'package' => $o->package?->name,
                'remaining_quota' => $o->remaining_quota,
                'remaining_classes' => $o->remaining_classes,
            ])->toArray(),
        ]);

        // Get most recent order for display purposes
        $activeOrder = $activeOrders->first();

        // ✅ GET NEXT UPCOMING CLASS from CustomerSchedule
        $nextClass = CustomerSchedule::with(['schedule.classModel', 'order.package'])
            ->where('customer_schedules.customer_id', $customer->id)
            ->where('customer_schedules.status', 'confirmed')
            ->join('schedules', 'schedules.id', '=', 'customer_schedules.schedule_id')
            ->where(function ($q) {
                $q->where('schedules.schedule_date', '>=', now()->toDateString())
                  ->orWhereNull('schedules.schedule_date');
            })
            ->select('customer_schedules.*')
            ->orderByRaw("COALESCE(schedules.schedule_date, '9999-12-31') ASC")
            ->orderBy('schedules.class_time')
            ->first();

        return view('member.profile-modal', compact(
            'customer',
            'transactions',
            'attendances',
            'schedules',
            'remainingQuota',
            'remainingClasses',
            'totalQuota',
            'totalClasses',
            'activeOrders',
            'activeOrder',
            'nextClass'
        ));
    }

    /**
     * Tampilkan form edit profil customer yang sedang login.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect('/login')->withErrors('Silakan login terlebih dahulu.');
        }

        return view('profile.edit', compact('customer'));
    }

    /**
     * Update informasi profil customer.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect('/login')->withErrors('Silakan login terlebih dahulu.');
        }

        $customer->fill($request->validated());

        // Reset verifikasi email jika email berubah
        if ($customer->isDirty('email')) {
            $customer->email_verified_at = null;
        }

        $customer->save();

        return Redirect::route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password customer yang sedang login.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect('/login')->withErrors('Silakan login terlebih dahulu.');
        }

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $customer->password = bcrypt($request->new_password);
        $customer->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Hapus akun customer yang sedang login.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required'],
        ]);

        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect('/login')->withErrors('Silakan login terlebih dahulu.');
        }

        // Pastikan password benar sebelum hapus akun
        if (!Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai.']);
        }

        Auth::guard('customer')->logout();

        $customer->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Akun berhasil dihapus.');
    }
}