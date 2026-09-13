<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

class CustomerAuthController extends Controller
{
    /**
     * Tampilkan form login untuk customer/member
     */
    public function showLoginForm()
    {
        return view('member.login');
    }

    /**
     * Proses login customer
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentukan field login (email atau no. HP)
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        // Siapkan kredensial login
        $credentials = [
            $field         => $request->login,
            'password'     => $request->password,
            'is_verified'  => 1,
        ];

        // Login dengan guard `customer`
        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('member.profile')->with('success', 'Login berhasil.');
        }

        // Jika gagal login, lakukan debug hash manual
        $customer = Customer::where($field, $request->login)->first();
        if ($customer) {
            if (!Hash::check($request->password, $customer->password)) {
                Log::warning("[LOGIN GAGAL] Password salah untuk {$field}: {$request->login}");
            }
        } else {
            Log::warning("[LOGIN GAGAL] Tidak ditemukan customer dengan {$field}: {$request->login}");
        }

        // Tampilkan error umum ke user
        return back()->withErrors([
            'login' => 'Login gagal. Periksa email/no. HP, password, dan pastikan akun sudah diverifikasi.',
        ])->withInput();
    }

    /**
     * Proses logout customer
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout sebagai member.');
    }
}
