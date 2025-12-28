<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>

<h3>Memproses Pembayaran...</h3>

<button id="pay-button">Bayar Sekarang</button>

<script>
document.getElementById('pay-button').onclick = function () {
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            window.location.href = "/payment/success";
        },
        onPending: function(result){
            alert("Menunggu pembayaran...");
        },
        onError: function(result){
            alert("Pembayaran gagal!");
        }
    });
};
</script>

</body>
</html>
