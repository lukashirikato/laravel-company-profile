<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $order->order_code }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000000;
            line-height: 1.4;
            padding: 20px;
            background-color: #f5f5f5;
        }

        /* Action Bar (Only show on web, not PDF) */
        .action-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .action-bar h3 {
            font-size: 16px;
            color: #333333;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            color: #ffffff;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Invoice Container */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        /* HEADER WITH LOGO */
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #8B6F6F;
            padding-bottom: 15px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
            text-align: right;
        }

        .company-logo {
            max-width: 220px;
            max-height: 90px;
            height: auto;
        }

        h1 {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 3px;
            color: #8B6F6F;
        }

        /* Info Grid Table */
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 50%;
        }

        /* Product Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .product-table th {
            background-color: #f0f0f0;
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .product-table td {
            border: 1px solid #000000;
            padding: 8px;
        }

        .product-table .total-row td {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 12px;
        }

        .product-table .discount-row td {
            color: #cc0000;
        }

        /* Payment Info Section */
        .payment-info {
            margin-top: 25px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #cccccc;
        }

        .payment-info-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-table td {
            padding: 5px 0;
            border: none;
        }

        .payment-table td:first-child {
            width: 180px;
            font-weight: bold;
        }

        /* Payment Status Badge */
        .payment-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
        }

        .payment-status.paid {
            background-color: #d4edda;
            color: #155724;
        }

        .payment-status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .payment-status.failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Notes Section */
        .notes {
            margin-top: 25px;
            padding: 15px;
            background-color: #fafafa;
            border: 1px solid #dddddd;
        }

        .notes p {
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .notes ol {
            margin-left: 20px;
            line-height: 1.8;
        }

        .notes ol li {
            margin-bottom: 4px;
        }

        /* Payment Details Box */
        .payment-details {
            margin-top: 20px;
            padding: 12px;
            background-color: #f0f0f0;
            border: 1px solid #cccccc;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #cccccc;
            font-size: 11px;
            color: #666666;
        }

        .footer strong {
            color: #000000;
        }

        /* Print & PDF Specific */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .invoice-container {
                box-shadow: none;
                padding: 20px;
            }
        }

        /* Hide action bar in PDF */
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>

    {{-- ACTION BAR - Only visible on web, hidden in PDF/Print --}}
    <div class="action-bar">
        <div class="action-buttons">
            <button onclick="window.print()" class="btn btn-success">
                Print
            </button>
            <a href="{{ route('invoice.download', $order->id) }}" class="btn btn-primary">
                Download PDF
            </a>
        </div>
    </div>

    {{-- INVOICE CONTAINER --}}
    <div class="invoice-container">

        {{-- HEADER WITH LOGO FTM SOCIETY --}}
        {{-- HEADER WITH LOGO FTM SOCIETY --}}
<div class="invoice-header">
    <div class="header-left">
        @if(isset($logoBase64) && !empty($logoBase64))
            <img src="{{ $logoBase64 }}" alt="FTM Society Logo" class="company-logo">
        @else
            {{-- Fallback jika logo tidak ada --}}
            <div style="width: 220px; height: 90px; background-color: #8B6F6F; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                FTM
            </div>
        @endif
    </div>
    <div class="header-right">
        <h1>INVOICE</h1>
    </div>
