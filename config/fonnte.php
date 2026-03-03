<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration untuk integrasi Fonnte WhatsApp API
    |
    */

    'api_token' => env('FONNTE_API_TOKEN', env('FONTTE_API_TOKEN', '')),
    'api_url' => env('FONNTE_API_URL', env('FONTTE_API_URL', 'https://api.fonnte.com/send')),
    'enabled' => env('FONNTE_ENABLE_NOTIFICATIONS', env('FONTTE_ENABLE_NOTIFICATIONS', false)),
    'sender_number' => env('WHATSAPP_SENDER_NUMBER', ''),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    | Untuk menghindari rate limiting dan ban
    |
    */
    
    'rate_limiting' => [
        'max_messages_per_minute' => 5,
        'max_messages_per_hour' => 60,
        'delay_between_messages' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    */

    'templates' => [
        'payment_success' => 'Halo {customer_name}, pembayaran Anda untuk paket {package_name} telah berhasil!',
        'booking_confirmation' => 'Halo {customer_name}, booking kelas {class_name} Anda telah dikonfirmasi!',
        'class_reminder' => 'Reminder: Kelas {class_name} Anda besok jam {class_time}!',
    ],
];
