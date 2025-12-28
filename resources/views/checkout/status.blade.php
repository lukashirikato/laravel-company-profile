@extends('layouts.app')

@section('content')

@php
    // --- HELPER DAN ASUMSI DATA ---
    function rp($n) { return 'Rp' . number_format($n,0,',','.'); }

    // Asumsi: $order->status adalah 'paid', 'pending', atau 'failed'
    $transactionStatus = $order->status ?? 'pending';

    $isSuccess = in_array($transactionStatus, ['paid', 'settlement', 'success']);
    $isPending = $transactionStatus == 'pending';

    // --- LOGIC TAMPILAN BERDASARKAN STATUS ---
    $title = 'Menunggu Pembayaran';
    $message = 'Kami sedang menunggu konfirmasi. Anda dapat menutup halaman ini.';
    $iconClass = 'text-yellow-500 bg-yellow-100'; // Default: Pending

    if ($isSuccess) {
        $title = 'Payment was successful';
        $message = 'Pembayaran Anda telah diterima. Terima kasih atas pesanan Anda.';
        $iconClass = 'text-green-500 bg-green-100';
    } elseif ($transactionStatus == 'failed' || $transactionStatus == 'expire') {
        $title = 'Pembayaran Gagal';
        $message = 'Pembayaran gagal. Mohon coba lagi atau hubungi support.';
        $iconClass = 'text-red-500 bg-red-100';
    }

    // --- SIMULASI DETAIL TAMBAHAN (Sesuai desain Crypto/Wallet) ---
    // Ganti nilai simulasi ini dengan data riil dari $order atau Midtrans/Payment Gateway jika tersedia.
    $paidInFiat = rp($order->amount ?? 0);
    $paidInCoin = $order->payment_type ? ucfirst($order->payment_type) : 'Bank Transfer'; // Ganti jika ini adalah crypto
    $transactionTime = date('d M Y, H:i', strtotime($order->transaction_time ?? now()));
    $transactionHashOrRef = $order->transaction_id ?? $order->order_code;

@endphp

<style>
    /* Styling konsisten dengan desain ungu/violet sebelumnya */
    .btn-primary-violet {
        background-color: #8b5cf6;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }
    .btn-primary-violet:hover { background-color: #7c3aed; }
    .btn-secondary-violet {
        border: 1px solid #c084fc;
        color: #8b5cf6;
    }
    .btn-secondary-violet:hover { background-color: #f3e8ff; }
</style>

<div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center">

        {{-- Icon Status (Ikon Centang Besar untuk Sukses) --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $iconClass }} mb-6">
            @if($isSuccess)
                {{-- Checkmark Icon for Success --}}
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            @elseif($isPending)
                {{-- Clock Icon for Pending --}}
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            @else
                {{-- X Icon for Failed/Expired --}}
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            @endif
        </div>

        {{-- Judul dan Pesan --}}
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h1>
        <p class="text-gray-500 mb-8">{{ $message }}</p>

        {{-- Total yang Dibayar --}}
        <div class="inline-block px-6 py-2 bg-gray-100 rounded-lg text-xl font-bold text-gray-800 mb-8">
            {{ $paidInFiat }} IDR
        </div>

        {{-- Detail Transaksi (Mengikuti Layout Gambar: Key-Value List) --}}
        <div class="space-y-4 text-left">
            <div class="flex justify-between text-sm text-gray-500">
                <span>**Customer Name**</span>
                <span class="font-medium text-gray-900">{{ $order->customer_name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>**Payment Method**</span>
                <span class="font-medium text-gray-900">{{ $paidInCoin }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>**Order Code**</span>
                <span class="font-medium text-gray-900">{{ $orderId }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>**Transaction time**</span>
                <span class="font-medium text-gray-900">{{ $transactionTime }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>**Transaction Hash/Ref**</span>
                {{-- Hash dibuat sebagai link, seperti di gambar --}}
                <a href="#" class="font-medium text-violet-600 hover:text-violet-700 truncate">{{ $transactionHashOrRef }}</a>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-4 mt-8">
            {{-- Tombol 'Cek Status Pembayaran' hanya muncul jika Pending --}}
            @if($isPending)
                <button id="check-status" class="flex-1 py-3 rounded-xl text-white font-semibold btn-primary-violet shadow-lg shadow-violet-500/30">
                    Cek Status Pembayaran
                </button>
            @else
                {{-- Tombol Download Inovice dan Receipt (Hanya untuk Sukses) --}}
                <button class="flex-1 py-3 rounded-xl font-semibold btn-secondary-violet hover:bg-violet-50 transition-colors">
                    Download Invoice
                </button>
                <a href="{{ url('/dashboard/invoices/' . $orderId) }}" class="flex-1 py-3 rounded-xl text-white font-semibold btn-primary-violet shadow-lg shadow-violet-500/30">
                    Download Receipt
                </a>
            @endif
        </div>

    </div>
</div>

<script>
// --- LOGIC Cek Status Pembayaran (Dipertahankan dari kode lama Anda) ---
const checkBtn = document.getElementById('check-status');

if (checkBtn) {
    checkBtn.addEventListener('click', function() {
        // Menggunakan fetch ke route yang sudah ada
        fetch("{{ route('checkout.status.get', $order->order_code) }}")
            .then(res => res.json())
            .then(data => {
                // Setelah mendapatkan status, redirect atau reload halaman untuk memuat tampilan baru.
                if (data.status === "paid" || data.status === "settlement") {
                    window.location.reload(); // Reload untuk memuat UI sukses
                } else if (data.status === "failed" || data.status === "expire") {
                    alert("Pembayaran gagal atau dibatalkan!");
                    window.location.reload(); // Reload untuk memuat UI gagal
                } else {
                    alert("Pembayaran masih pending.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Gagal mengecek status pembayaran.");
            });
    });
}
</script>

@endsection