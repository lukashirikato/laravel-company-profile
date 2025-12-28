<?php

return [

    // Midtrans Keys
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    // Mode Production / Sandbox
    'is_production' => filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),

    // Setting tambahan Midtrans
    'is_sanitized' => true,   // wajib TRUE
    'is_3ds' => true,          // untuk secure credit card (aman)

    // optional
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
];
