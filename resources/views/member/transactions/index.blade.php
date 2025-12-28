
@extends('layouts.app') {{-- atau layout kamu --}}

@section('content')
<div class="container py-4">

    <h3 class="mb-4">Riwayat Transaksi</h3>

    @forelse ($transactions as $t)
        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>ID Transaksi:</strong> {{ $t->transaction_id ?? '-' }}<br>
                    <strong>Status:</strong> {{ $t->status ?? '-' }}<br>
                    <strong>Metode Pembayaran:</strong> {{ $t->payment_method ?? '-' }}
                </div>
                <div class="text-end">
                    <strong>Total:</strong> Rp {{ number_format($t->amount ?? 0) }}<br>
                    <small>{{ $t->created_at ? $t->created_at->format('d M Y H:i') : '' }}</small>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Belum ada transaksi.</p>
    @endforelse

</div>
@endsection
