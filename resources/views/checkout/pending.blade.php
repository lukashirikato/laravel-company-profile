@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-8">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- LEFT SIDE: STATUS & INSTRUCTIONS --}}
        <div class="p-10 flex flex-col justify-center items-center text-center bg-yellow-50">

            {{-- Status Icon --}}
            <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Heading --}}
            <h2 class="text-2xl font-bold text-gray-800 mt-6">
                Payment Pending
            </h2>

            <p class="text-gray-500 mt-3 max-w-sm">
                Your order has been created. Please complete your payment to get access to the program.
            </p>

            {{-- PAYMENT INSTRUCTIONS - DENGAN FALLBACK UNTUK PAYMENT_TYPE NULL --}}
            @php
                // Cek payment_type, jika NULL atau kosong, anggap bank_transfer sebagai default
                $paymentType = strtolower($order->payment_type ?? '');
                $showInstructions = ($paymentType == 'bank_transfer') || 
                                   ($paymentType == '') || 
                                   ($paymentType == 'null') ||
                                   ($order->status == 'pending');
            @endphp

            @if($showInstructions)
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 w-full max-w-sm text-left">
                    
                    {{-- Instruction Header --}}
                    <h4 class="font-semibold text-gray-800 text-sm mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-blue-600" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payment Instructions:
                    </h4>

                    {{-- Instruction Steps --}}
                    <ol class="text-sm text-gray-700 space-y-2 list-decimal list-inside">
                        <li>Transfer to our bank account below</li>
                        <li>Transfer <strong class="text-gray-900">exactly Rp{{ number_format($order->amount, 0, ',', '.') }}</strong></li>
                        <li>Screenshot your transfer receipt</li>
                        <li class="font-semibold text-gray-900">Send proof to admin via WhatsApp</li>
                    </ol>

                    {{-- Bank Account Info --}}
                    <div class="mt-3 bg-white rounded-lg p-3 border border-blue-200">
                        <p class="text-xs text-gray-600 mb-1">Transfer to:</p>
                        <p class="text-sm font-bold text-gray-900">BCA - 1234567890</p>
                        <p class="text-sm text-gray-700">a.n. GYM FITNESS CENTER</p>
                    </div>

                    {{-- WhatsApp Confirmation Button --}}
                    @php
                        $whatsappNumber = '6287785767395'; // GANTI DENGAN NOMOR ADMIN
                        $packageName = $order->package->name ?? 'Package';
                        $whatsappMessage = "Halo Admin, saya sudah melakukan pembayaran:\n\n" .
                                         "Order Code: {$order->order_code}\n" .
                                         "Package: {$packageName}\n" .
                                         "Amount: Rp" . number_format($order->amount, 0, ',', '.') . "\n\n" .
                                         "Mohon segera diverifikasi. Terima kasih!";
                    @endphp

                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" 
                       target="_blank"
                       class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white text-center py-2.5 px-4 rounded-lg font-semibold transition flex items-center justify-center shadow-sm">
                        
                        {{-- WhatsApp Icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                        </svg>
                        
                        Confirm Payment via WhatsApp
                    </a>

                    <p class="text-xs text-gray-500 mt-2 text-center">
                        Admin will verify within 1x24 hours
                    </p>
                </div>
            @endif

            
        </div>

        {{-- RIGHT SIDE: TRANSACTION SUMMARY --}}
        <div class="p-10">

            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                Transaction Summary
            </h3>

            <div class="space-y-4 text-sm">

                {{-- Order Code --}}
                <div class="flex justify-between items-start">
                    <span class="text-gray-500">Order Code</span>
                    <span class="font-medium text-gray-800 text-right">
                        {{ $order->order_code }}
                    </span>
                </div>

                {{-- Package Name --}}
                <div class="flex justify-between items-start">
                    <span class="text-gray-500">Package</span>
                    <span class="font-medium text-gray-800 text-right">
                        {{ $order->package->name ?? '-' }}
                    </span>
                </div>

                {{-- Original Price --}}
                <div class="flex justify-between items-start">
                    <span class="text-gray-500">Original Price</span>
                    <span class="font-medium text-gray-800">
                        Rp{{ number_format($order->amount + $order->discount, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Voucher Discount --}}
                @if($order->discount > 0)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-500">
                            Voucher
                            <span class="text-xs text-gray-400 block">
                                ({{ $order->voucher_code }})
                            </span>
                        </span>
                        <span class="font-medium text-red-600">
                            - Rp{{ number_format($order->discount, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                {{-- Payment Method --}}
                <div class="flex justify-between items-start">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-800">
                        @php
                            $displayPaymentType = $order->payment_type ?? 'BANK TRANSFER';
                            if (strtolower($displayPaymentType) == 'null' || empty($displayPaymentType)) {
                                $displayPaymentType = 'BANK TRANSFER';
                            }
                        @endphp
                        {{ strtoupper(str_replace('_', ' ', $displayPaymentType)) }}
                    </span>
                </div>

                {{-- Payment Status --}}
                <div class="flex justify-between items-start">
                    <span class="text-gray-500">Payment Status</span>
                    <span class="font-semibold text-yellow-600 capitalize" id="payment-status">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                {{-- Payment Deadline --}}
                @if($order->expired_at)
                    <div class="flex justify-between items-start">
                        <span class="text-gray-500">Payment Deadline</span>
                        <span class="font-medium text-red-600 text-right">
                            {{ \Carbon\Carbon::parse($order->expired_at)->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                @endif

            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 my-6"></div>

            {{-- Total Amount --}}
            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm font-medium">Total to Pay</span>
                <span class="text-2xl font-bold text-yellow-600">
                    Rp{{ number_format($order->amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

    </div>
</div>

{{-- AUTO CHECK PAYMENT STATUS --}}
<script>
(function() {
    let checkCount = 0;
    const maxChecks = 60; // Check for max 10 minutes (60 x 10 seconds)
    const statusElement = document.getElementById('status-check-info');
    const paymentStatusElement = document.getElementById('payment-status');

    function checkPaymentStatus() {
        if (checkCount >= maxChecks) {
            statusElement.textContent = 'Auto-check stopped. Refresh page manually.';
            statusElement.classList.remove('text-yellow-700');
            statusElement.classList.add('text-gray-500');
            return;
        }
        
        fetch("{{ route('checkout.status.get', $order->order_code) }}")
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                checkCount++;
                
                if (data.status === 'paid' || data.status === 'active') {
                    // Payment successful - redirect to success page
                    statusElement.textContent = 'Payment verified! Redirecting...';
                    statusElement.classList.remove('text-yellow-700');
                    statusElement.classList.add('text-green-700');
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('payment.success', $order->order_code) }}";
                    }, 1000);
                    
                } else if (data.status === 'failed' || data.status === 'expired' || data.status === 'cancelled') {
                    // Payment failed/expired/cancelled
                    paymentStatusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    statusElement.textContent = 'Payment ' + data.status + '. Please contact admin.';
                    statusElement.classList.remove('text-yellow-700');
                    statusElement.classList.add('text-red-700');
                    
                } else {
                    // Still pending - continue checking
                    statusElement.textContent = 'Auto-checking... (' + checkCount + '/' + maxChecks + ')';
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
                statusElement.textContent = 'Error checking status. Please refresh page.';
            });
    }

    // Initial check after 5 seconds
    setTimeout(checkPaymentStatus, 5000);

    // Check status every 10 seconds
    setInterval(checkPaymentStatus, 10000);
})();
</script>
@endsection