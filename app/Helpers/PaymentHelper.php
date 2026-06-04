<?php

if (!function_exists('formatPaymentMethod')) {
    /**
     * Format payment method untuk display yang user-friendly
     * 
     * @param string|null $paymentType
     * @return string
     */
    function formatPaymentMethod($paymentType)
    {
        if (!$paymentType) {
            return 'Menunggu Pembayaran';
        }
        
        // Map Midtrans payment types ke format yang lebih readable
        $paymentMethodMap = [
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'bank_transfer' => 'Virtual Account',
            'bca_va' => 'Virtual Account BCA',
            'bni_va' => 'Virtual Account BNI',
            'bri_va' => 'Virtual Account BRI',
            'permata_va' => 'Virtual Account Permata',
            'other_va' => 'Virtual Account',
            'echannel' => 'Mandiri Bill Payment',
            'cstore' => 'Convenience Store',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
            'akulaku' => 'Akulaku',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
        ];
        
        // Return mapped value atau original value dengan capitalize
        return $paymentMethodMap[strtolower($paymentType)] ?? ucwords(str_replace('_', ' ', $paymentType));
    }
}