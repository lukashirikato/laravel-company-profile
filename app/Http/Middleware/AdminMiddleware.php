<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Izinkan admin & owner (owner memiliki semua hak admin).
     *
     * @param  string  $guard  guard auth, default "web" (panel Filament)
     */
    public function handle(Request $request, Closure $next, string $guard = 'web'): Response
    {
        $user = Auth::guard($guard)->user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin atau owner yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
