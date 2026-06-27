<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Customer;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil member beserta jadwal dan riwayat transaksi
     */
    public function show()
    {
        $authCustomer = Auth::guard('customer')->user();
        if (!$authCustomer instanceof Customer) {
            return Redirect::route('member.login');
        }

        /** @var Customer&Model $customer */
        $customer = $authCustomer;

        // Eager-load relasi yang dipakai view untuk mencegah N+1 query
        $customer->loadMissing(['schedules.classModel']);

        // Ambil semua jadwal (cached) + classModel untuk pemakaian di Blade
        $schedules = Cache::remember('member.profile.schedules.v1', 300, function () {
            return Schedule::with('classModel:id,class_name')
                ->select(['id', 'class_id', 'schedule_label', 'day', 'class_time', 'instructor'])
                ->get();
        });

        // Tidak dipakai di view saat ini, diset kosong agar request lebih ringan
        $transactions = [];

        // Tidak dipakai di view saat ini; tetap jaga kompatibilitas variabel
        $attendances = [];

        return View::make('member.profile', compact('customer', 'schedules', 'transactions', 'attendances'));
    }

    /**
     * Update informasi profil member
     */
    public function update(Request $request)
    {
        $authCustomer = Auth::guard('customer')->user();
        if (!$authCustomer instanceof Customer) {
            return Redirect::route('member.login');
        }

        /** @var Customer&Model $customer */
        $customer = $authCustomer;

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->getKey(),
        ]);

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];

        // Jika email berubah, reset verifikasi email
        if ($customer->isDirty('email')) {
            $customer->email_verified_at = null;
        }

        $customer->save();

        return Redirect::back()->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password member
     */
    public function changePassword(Request $request)
    {
        $authCustomer = Auth::guard('customer')->user();
        if (!$authCustomer instanceof Customer) {
            return Redirect::route('member.login');
        }

        /** @var Customer&Model $customer */
        $customer = $authCustomer;

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($validated['current_password'], $customer->getAuthPassword())) {
            return Redirect::back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $customer->password = Hash::make($validated['new_password']);
        $customer->save();

        return Redirect::back()->with('success', 'Password berhasil diubah.');
    }
}
