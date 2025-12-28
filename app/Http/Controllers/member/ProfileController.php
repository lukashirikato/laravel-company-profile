<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil member beserta jadwal dan riwayat transaksi
     */
    public function show()
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        // Ambil semua jadwal
        $schedules = Schedule::all();

        // Ambil transaksi customer yang sedang login
        $transactions = $customer->transactions()->latest()->get() ?? collect();

        // Ambil presensi/attendances customer yang sedang login (cek dulu apakah tabel ada)
        $attendances = collect();
        if (Schema::hasTable('attendances')) {
            $attendances = $customer->attendances()->latest()->get() ?? collect();
        }

        return view('member.profile', compact('customer', 'schedules', 'transactions', 'attendances'));
    }

    /**
     * Update informasi profil member
     */
    public function update(Request $request)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->getKey(),
        ]);

        $customer->fill($request->only('name', 'email'));

        // Jika email berubah, reset verifikasi email
        if ($customer->isDirty('email')) {
            $customer->email_verified_at = null;
        }

        $customer->save();

        return redirect()->back()->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password member
     */
    public function changePassword(Request $request)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($request->current_password, $customer->getAuthPassword())) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
