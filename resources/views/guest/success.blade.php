<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Berhasil — FTM Society</title>

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
                        "springs-ivy": "#1D5A4B",
                        "grounded-green": "#C5D79B",
                        "burnt-cherry": "#7A2B4A",
                        "soft-petals": "#F4C9DF",
                        "patina-green": "#1A7A5E",
                        layl: "#1C1C1C",
                        rising: "#FCF9F2",
                    },
                    fontFamily: {
                        nord: ['Nord', 'Poppins', 'sans-serif'],
                        instrument: ['"Instrument Serif"', 'Georgia', 'serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    @php
        $adminWhatsapp = config('services.ftm.admin_whatsapp', '6287785767395');
        $waMessage = "Halo Admin FTM Society, saya sudah melakukan pembayaran.\n\n"
            . "Kode Pesanan: {$order->order_code}\n"
            . "Paket: " . ($order->package->name ?? '-') . "\n"
            . "Total: Rp" . number_format((int) $order->amount, 0, ',', '.') . "\n\n"
            . "Mohon dibantu untuk proses selanjutnya. Terima kasih.";
        $waUrl = 'https://wa.me/' . $adminWhatsapp . '?text=' . rawurlencode($waMessage);
        $paymentType = $order->payment_type ?? optional($order->transaction)->payment_type;
        $paymentLabel = $paymentType ? ucwords(str_replace('_', ' ', $paymentType)) : 'Midtrans';
    @endphp

    <style>
        :root {
            --ftm-pink: #EE4E8B;
            --ftm-cherry: #7A2B4A;
            --ftm-petals: #F4C9DF;
            --ftm-cream: #FCF9F2;
            --ftm-green: #1A7A5E;
            --ftm-ink: #1C1C1C;
        }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: var(--ftm-ink);
            background-color: var(--ftm-cream);
            background-image:
                radial-gradient(circle at 12% 0%, rgba(238, 78, 139, 0.14) 0%, transparent 34%),
                radial-gradient(circle at 90% 12%, rgba(197, 215, 155, 0.35) 0%, transparent 38%),
                radial-gradient(circle at 50% 100%, rgba(122, 43, 74, 0.08) 0%, transparent 55%);
            min-height: 100vh;
        }

        h1, h2, h3, .font-nord {
            font-family: 'Nord', 'Poppins', sans-serif;
            letter-spacing: -0.01em;
        }

        .ftm-shell { width: 100%; max-width: 860px; margin: 0 auto; padding: 0 1rem; }

        .ftm-card {
            background: #fff;
            border: 1px solid rgba(244, 201, 223, 0.85);
            border-radius: 22px;
            box-shadow: 0 20px 44px rgba(122, 43, 74, 0.1), 0 1px 3px rgba(28, 28, 28, 0.04);
        }

        .ftm-success-ring {
            width: 84px;
            height: 84px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 35% 25%, rgba(197, 215, 155, 0.95), rgba(26, 122, 94, 0.18));
            box-shadow: 0 14px 30px rgba(26, 122, 94, 0.22);
            animation: ftm-pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes ftm-pop {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .ftm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.7rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.7rem;
            background: rgba(197, 215, 155, 0.45);
            color: #1D5A4B;
        }

        .ftm-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.85rem;
            padding: 0.4rem 0;
        }

        .ftm-summary-row .label { color: rgba(28, 28, 28, 0.6); }

        .ftm-step-item {
            position: relative;
            display: flex;
            gap: 0.85rem;
            padding-bottom: 1.15rem;
        }

        .ftm-step-item:last-child { padding-bottom: 0; }

        .ftm-step-item::before {
            content: '';
            position: absolute;
            left: 0.72rem;
            top: 1.6rem;
            bottom: 0;
            width: 2px;
            background: rgba(244, 201, 223, 0.85);
        }

        .ftm-step-item:last-child::before { display: none; }

        .ftm-step-num {
            position: relative;
            z-index: 1;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 999px;
            background: rgba(244, 201, 223, 0.6);
            color: var(--ftm-cherry);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 0.68rem;
            flex-shrink: 0;
        }

        .ftm-step-item.is-done .ftm-step-num {
            background: var(--ftm-green);
            color: #fff;
        }

        .ftm-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.95rem 1.25rem;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--ftm-pink), var(--ftm-cherry));
            color: #fff;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 14px 26px rgba(122, 43, 74, 0.24);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .ftm-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(122, 43, 74, 0.3);
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
            color: var(--ftm-cherry);
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background 0.18s ease, border-color 0.18s ease;
        }

        .ftm-btn-secondary:hover { background: var(--ftm-cream); border-color: var(--ftm-pink); }

        .ftm-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-weight: 700;
            letter-spacing: 0.04em;
            background: var(--ftm-cream);
            border: 1px dashed rgba(244, 201, 223, 0.95);
            border-radius: 10px;
            padding: 0.55rem 0.75rem;
            font-size: 0.82rem;
            word-break: break-all;
        }

        @media (prefers-reduced-motion: reduce) {
            .ftm-success-ring { animation: none; }
            .ftm-btn-primary { transition: none; }
        }
    </style>