</div>

        {{-- BILL TO & INVOICE INFO --}}
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

        {{-- PRODUCT TABLE --}}
        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Service / Product</th>
                    <th style="width: 60px;">Qty.</th>
                    <th style="width: 120px;">Price</th>
                    <th style="width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- Product Row --}}
                <tr>
                    <td>1</td>
                    <td>{{ $package->name }}</td>
                    <td>1</td>
                    <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                </tr>

                {{-- Discount Row --}}
                @if(isset($order->discount) && $order->discount > 0)
                <tr class="discount-row">
                    <td></td>
                    <td>
                        Voucher Discount
                        @if(isset($order->voucher_code))
                        <br><small style="font-size: 9px;">Kode: {{ $order->voucher_code }}</small>
                        @endif
                    </td>
                    <td>1</td>
                    <td>- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                    <td>- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif

                {{-- Total Row --}}
                <tr class="total-row">
                    <td colspan="4">Total Amount</td>
                    <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- PAYMENT INFORMATION --}}
        <div class="payment-info">
            <div class="payment-info-title">Payment Information:</div>
            
            <table class="payment-table">
                <tr>
                    <td>Payment Method:</td>
                    <td>
                        @if(isset($order->transaction) && isset($order->transaction->payment_type) && $order->transaction->payment_type !== '-')
                            {{ strtoupper(str_replace('_', ' ', $order->transaction->payment_type)) }}
                        @elseif(isset($order->payment_type) && $order->payment_type !== '-')
                            {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
                        @elseif(isset($order->payment_method) && $order->payment_method !== '-')
                            {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>
                        @php
                            // PERBAIKAN: Ambil status dari transaction, bukan dari order
                            $status = strtolower($order->transaction->status ?? $order->status ?? 'pending');
                            $statusClass = 'pending';
                            
                            if (in_array($status, ['paid', 'success', 'settlement', 'capture'])) {
                                $statusClass = 'paid';
                            } elseif (in_array($status, ['failed', 'expire', 'cancel', 'deny'])) {
                                $statusClass = 'failed';
                            }
                        @endphp
                        <span class="payment-status {{ $statusClass }}">
                            {{ strtoupper($order->transaction->status ?? $order->status ?? 'PENDING') }}
                        </span>
                    </td>
                </tr>
                @if(isset($order->transaction) && (isset($order->transaction->transaction_id) || isset($order->transaction->order_id)))
                <tr>
                    <td>Transaction ID:</td>
                    <td>{{ $order->transaction->transaction_id ?? $order->transaction->order_id ?? '-' }}</td>
                </tr>
                @elseif(isset($order->transaction_id) || isset($order->order_id))
                <tr>
                    <td>Transaction ID:</td>
                    <td>{{ $order->transaction_id ?? $order->order_id ?? '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td>Payment Date:</td>
                    <td>
                        @php
                            // PERBAIKAN: Ambil tanggal pembayaran dari transaction
                            $paymentDate = null;
                            
                            // Prioritas 1: transaction->settlement_time (Midtrans)
                            if (isset($order->transaction->settlement_time) && $order->transaction->settlement_time) {
                                $paymentDate = $order->transaction->settlement_time;
                            }
                            // Prioritas 2: transaction->paid_at
                            elseif (isset($order->transaction->paid_at) && $order->transaction->paid_at) {
                                $paymentDate = $order->transaction->paid_at;
                            }
                            // Prioritas 3: order->paid_at
                            elseif (isset($order->paid_at) && $order->paid_at) {
                                $paymentDate = $order->paid_at;
                            }
                            // Prioritas 4: Jika status PAID, gunakan updated_at
                            elseif (isset($order->transaction->status) && in_array(strtolower($order->transaction->status), ['paid', 'success', 'settlement', 'capture'])) {
                                $paymentDate = $order->transaction->updated_at ?? $order->updated_at;
                            }
                            elseif (isset($order->status) && in_array(strtolower($order->status), ['paid', 'success', 'settlement', 'capture'])) {
                                $paymentDate = $order->updated_at;
                            }
                        @endphp
                        
                        @if($paymentDate)
                            {{ \Carbon\Carbon::parse($paymentDate)->translatedFormat('d F Y, H:i') }} WIB
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- TERMS & CONDITIONS --}}
        <div class="notes">
            <p><strong>Dengan membayar, Sister telah menyetujui peraturan-peraturan berikut:</strong></p>
            <ol>
                <li>Dilarang menyalakan musik</li>
                <li>Dilarang merekam atau mengambil foto saat kelas berlangsung</li>
                <li>Tepat waktu</li>
                <li>Kelas bersifat Non-refundable, ketidakhadiran peserta tidak dapat di-refund atau digantikan.</li>
            </ol>
            <p style="margin-top: 10px;">
                Jazakillah khair Sister. Semoga Allah mudahkan ikhtiar kita untuk menjaga tubuh titipan Allah ini agar tetap sehat dan bugar.
            </p>
        </div>


        {{-- FOOTER --}}
        <div class="footer">
            <strong>Contact Information:</strong><br>
            FTM Society<br>
            Email: info@ftmsociety.com
            
            @if(isset($order->created_at))
            <p style="margin-top: 8px; font-size: 10px; color: #999999;">
                Invoice generated on {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }} WIB
            </p>
            @endif
        </div>

    </div>

</body>
</html>