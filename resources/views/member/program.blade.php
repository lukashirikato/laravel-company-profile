@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream px-6 py-10">

    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-dark">
                    Program Saya
                </h1>
                <p class="text-sm text-cream0 mt-1">
                    Daftar paket & program yang sedang dan pernah kamu ikuti
                </p>
            </div>

            <a href="{{ route('member.profile') }}"
               class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2
                      bg-primary text-white text-sm font-semibold
                      rounded-xl hover:bg-secondary transition">
                Profile Saya
            </a>
        </div>

        {{-- LIST PROGRAM --}}
        @forelse ($orders as $order)
            <div class="bg-white rounded-2xl shadow mb-6 p-6">

                {{-- TOP --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-dark">
                            {{ $order->package->name ?? 'Program' }}
                        </h2>
                        <p class="text-sm text-cream0 mt-1">
                            Order Code: {{ $order->order_code }}
                        </p>
                    </div>

                    {{-- STATUS --}}
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-1 rounded-full text-sm font-semibold
                            {{ $order->status === 'paid'
                                ? 'bg-grounded-green/40 text-springs-ivy'
                                : 'bg-light-pink/30 text-dark/70' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>

                </div>

                {{-- DIVIDER --}}
                <div class="border-t my-6"></div>

                {{-- DETAILS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm">

                    <div>
                        <span class="block text-dark/40">Harga</span>
                        <span class="font-medium text-dark">
                            Rp{{ number_format($order->amount,0,',','.') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-dark/40">Metode Pembayaran</span>
                        <span class="font-medium text-dark">
                            {{ strtoupper($order->payment_type ?? '-') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-dark/40">Tanggal Pembelian</span>
                        <span class="font-medium text-dark">
                            {{ $order->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-dark/40">Status Program</span>
                        <span class="font-semibold {{ $order->status === 'paid' ? 'text-accent' : 'text-cream0' }}">
                            {{ $order->status === 'paid' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="mt-6 flex flex-wrap gap-3">

                    <a href="{{ route('invoice.show', $order->id) }}"
                       target="_blank"
                       class="px-5 py-2 rounded-xl border border-light-pink/60
                              text-sm font-semibold text-dark
                              hover:bg-cream transition">
                        Download Invoice
                    </a>

                    <a href="{{ route('member.profile') }}"
                       class="px-5 py-2 rounded-xl bg-primary
                              text-white text-sm font-semibold
                              hover:bg-secondary transition">
                        Lihat Profile
                    </a>

                </div>

            </div>
        @empty

            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl shadow p-12 text-center">
                <h3 class="text-lg font-semibold text-dark">
                    Belum Ada Program
                </h3>
                <p class="text-sm text-cream0 mt-2">
                    Kamu belum memiliki program aktif.
                </p>

                <a href="{{ route('home') }}"
                   class="inline-block mt-6 px-6 py-3
                          bg-primary text-white rounded-xl
                          font-semibold hover:bg-secondary transition">
                    Lihat Program
                </a>
            </div>

        @endforelse

    </div>

</div>
@endsection
