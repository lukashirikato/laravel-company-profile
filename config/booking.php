<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Batas Waktu Pembatalan Booking
    |--------------------------------------------------------------------------
    | Member hanya dapat membatalkan booking secara mandiri sampai X jam
    | sebelum kelas dimulai. Lewat batas ini, pembatalan harus melalui admin.
    */
    'cancellation_cutoff_hours' => (int) env('BOOKING_CANCEL_CUTOFF_HOURS', 4),
];
