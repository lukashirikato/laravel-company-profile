@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- LEFT STATUS --}}
        <div class="p-10 flex flex-col justify-center items-center text-center bg-gray-50">

            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mt-6">
                Payment Successful
            </h2>

            <p class="text-gray-500 mt-3 max-w-sm">
                Your payment has been successfully processed.
                You now have full access to your selected program.
            </p>

            <a href="{{ route('member.profile') }}"
               class="mt-6 inline-block bg-green-600 text-white
                      px-8 py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                Go to My Profile
            </a>

            @if(isset($order))
                <a href="{{ route('invoice.show', $order->id) }}"
                   target="_blank"
                   class="mt-3 inline-block border border-gray-300 text-gray-700
                          px-6 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Download Invoice
                </a>
            @endif
        </div>

        {{-- RIGHT SUMMARY --}}
        <div class="p-10">

            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                Transaction Summary
            </h3>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Order Code</span>
                    <span class="font-medium text-gray-800">
                        {{ $order->order_code }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Package</span>
                    <span class="font-medium text-gray-800">
                        {{ $order->package->name ?? '-' }}
                    </span>
                </div>

                {{-- ORIGINAL PRICE --}}
                <div class="flex justify-between">
                    <span class="text-gray-500">Original Price</span>
                    <span class="font-medium text-gray-800">
                        Rp{{ number_format($order->amount + $order->discount,0,',','.') }}
                    </span>
                </div>

                {{-- VOUCHER --}}
                @if($order->discount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Voucher
                        <span class="text-xs text-gray-400">
                            ({{ $order->voucher_code }})
                        </span>
                    </span>
                    <span class="font-medium text-red-600">
                        - Rp{{ number_format($order->discount,0,',','.') }}
                    </span>
                </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-800">
                        {{ strtoupper($order->payment_type ?? '-') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Status</span>
                    <span class="font-semibold text-green-600 capitalize">
                        {{ $order->status }}
                    </span>
                </div>

            </div>

            <div class="border-t my-6"></div>

            <div class="flex justify-between items-center">
                <span class="text-gray-500 text-sm">Total Paid</span>
                <span class="text-2xl font-bold text-green-600">
                    Rp{{ number_format($order->amount,0,',','.') }}
                </span>
            </div>

        </div>

    </div>

</div>
@endsection
