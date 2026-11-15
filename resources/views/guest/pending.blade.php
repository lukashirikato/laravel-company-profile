<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menunggu Pembayaran — FTM Society</title>

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
                        "grounded-green": "#C5D79B",
                        "burnt-cherry": "#7A2B4A",
                        rising: "#FCF9F2",
                    },
                    fontFamily: {
                        nord: ['Nord', 'Poppins', 'sans-serif'],
                        instrument: ['"Instrument Serif"', 'Georgia', 'serif'],
                    },
                },
            },
        };
    </script>

    @php
        $isProduction = (bool) config('midtrans.is_production', false);
        $snapUrl = $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';

        $adminWhatsapp = config('services.ftm.admin_whatsapp', '6287785767395');
        $waMessage = "Halo Admin FTM Society, saya memiliki pesanan yang belum selesai dibayar.\n\n"
            . "Kode Pesanan: {$order->order_code}\n"
            . "Paket: " . ($order->package->name ?? '-') . "\n"
            . "Total: Rp" . number_format((int) $order->amount, 0, ',', '.') . "\n\n"
            . "Mohon dibantu. Terima kasih.";
        $waUrl = 'https://wa.me/' . $adminWhatsapp . '?text=' . rawurlencode($waMessage);
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        :root {
            --ftm-pink: #EE4E8B;
            --ftm-cherry: #7A2B4A;
            --ftm-cream: #FCF9F2;
            --ftm-ink: #1C1C1C;
        }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: var(--ftm-ink);
            background-color: var(--ftm-cream);
            background-image:
                radial-gradient(circle at 10% 0%, rgba(238, 78, 139, 0.12) 0%, transparent 34%),
                radial-gradient(circle at 92% 10%, rgba(244, 201, 223, 0.55) 0%, transparent 38%),
                radial-gradient(circle at 50% 100%, rgba(122, 43, 74, 0.07) 0%, transparent 55%);
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

        .ftm-pending-ring {
            width: 84px;
            height: 84px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(244, 201, 223, 0.9), rgba(238, 78, 139, 0.2));
            box-shadow: 0 14px 30px rgba(238, 78, 139, 0.18);
            animation: ftm-breathe 2.6s ease-in-out infinite;
        }

        @keyframes ftm-breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.045); }
        }

        .ftm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.7rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.7rem;
            background: rgba(244, 201, 223, 0.55);
            color: var(--ftm-cherry);
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

        .ftm-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-weight: 700;
            letter-spacing: 0.04em;
            background: var(--ftm-cream);
            border: 1px dashed rgba(244, 201, 223, 0.95);
            border-radius: 10px;
            padding: 0.6rem 0.8rem;
            font-size: 0.82rem;
            word-break: break-all;
        }

        .ftm-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.95rem 1.25rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--ftm-pink), var(--ftm-cherry));
            color: #fff;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 14px 26px rgba(122, 43, 74, 0.24);
            transition: transform 0.15s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .ftm-btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(122, 43, 74, 0.3);
        }

        .ftm-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

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

        .ftm-alert {
            display: none;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.8rem 0.95rem;
            border-radius: 12px;
            background: rgba(238, 78, 139, 0.1);
            border-left: 4px solid var(--ftm-pink);
            color: var(--ftm-cherry);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .ftm-alert.is-visible { display: flex; }

        .ftm-spin {
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-top-color: #fff;
            border-radius: 999px;
            animation: ftm-spin 0.7s linear infinite;
        }

        @keyframes ftm-spin { to { transform: rotate(360deg); } }

        @media (prefers-reduced-motion: reduce) {
            .ftm-pending-ring { animation: none; }
            .ftm-spin { animation-duration: 1.4s; }
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
                <i class="ri-lock-2-line"></i> Pembayaran diproses Midtrans
            </span>
        </div>
    </header>

    <main class="ftm-shell py-7 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-5 lg:gap-7 items-start">
            <div class="space-y-5">
                <section class="ftm-card p-6 sm:p-8 text-center">
                    <div class="ftm-pending-ring mx-auto">
                        <i class="ri-time-line text-secondary text-4xl"></i>
                    </div>

                    <span class="ftm-badge mt-5">
                        <i class="ri-loader-4-line"></i>
                        Menunggu pembayaran
                    </span>

                    <h1 class="text-secondary text-2xl sm:text-3xl font-extrabold mt-3">Pesananmu sudah tersimpan</h1>
                    <p class="text-dark/60 text-sm leading-relaxed mt-2 max-w-md mx-auto">
                        Selesaikan pembayaran untuk mengaktifkan paket. Jika kamu sudah membayar,
                        status akan diperbarui otomatis setelah konfirmasi Midtrans diterima.
                    </p>

                    <div id="pending-alert" class="ftm-alert mt-4" role="alert" aria-live="polite">
                        <i class="ri-error-warning-line text-base mt-0.5"></i>
                        <span id="pending-alert-text">Terjadi kesalahan.</span>
                    </div>

                    <div class="mt-6 space-y-2.5">
                        <button type="button" id="retry-payment" class="ftm-btn-primary">
                            <i class="ri-secure-payment-line text-lg" id="retry-icon"></i>
                            <span id="retry-label">Selesaikan Pembayaran</span>
                        </button>

                        <a href="{{ route('guest.checkout.success', $order->order_code) }}" class="ftm-btn-secondary">
                            <i class="ri-refresh-line"></i>
                            Saya Sudah Bayar — Cek Status
                        </a>

                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="ftm-btn-secondary">
                            <i class="ri-whatsapp-line text-accent"></i>
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>

                    <p id="auto-check" class="text-[11px] text-dark/45 mt-4 flex items-center justify-center gap-1.5">
                        <i class="ri-refresh-line"></i>
                        Memeriksa status pembayaran otomatis…
                    </p>
                </section>

                <section class="ftm-card p-5 sm:p-6">
                    <h2 class="font-nord font-extrabold text-secondary text-base mb-3">Yang perlu kamu tahu</h2>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-xs text-dark/65 leading-relaxed">
                            <i class="ri-checkbox-circle-line text-accent text-base mt-0.5"></i>
                            <span>Jangan tutup halaman ini sebelum pembayaran selesai.</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs text-dark/65 leading-relaxed">
                            <i class="ri-checkbox-circle-line text-accent text-base mt-0.5"></i>
                            <span>Kode pesanan bisa digunakan untuk mengecek status kapan saja.</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs text-dark/65 leading-relaxed">
                            <i class="ri-checkbox-circle-line text-accent text-base mt-0.5"></i>
                            <span>Setelah lunas, kamu akan diarahkan untuk mengaktifkan akun member.</span>
                        </li>
                    </ul>
                </section>
            </div>

            <aside class="lg:sticky lg:top-6">
                <div class="ftm-card p-5 sm:p-6">
                    <h2 class="font-nord font-extrabold text-secondary text-base mb-4">Detail Pesanan</h2>

                    <div class="rounded-xl bg-cream border border-light-pink/70 p-4 mb-4">
                        <p class="text-[10px] uppercase tracking-wider text-dark/45 font-bold mb-1">Kode Pesanan</p>
                        <span id="order-code" class="ftm-code block">{{ $order->order_code }}</span>
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
                            <span class="label">Status</span>
                            <span class="font-semibold text-secondary capitalize">{{ $order->status }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-light-pink my-4"></div>

                    <div class="flex items-end justify-between">
                        <span class="text-[11px] uppercase tracking-wider text-dark/45 font-semibold">Total</span>
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
            const retryButton = document.getElementById('retry-payment');
            const retryLabel = document.getElementById('retry-label');
            const retryIcon = document.getElementById('retry-icon');
            const alertBox = document.getElementById('pending-alert');
            const alertText = document.getElementById('pending-alert-text');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            const RETRY_URL = @json(route('guest.checkout.retry', $order->order_code));
            const REDIRECT_BASE = @json(route('guest.checkout.success', '__CODE__'));
            const STATUS_URL = @json(route('guest.checkout.status', $order->order_code));
            const ORDER_CODE = @json($order->order_code);
            const autoCheck = document.getElementById('auto-check');

            let pollAttempts = 0;
            let polling = true;

            function pollStatus() {
                if (!polling || pollAttempts >= 25) {
                    if (autoCheck) {
                        autoCheck.innerHTML = '<i class="ri-information-line"></i> Tekan “Saya Sudah Bayar” untuk memeriksa status.';
                    }
                    return;
                }

                pollAttempts += 1;

                fetch(STATUS_URL, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                })
                    .then((response) => response.ok ? response.json() : null)
                    .then((data) => {
                        if (data && data.paid) {
                            polling = false;
                            window.location.href = REDIRECT_BASE.replace('__CODE__', encodeURIComponent(ORDER_CODE));
                            return;
                        }

                        setTimeout(pollStatus, 6000);
                    })
                    .catch(() => setTimeout(pollStatus, 9000));
            }

            setTimeout(pollStatus, 5000);

            function showError(message) {
                alertText.textContent = message;
                alertBox.classList.add('is-visible');
            }

            function setLoading(isLoading) {
                retryButton.disabled = isLoading;

                if (isLoading) {
                    retryLabel.textContent = 'Menyiapkan pembayaran…';
                    retryIcon.className = 'ftm-spin';
                } else {
                    retryLabel.textContent = 'Selesaikan Pembayaran';
                    retryIcon.className = 'ri-secure-payment-line text-lg';
                }
            }

            retryButton.addEventListener('click', function () {
                polling = false;
                alertBox.classList.remove('is-visible');
                setLoading(true);

                fetch(RETRY_URL, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Gagal menyiapkan pembayaran ulang.');
                        }

                        return data;
                    })
                    .then((data) => {
                        if (typeof window.snap === 'undefined') {
                            throw new Error('Modul pembayaran belum siap. Muat ulang halaman lalu coba lagi.');
                        }

                        setLoading(false);

                        window.snap.pay(data.snapToken, {
                            onSuccess: function () {
                                window.location.href = REDIRECT_BASE.replace('__CODE__', encodeURIComponent(data.order_code || '{{ $order->order_code }}'));
                            },
                            onPending: function () {
                                window.location.href = REDIRECT_BASE.replace('__CODE__', encodeURIComponent(data.order_code || '{{ $order->order_code }}'));
                            },
                            onError: function () {
                                showError('Pembayaran gagal diproses. Silakan coba metode lain.');
                            },
                            onClose: function () {
                                polling = true;
                                pollAttempts = 0;
                                setTimeout(pollStatus, 4000);
                                showError('Jendela pembayaran ditutup. Kamu bisa mencoba lagi kapan saja.');
                            },
                        });
                    })
                    .catch((error) => {
                        setLoading(false);
                        showError(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    });
            });
        })();
    </script>
</body>
</html>
