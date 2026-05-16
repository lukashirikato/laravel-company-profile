@extends('layouts.app')

@section('content')

{{-- ⚠️ IMPORTANT: Redirect jika payment belum complete --}}
@if(!in_array($order->status, ['paid', 'active', 'settlement']))
    <script>
        // Auto redirect jika user coba akses success page tapi statusnya bukan paid
        window.location.href = "{{ route('payment.success', $order->order_code) }}";
    </script>
@endif

<div class="min-h-screen bg-cream flex items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- LEFT STATUS --}}
        <div class="p-10 flex flex-col justify-center items-center text-center bg-cream">

            <div class="w-20 h-20 rounded-full bg-grounded-green/40 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-accent"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-dark mt-6">
                Payment Successful
            </h2>

            <p class="text-cream0 mt-3 max-w-sm">
                Your payment has been successfully processed.
                You now have full access to your selected program.
            </p>

            <a href="{{ route('member.book') }}"
            class="mt-6 inline-block bg-accent text-white
                    px-8 py-3 rounded-xl font-semibold hover:bg-springs-ivy transition">
                Book Class Schedule
            </a>


            @if(isset($order))
                <a href="{{ route('invoice.show', $order->id) }}"
                   target="_blank"
                   class="mt-3 inline-block border border-light-pink/60 text-dark
                          px-6 py-2 rounded-xl text-sm font-medium hover:bg-cream transition">
                    Download Invoice
                </a>
            @endif
        </div>

        {{-- RIGHT SUMMARY --}}
        <div class="p-10">

            <h3 class="text-lg font-semibold text-dark mb-6">
                Transaction Summary
            </h3>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">
                    <span class="text-cream0">Order Code</span>
                    <span class="font-medium text-dark">
                        {{ $order->order_code }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-cream0">Package</span>
                    <span class="font-medium text-dark">
                        {{ $order->package->name ?? '-' }}
                    </span>
                </div>

                {{-- ORIGINAL PRICE --}}
                <div class="flex justify-between">
                    <span class="text-cream0">Original Price</span>
                    <span class="font-medium text-dark">
                        Rp{{ number_format($order->amount + $order->discount,0,',','.') }}
                    </span>
                </div>

                {{-- VOUCHER --}}
                @if($order->discount > 0)
                <div class="flex justify-between">
                    <span class="text-cream0">
                        Voucher
                        <span class="text-xs text-dark/40">
                            ({{ $order->voucher_code }})
                        </span>
                    </span>
                    <span class="font-medium text-secondary">
                        - Rp{{ number_format($order->discount,0,',','.') }}
                    </span>
                </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-cream0">Payment Method</span>
                    <span class="font-medium text-dark">
                        {{ strtoupper($order->payment_type ?? '-') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-cream0">Payment Status</span>
                    <span class="font-semibold text-accent capitalize">
                        {{ $order->status }}
                    </span>
                </div>

            </div>

            <div class="border-t my-6"></div>

            <div class="flex justify-between items-center">
                <span class="text-cream0 text-sm">Total Paid</span>
                <span class="text-2xl font-bold text-accent">
                    Rp{{ number_format($order->amount,0,',','.') }}
                </span>
            </div>

        </div>

    </div>

</div>
@endsection