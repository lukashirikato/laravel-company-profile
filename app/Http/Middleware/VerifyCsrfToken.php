<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exclude Midtrans notification callback so external POSTs are accepted
        'midtrans/notification',

        // Exclude member login temporarily for Ngrok testing
        '/member/login',
    ];
}
