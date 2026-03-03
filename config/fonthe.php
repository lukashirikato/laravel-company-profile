<?php

return [
    'api_token' => env('FONTTE_API_TOKEN', env('FONNTE_API_TOKEN', '')),
    'api_url' => env('FONTTE_API_URL', env('FONNTE_API_URL', 'https://api.fonnte.com/send')),
    'enabled' => env('FONTTE_ENABLE_NOTIFICATIONS', env('FONNTE_ENABLE_NOTIFICATIONS', false)),
    'sender_number' => env('WHATSAPP_SENDER_NUMBER', ''),

    // Rate limiting
    'rate_limit' => [
        'max_per_minute' => 5,
        'max_per_hour' => 50,
    ],

    // Message templates
    'templates' => [
        'payment_success' => 'Pembayaran Anda sebesar Rp{amount} untuk paket {package_name} telah berhasil diproses. Terimakasih telah menjadi bagian dari FTM Society!',
        'booking_confirmation' => 'Booking Anda untuk kelas {class_name} pada {date} pukul {time} telah dikonfirmasi. Sampai jumpa di kelas!',
        'class_reminder' => 'Pengingat: Kelas {class_name} dimulai dalam {hours} jam. Jangan lupa untuk hadir tepat waktu!',
        'check_in_success' => 'Halo {customer_name}, Anda telah berhasil melakukan check-in pada {check_in_time}. Quota tersisa: {remaining_quota}/{total_quota}. Mari berlatih dengan semangat!',
    ],
];
