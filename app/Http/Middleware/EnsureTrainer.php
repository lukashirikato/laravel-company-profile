<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrainer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('trainer')->user();

        if (! $user) {
            return redirect()->guest(route('trainer.login'));
        }

        if (! $user->isTrainer()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk akun pelatih.');
        }

        return $next($request);
    }
}
