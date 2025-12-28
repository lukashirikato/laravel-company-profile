@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 px-6 py-10">

    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Program Saya
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar paket & program yang sedang dan pernah kamu ikuti
                </p>
            </div>

            <a href="{{ route('member.profile') }}"
               class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2
                      bg-blue-600 text-white text-sm font-semibold
                      rounded-xl hover:bg-blue-700 transition">
                Profile Saya
            </a>
        </div>

        {{-- LIST PROGRAM --}}
        @forelse ($orders as $order)
            <div class="bg-white rounded-2xl shadow mb-6 p-6">

                {{-- TOP --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $order->package->name ?? 'Program' }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Order Code: {{ $order->order_code }}
                        </p>
                    </div>

                    {{-- STATUS --}}
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-1 rounded-full text-sm font-semibold
                            {{ $order->status === 'paid'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-200 text-gray-600' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>

                </div>

                {{-- DIVIDER --}}
                <div class="border-t my-6"></div>

                {{-- DETAILS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm">

                    <div>
                        <span class="block text-gray-400">Harga</span>
                        <span class="font-medium text-gray-800">
                            Rp{{ number_format($order->amount,0,',','.') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-400">Metode Pembayaran</span>
                        <span class="font-medium text-gray-800">
                            {{ strtoupper($order->payment_type ?? '-') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-400">Tanggal Pembelian</span>
                        <span class="font-medium text-gray-800">
                            {{ $order->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-400">Status Program</span>
                        <span class="font-semibold {{ $order->status === 'paid' ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $order->status === 'paid' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="mt-6 flex flex-wrap gap-3">

                    <a href="{{ route('invoice.show', $order->id) }}"
                       target="_blank"
                       class="px-5 py-2 rounded-xl border border-gray-300
                              text-sm font-semibold text-gray-700
                              hover:bg-gray-50 transition">
                        Download Invoice
                    </a>

                    <a href="{{ route('member.profile') }}"
                       class="px-5 py-2 rounded-xl bg-blue-600
                              text-white text-sm font-semibold
                              hover:bg-blue-700 transition">
                        Lihat Profile
                    </a>

                </div>

            </div>
        @empty

            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl shadow p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-700">
                    Belum Ada Program
                </h3>
                <p class="text-sm text-gray-500 mt-2">
                    Kamu belum memiliki program aktif.
                </p>

                <a href="{{ route('home') }}"
                   class="inline-block mt-6 px-6 py-3
                          bg-blue-600 text-white rounded-xl
                          font-semibold hover:bg-blue-700 transition">
                    Lihat Program
                </a>
            </div>

        @endforelse

    </div>

</div>
@endsection
