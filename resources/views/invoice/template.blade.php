<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_code }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.4;
            padding: 30px;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .info-table td {
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table th {
            background: #eee;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
        }

        .notes {
            margin-top: 20px;
            font-size: 12px;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
        }
    </style>

</head>

<body>

    {{-- JUDUL --}}
    <h1>INVOICE</h1>

    {{-- INFORMASI UTAMA --}}
    <table class="info-table">
        <tr>
            <td><strong>Bill to:</strong> {{ $customer->name }}</td>
            <td><strong>Invoice Number:</strong> {{ $order->order_code }}</td>
        </tr>
        <tr>
            <td><strong>From:</strong> FTM Society</td>
            <td>
                <strong>Invoice Date:</strong> 
                {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    {{-- TABEL PRODUK --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Service / Product</th>
                <th>Qty.</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
    {{-- PRODUCT --}}
    <tr>
        <td>1</td>
        <td>{{ $package->name }}</td>
        <td>1</td>
        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
    </tr>

    {{-- VOUCHER DISCOUNT --}}
    @if($order->discount > 0)
    <tr>
        <td></td>
        <td>
            Voucher Discount
            <br>
            <small>Kode: {{ $order->voucher_code }}</small>
        </td>
        <td>1</td>
        <td style="color:red;">
            - Rp {{ number_format($order->discount, 0, ',', '.') }}
        </td>
        <td style="color:red;">
            - Rp {{ number_format($order->discount, 0, ',', '.') }}
        </td>
    </tr>
    @endif

    {{-- TOTAL --}}
    <tr class="total-row">
        <td colspan="4">Total Amount</td>
        <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
    </tr>
</tbody>

    </table>

    {{-- CATATAN --}}
    <div class="notes">
        <p>Dengan membayar, Sister telah menyetujui peraturan-peraturan berikut:</p>
        <ol>
            <li>Dilarang menyalakan musik</li>
            <li>Dilarang merekam atau mengambil foto saat kelas berlangsung</li>
            <li>Tepat waktu</li>
            <li>Kelas bersifat Non-refundable, ketidakhadiran peserta tidak dapat di-refund atau digantikan.</li>
        </ol>

        <p>Jazakillah khair Sister. Semoga Allah mudahkan ikhtiar kita untuk menjaga tubuh titipan Allah ini agar tetap sehat dan bugar.</p>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <strong>Pembayaran:</strong><br>
        Bank Syariah Indonesia — 7123083727<br>
        an AFRA NISAA MADINA
    </div>

</body>
</html>
