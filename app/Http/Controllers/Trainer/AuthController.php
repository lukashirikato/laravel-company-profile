<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('trainer')->check() && Auth::guard('trainer')->user()?->isTrainer()) {
            return redirect()->route('trainer.dashboard');
        }

        return view('trainer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! $user->isTrainer()) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak memiliki akses portal pelatih.',
            ]);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        Auth::guard('trainer')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('trainer.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('trainer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trainer.login');
    }
}
