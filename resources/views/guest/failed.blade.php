<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tidak Selesai — FTM Society</title>

    <link rel="icon" type="image/png" href="{{ asset('icons/favicon.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#EE4E8B",
                        secondary: "#7A2B4A",
                        accent: "#1A7A5E",
                        "light-pink": "#F4C9DF",
                        cream: "#FCF9F2",
                        dark: "#1C1C1C",
                    },
                    fontFamily: { nord: ['Nord', 'Poppins', 'sans-serif'] },
                },
            },
        };
    </script>

    @php
        $adminWhatsapp = config('services.ftm.admin_whatsapp', '6287785767395');
        $waMessage = "Halo Admin FTM Society, pembayaran saya belum berhasil."
            . (isset($order) ? "\n\nKode Pesanan: {$order->order_code}\nPaket: " . ($order->package->name ?? '-') : '')
            . "\n\nMohon dibantu. Terima kasih.";
        $waUrl = 'https://wa.me/' . $adminWhatsapp . '?text=' . rawurlencode($waMessage);
    @endphp

    <style>
        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: #1C1C1C;
            background-color: #FCF9F2;
            background-image:
                radial-gradient(circle at 12% 0%, rgba(238, 78, 139, 0.12) 0%, transparent 34%),
                radial-gradient(circle at 90% 12%, rgba(244, 201, 223, 0.5) 0%, transparent 38%);
            min-height: 100vh;
        }

        h1, h2, .font-nord { font-family: 'Nord', 'Poppins', sans-serif; letter-spacing: -0.01em; }

        .ftm-card {
            background: #fff;
            border: 1px solid rgba(244, 201, 223, 0.85);
            border-radius: 22px;
            box-shadow: 0 20px 44px rgba(122, 43, 74, 0.1);
        }

        .ftm-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.95rem 1.25rem;
            border-radius: 14px;
            background: linear-gradient(135deg, #EE4E8B, #7A2B4A);
            color: #fff;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 14px 26px rgba(122, 43, 74, 0.24);
        }

        .ftm-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.9rem 1.25rem;
            border-radius: 14px;
            background: #fff;
            border: 1.5px solid rgba(244, 201, 223, 0.95);
            color: #7A2B4A;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background 0.18s ease, border-color 0.18s ease;
        }

        .ftm-btn-secondary:hover { background: #FCF9F2; border-color: #EE4E8B; }
    </style>
</head>
<body class="antialiased flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-5">
            <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="FTM Society" class="w-14 h-14 rounded-full object-cover mx-auto border-2 border-light-pink shadow-md">
        </div>

        <div class="ftm-card p-6 sm:p-8 text-center">
            <div class="w-20 h-20 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center mx-auto">
                <i class="ri-close-circle-line text-rose-500 text-4xl"></i>
            </div>

            <h1 class="text-secondary text-2xl font-extrabold mt-4">Pembayaran Belum Selesai</h1>
            <p class="text-dark/60 text-sm leading-relaxed mt-2">
                Sepertinya pembayaran tidak berhasil atau dibatalkan. Tidak ada dana yang kami tagih
                untuk transaksi yang gagal.
            </p>

            @if(isset($order))
                <div class="mt-5 rounded-xl bg-cream border border-light-pink/70 p-4 text-left">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[11px] uppercase tracking-wider text-dark/45 font-semibold">Kode Pesanan</span>
                        <span class="font-mono font-bold text-secondary text-xs break-all">{{ $order->order_code }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 mt-2">
                        <span class="text-[11px] uppercase tracking-wider text-dark/45 font-semibold">Paket</span>
                        <span class="font-medium text-xs text-right">{{ $order->package->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 mt-2">
                        <span class="text-[11px] uppercase tracking-wider text-dark/45 font-semibold">Total</span>
                        <span class="font-nord font-extrabold text-secondary text-sm">Rp{{ number_format((int) $order->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif

            <div class="mt-6 space-y-2.5">
                <a href="{{ url('/') }}" class="ftm-btn-primary">
                    <i class="ri-arrow-left-line text-lg"></i>
                    Kembali ke Beranda
                </a>

                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="ftm-btn-secondary">
                    <i class="ri-whatsapp-line text-accent"></i>
                    Hubungi Admin via WhatsApp
                </a>
            </div>

            <p class="text-[11px] text-dark/45 mt-4">
                Ingin mencoba lagi? Buka halaman paket di beranda lalu ulangi checkout.
            </p>
        </div>

        <p class="text-center text-[11px] text-dark/40 mt-6">© {{ date('Y') }} FTM Society · Muslimah-Only Gym</p>
    </div>
</body>
</html>
