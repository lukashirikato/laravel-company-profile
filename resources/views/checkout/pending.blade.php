@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream flex items-center justify-center px-4 py-8">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- LEFT SIDE: STATUS & INSTRUCTIONS --}}
        <div class="p-10 flex flex-col justify-center items-center text-center bg-grounded-green/20">

            {{-- Status Icon --}}
            <div class="w-20 h-20 rounded-full bg-grounded-green/40 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-springs-ivy"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Heading --}}
            <h2 class="text-2xl font-bold text-dark mt-6">
                Payment Pending
            </h2>

            <p class="text-cream0 mt-3 max-w-sm">
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
                <div class="mt-4 bg-light-pink/30 border border-primary/30 rounded-xl p-4 w-full max-w-sm text-left">
                    
                    {{-- Instruction Header --}}
                    <h4 class="font-semibold text-dark text-sm mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-primary" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Payment Instructions:
                    </h4>

                    <p class="text-sm text-dark">
                        Selesaikan pembayaran melalui metode yang Anda pilih pada jendela Midtrans.
                        Status pembayaran di halaman ini diperbarui otomatis.
                    </p>

                    <p id="status-check-info" class="text-xs text-cream0 mt-3">
                        Menunggu pembayaran...
                    </p>
                </div>
            @endif

            
        </div>

        {{-- RIGHT SIDE: TRANSACTION SUMMARY --}}
        <div class="p-10">

            <h3 class="text-lg font-semibold text-dark mb-6">
                Transaction Summary
            </h3>

            <div class="space-y-4 text-sm">

                {{-- Order Code --}}
                <div class="flex justify-between items-start">
                    <span class="text-cream0">Order Code</span>
                    <span class="font-medium text-dark text-right">
                        {{ $order->order_code }}
                    </span>
                </div>

                {{-- Package Name --}}
                <div class="flex justify-between items-start">
                    <span class="text-cream0">Package</span>
                    <span class="font-medium text-dark text-right">
                        {{ $order->package->name ?? '-' }}
                    </span>
                </div>

                {{-- Original Price --}}
                <div class="flex justify-between items-start">
                    <span class="text-cream0">Original Price</span>
                    <span class="font-medium text-dark">
                        Rp{{ number_format($order->amount + $order->discount, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Voucher Discount --}}
                @if($order->discount > 0)
                    <div class="flex justify-between items-start">
                        <span class="text-cream0">
                            Voucher
                            <span class="text-xs text-dark/40 block">
                                ({{ $order->voucher_code }})
                            </span>
                        </span>
                        <span class="font-medium text-secondary">
                            - Rp{{ number_format($order->discount, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                {{-- Payment Method --}}
                <div class="flex justify-between items-start">
                    <span class="text-cream0">Payment Method</span>
                    <span class="font-medium text-dark">
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
                    <span class="text-cream0">Payment Status</span>
                    <span class="font-semibold text-springs-ivy capitalize" id="payment-status">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                {{-- Payment Deadline --}}
                @if($order->expired_at)
                    <div class="flex justify-between items-start">
                        <span class="text-cream0">Payment Deadline</span>
                        <span class="font-medium text-secondary text-right">
                            {{ \Carbon\Carbon::parse($order->expired_at)->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                @endif

            </div>

            {{-- Divider --}}
            <div class="border-t border-light-pink/50 my-6"></div>

            {{-- Total Amount --}}
            <div class="flex justify-between items-center">
                <span class="text-cream0 text-sm font-medium">Total to Pay</span>
                <span class="text-2xl font-bold text-springs-ivy">
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

    if (!statusElement || !paymentStatusElement) {
        return;
    }

    function checkPaymentStatus() {
        if (checkCount >= maxChecks) {
            statusElement.textContent = 'Auto-check stopped. Refresh page manually.';
            statusElement.classList.remove('text-springs-ivy');
            statusElement.classList.add('text-cream0');
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
                    statusElement.classList.remove('text-springs-ivy');
                    statusElement.classList.add('text-springs-ivy');
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('payment.success', $order->order_code) }}";
                    }, 1000);
                    
                } else if (data.status === 'failed' || data.status === 'expired' || data.status === 'cancelled') {
                    // Payment failed/expired/cancelled
                    paymentStatusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    statusElement.textContent = 'Payment ' + data.status + '. Please contact admin.';
                    statusElement.classList.remove('text-springs-ivy');
                    statusElement.classList.add('text-secondary');
                    
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