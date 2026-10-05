<?php

namespace App\Http\Middleware;

use Closure;
use App\Support\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware role dengan hierarki:
 *
 *   role:admin  -> boleh admin & owner  (owner naik level otomatis)
 *   role:owner  -> hanya owner
 *   role:user   -> semua role (user, admin, owner)
 *
 * Dipakai: ->middleware('role:owner')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        $userRole = UserRole::normalize($user->role);
        $allowed = [];

        foreach ($roles as $role) {
            $allowed[] = $role;

            // Hierarki: admin selalu mencakup owner
            if ($role === 'admin') {
                $allowed[] = 'owner';
            }
        }

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Akses ditolak. Role Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