</head>
<body class="antialiased">
    <header class="w-full border-b border-light-pink/60 bg-white/80 backdrop-blur">
        <div class="ftm-shell flex items-center justify-between py-3.5">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="FTM Society" class="w-9 h-9 rounded-full object-cover border border-light-pink">
                <span class="font-nord font-extrabold text-secondary text-sm tracking-wide">FTM SOCIETY</span>
            </a>
            <span class="text-[11px] text-dark/50 flex items-center gap-1.5">
                <i class="ri-lock-2-line"></i> Transaksi terenkripsi
            </span>
        </div>
    </header>

    <main class="ftm-shell py-7 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-5 lg:gap-7 items-start">
            <div class="space-y-5">
                <section class="ftm-card p-6 sm:p-8 text-center">
                    <div class="ftm-success-ring mx-auto">
                        <i class="ri-check-line text-white text-4xl"></i>
                    </div>

                    <span class="ftm-badge mt-5">
                        <i class="ri-checkbox-circle-fill"></i>
                        Pembayaran berhasil
                    </span>

                    <h1 class="text-secondary text-2xl sm:text-3xl font-extrabold mt-3">
                        Terima kasih, {{ $order->customer_name ?: ($order->customer->name ?? 'Sahabat FTM') }}!
                    </h1>
                    <p class="text-dark/60 text-sm leading-relaxed mt-2 max-w-md mx-auto">
                        Pembayaran kamu sudah kami terima. Satu langkah lagi: aktifkan password akun
                        untuk mulai booking kelas dan melihat jadwal.
                    </p>

                    @if($awaitingAdmin)
                        <div class="mt-4 flex items-start gap-2.5 text-left rounded-xl border border-amber-200 bg-amber-50 p-3.5">
                            <i class="ri-time-line text-amber-600 mt-0.5"></i>
                            <p class="text-[12px] text-amber-800 leading-relaxed">
                                Paket ini memerlukan verifikasi admin sebelum kuota aktif.
                                Tim FTM akan menghubungi kamu via WhatsApp.
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 space-y-2.5">
                        @if($needsActivation)
                            <a href="{{ $activationUrl }}" class="ftm-btn-primary">
                                <i class="ri-user-add-line text-lg"></i>
                                Aktifkan Akun Sekarang
                            </a>
                            <p class="text-[11px] text-dark/45">
                                Link aktivasi juga kami kirim ke WhatsApp kamu.
                            </p>
                        @else
                            <a href="{{ route('login') }}" class="ftm-btn-primary">
                                <i class="ri-login-box-line text-lg"></i>
                                Masuk ke Member Area
                            </a>
                        @endif

                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="ftm-btn-secondary">
                            <i class="ri-whatsapp-line text-lg text-accent"></i>
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>

                    <p class="text-[11px] text-dark/45 mt-4">
                        Simpan kode pesanan di bawah ini untuk keperluan konfirmasi.
                    </p>
                </section>

                <section class="ftm-card p-5 sm:p-6">
                    <h2 class="font-nord font-extrabold text-secondary text-base mb-4">Langkah Selanjutnya</h2>

                    <div class="ftm-step-item is-done">
                        <span class="ftm-step-num"><i class="ri-check-line"></i></span>
                        <div>
                            <p class="font-nord font-bold text-secondary text-sm">Pembayaran dikonfirmasi</p>
                            <p class="text-dark/55 text-xs mt-0.5">Sistem sudah menerima pembayaran kamu.</p>
                        </div>
                    </div>

                    <div class="ftm-step-item {{ $needsActivation ? '' : 'is-done' }}">
                        <span class="ftm-step-num">{{ $needsActivation ? '2' : '' }}<i class="{{ $needsActivation ? '' : 'ri-check-line' }}"></i></span>
                        <div>
                            <p class="font-nord font-bold text-secondary text-sm">Aktivasi akun member</p>
                            <p class="text-dark/55 text-xs mt-0.5">
                                {{ $needsActivation ? 'Buat password untuk mengakses area member.' : 'Akun kamu sudah aktif.' }}
                            </p>
                        </div>
                    </div>

                    <div class="ftm-step-item">
                        <span class="ftm-step-num">3</span>
                        <div>
                            <p class="font-nord font-bold text-secondary text-sm">Booking kelas favoritmu</p>
                            <p class="text-dark/55 text-xs mt-0.5">Pilih jadwal yang tersedia dari dashboard member.</p>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="lg:sticky lg:top-6">
                <div class="ftm-card p-5 sm:p-6">
                    <h2 class="font-nord font-extrabold text-secondary text-base mb-4">Detail Pesanan</h2>

                    <div class="rounded-xl bg-cream border border-light-pink/70 p-4 mb-4">
                        <p class="text-[10px] uppercase tracking-wider text-dark/45 font-bold mb-1">Kode Pesanan</p>
                        <div class="flex items-center gap-2">
                            <span id="order-code" class="ftm-code flex-1">{{ $order->order_code }}</span>
                            <button type="button" id="copy-code" class="w-9 h-9 rounded-lg bg-white border border-light-pink flex items-center justify-center text-secondary hover:bg-light-pink/40 transition" aria-label="Salin kode pesanan">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="ftm-summary-row">
                            <span class="label">Paket</span>
                            <span class="font-medium text-right">{{ $order->package->name ?? '-' }}</span>
                        </div>
                        <div class="ftm-summary-row">
                            <span class="label">Email</span>
                            <span class="font-medium text-right break-all">{{ $order->customer->email ?? '-' }}</span>
                        </div>
                        <div class="ftm-summary-row">
                            <span class="label">Metode</span>
                            <span class="font-medium text-right">{{ $paymentLabel }}</span>
                        </div>
                        <div class="ftm-summary-row">
                            <span class="label">Status</span>
                            <span class="font-semibold text-accent capitalize">{{ $order->status }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-light-pink my-4"></div>

                    <div class="flex items-end justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-dark/45 font-semibold">Total dibayar</span>
                        <span class="font-nord font-extrabold text-secondary text-xl">Rp{{ number_format((int) $order->amount, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('home') }}" class="ftm-btn-secondary mt-5">
                        <i class="ri-arrow-left-line"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </aside>
        </div>
    </main>

    <footer class="ftm-shell py-8 text-center">
        <p class="text-[11px] text-dark/40">© {{ date('Y') }} FTM Society · Muslimah-Only Gym</p>
    </footer>

    <script>
        (function () {
            const copyButton = document.getElementById('copy-code');
            const codeEl = document.getElementById('order-code');

            if (!copyButton || !codeEl) return;

            copyButton.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(codeEl.textContent.trim());
                    const original = copyButton.innerHTML;
                    copyButton.innerHTML = '<i class="ri-check-line text-accent"></i>';
                    setTimeout(() => { copyButton.innerHTML = original; }, 1400);
                } catch (e) {
                    const range = document.createRange();
                    range.selectNodeContents(codeEl);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            });
        })();
    </script>
</body>
</html>
