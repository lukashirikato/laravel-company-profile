<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>Checkout Paket: {{ $package->name }}</h2>
<p>Harga: Rp{{ number_format($package->price) }}</p>

@php
    // Cek apakah paket mengandung kata "reformer"
    $pkgName = strtolower($package->name);

    $reformerKeywords = [
        'reformer',
        'reformer pilates',
        'reformer single',
        'single visit group',
    ];

    $isReformer = false;

    foreach ($reformerKeywords as $key) {
        if (strpos($pkgName, $key) !== false) {
            $isReformer = true;
            break;
        }
    }

    // Ambil kelas Reformer Pilates
    $reformerClass = \App\Models\ClassModel::where('name', 'Reformer Pilates')->first();
@endphp


<form id="checkout-form">
    <input type="hidden" name="package_id" value="{{ $package->id }}">

    {{-- AUTO PILIH KELAS UNTUK REFORMER --}}
    @if ($isReformer && $reformerClass)
        <p><strong>Kelas:</strong> Reformer Pilates</p>
        <input type="hidden" name="class_id" value="{{ $reformerClass->id }}">
    @endif

    <label>Nama:</label><br>
    <input type="text" name="name" value="{{ Auth::guard('customer')->user()->name ?? '' }}" required>
    <br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="{{ Auth::guard('customer')->user()->email ?? '' }}" required>
    <br><br>

    <label>Nomor HP:</label><br>
    <input type="text" name="phone" value="{{ Auth::guard('customer')->user()->phone_number ?? '' }}" required>
    <br><br>

    <button type="button" id="pay-button">Bayar Sekarang</button>
</form>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    let form = document.getElementById('checkout-form');
    let formData = new FormData(form);

    fetch("/guest/checkout/store", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert("Terjadi error: " + data.error);
            return;
        }

        snap.pay(data.snapToken, {
            onSuccess: function(result){
                window.location.href = "/checkout/success/" + data.order_id;
            },
            onPending: function(result){
                alert("Pembayaran pending.");
            },
            onError: function(result){
                alert("Terjadi error saat pembayaran.");
            }
        });
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi error, cek console.");
    });
});
</script>

</body>
</html>
