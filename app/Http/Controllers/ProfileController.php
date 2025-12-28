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

class ProfileController extends Controller
{
    /**
     * Tampilkan profil customer beserta transaksi, presensi, dan jadwal.
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

        return view('member.profile-modal', compact('customer', 'transactions', 'attendances', 'schedules'));
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
