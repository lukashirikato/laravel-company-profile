@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
    <h1 class="text-2xl font-bold mb-4">Checkout: {{ $product->name }}</h1>

    <div class="space-y-2">
        <p><strong>Nama:</strong> {{ $customer->name }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Harga:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
    </div>

    <button id="pay-button"
        class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
        Bayar Sekarang
    </button>
</div>

{{-- MIDTRANS SCRIPT --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay("{{ $snapToken }}", {
        onSuccess: function (result) {
            window.location.href = "/checkout/status/{{ $order->id }}";
        },
        onPending: function (result) {
            window.location.href = "/checkout/status/{{ $order->id }}";
        },
        onError: function (result) {
            alert("Pembayaran gagal, coba lagi.");
        },
        onClose: function () {
            alert("Jangan tutup sebelum pembayaran selesai.");
        }
    });
});
</script>
@endsection
