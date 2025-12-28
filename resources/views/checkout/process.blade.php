@extends('layouts.app')

@section('content')
<div class="container mx-auto py-20">
    <h2 class="text-3xl font-bold mb-6 text-center">Checkout: {{ $package->name }}</h2>
    <p class="text-center mb-6">IDR {{ number_format($package->price,0,',','.') }}</p>

    <div class="text-center mb-4">
        <p>Nama: {{ auth('customer')->user()->name }}</p>
        <p>Email: {{ auth('customer')->user()->email }}</p>
    </div>

    <button id="pay-button" class="bg-primary text-white px-6 py-3 rounded-lg mx-auto block">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').onclick = function(){

    fetch("{{ route('checkout.process', $package->slug) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if(data.snapToken){
            snap.pay(data.snapToken, {
                onSuccess: function(result){
                    alert('Pembayaran berhasil!');
                    window.location.href = "/invoice/" + result.order_id;
                },
                onPending: function(result){
                    alert('Menunggu pembayaran.');
                    window.location.href = "/invoice/" + result.order_id;
                },
                onError: function(result){
                    alert('Terjadi error!');
                },
                onClose: function(){
                    alert('Popup ditutup.');
                }
            });
        } else {
            alert('Gagal membuat transaksi.');
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('Terjadi error saat checkout.');
    });

};
</script>
@endsection
