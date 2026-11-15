<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aktifkan Akun — FTM Society</title>

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
                radial-gradient(circle at 12% 0%, rgba(238, 78, 139, 0.14) 0%, transparent 34%),
                radial-gradient(circle at 90% 12%, rgba(197, 215, 155, 0.32) 0%, transparent 38%),
                radial-gradient(circle at 50% 100%, rgba(122, 43, 74, 0.08) 0%, transparent 55%);
            min-height: 100vh;
        }

        h1, h2, .font-nord {
            font-family: 'Nord', 'Poppins', sans-serif;
            letter-spacing: -0.01em;
        }

        .ftm-shell { width: 100%; max-width: 460px; margin: 0 auto; padding: 0 1rem; }

        .ftm-card {
            background: #fff;
            border: 1px solid rgba(244, 201, 223, 0.85);
            border-radius: 22px;
            box-shadow: 0 22px 46px rgba(122, 43, 74, 0.12), 0 1px 3px rgba(28, 28, 28, 0.04);
            overflow: hidden;
        }

        .ftm-card-top {
            height: 5px;
            background: linear-gradient(90deg, var(--ftm-pink), var(--ftm-cherry));
        }

        .ftm-label {
            display: block;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ftm-cherry);
            margin-bottom: 0.4rem;
        }

        .ftm-input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid rgba(244, 201, 223, 0.95);
            border-radius: 12px;
            background: var(--ftm-cream);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--ftm-ink);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .ftm-input:focus {
            outline: none;
            border-color: var(--ftm-pink);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(238, 78, 139, 0.13);
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
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .ftm-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(122, 43, 74, 0.3);
        }

        .ftm-alert-error {
            background: rgba(238, 78, 139, 0.1);
            border-left: 4px solid var(--ftm-pink);
            color: var(--ftm-cherry);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .ftm-alert-success {
            background: rgba(197, 215, 155, 0.4);
            border-left: 4px solid #1A7A5E;
            color: #1D5A4B;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .ftm-info {
            background: rgba(244, 201, 223, 0.35);
            border: 1px solid rgba(238, 78, 139, 0.2);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.76rem;
            line-height: 1.55;
            color: rgba(28, 28, 28, 0.72);
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center py-8 px-4">
    <div class="ftm-shell">
        <div class="text-center mb-5">
            <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="FTM Society" class="w-14 h-14 rounded-full object-cover mx-auto border-2 border-light-pink shadow-md">
        </div>

        <div class="ftm-card">
            <div class="ftm-card-top"></div>
            <div class="p-6 sm:p-8">
                <div class="text-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-light-pink/50 text-secondary font-semibold text-[11px]">
                        <i class="ri-shield-check-line"></i>
                        Langkah terakhir
                    </span>
                    <h1 class="text-secondary text-2xl font-extrabold mt-3">Aktifkan Akunmu</h1>
                    <p class="text-dark/60 text-sm leading-relaxed mt-1.5">
                        Buat password untuk masuk ke area member dan mulai booking kelas.
                    </p>
                </div>

                <div class="ftm-info mt-5 flex items-start gap-2.5">
                    <i class="ri-mail-check-line text-secondary text-base mt-0.5"></i>
                    <div>
                        <p class="font-semibold text-secondary text-xs">Email terdaftar</p>
                        <p class="break-all mt-0.5">{{ $order->customer->email ?? '-' }}</p>
                        <p class="mt-1 text-dark/50">Gunakan email ini untuk login berikutnya.</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="ftm-alert-error mt-4 flex items-start gap-2">
                        <i class="ri-error-warning-line mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="ftm-alert-success mt-4 flex items-start gap-2">
                        <i class="ri-checkbox-circle-line mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="ftm-alert-error mt-4 flex items-start gap-2">
                        <i class="ri-error-warning-line mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('guest.checkout.activate.submit', $order->order_code) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="password" class="ftm-label">Password Baru</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="ftm-input"
                               placeholder="Minimal 8 karakter"
                               autocomplete="new-password"
                               minlength="8"
                               required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="ftm-label">Ulangi Password</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="ftm-input"
                               placeholder="Ketik ulang password"
                               autocomplete="new-password"
                               minlength="8"
                               required>
                    </div>

                    <button type="submit" class="ftm-btn-primary">
                        <i class="ri-lock-password-line text-lg"></i>
                        Simpan Password & Masuk
                    </button>
                </form>

                <form method="POST" action="{{ route('guest.checkout.activate.resend', $order->order_code) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-center text-[12px] font-semibold text-secondary hover:text-primary transition">
                        <i class="ri-whatsapp-line"></i>
                        Kirim ulang link aktivasi ke WhatsApp
                    </button>
                </form>

                <div class="mt-4 flex items-center justify-center gap-4 text-[11px] text-dark/50">
                    <span class="flex items-center gap-1.5"><i class="ri-lock-2-line text-accent"></i> Terenkripsi</span>
                    <span class="flex items-center gap-1.5"><i class="ri-eye-off-line text-accent"></i> Tidak dibagikan</span>
                </div>

                <p class="text-center text-[11px] text-dark/45 mt-4">
                    Pesanan <span class="font-semibold text-secondary">{{ $order->order_code }}</span> ·
                    {{ $order->package->name ?? '-' }}
                </p>
            </div>
        </div>

        <p class="text-center text-[11px] text-dark/40 mt-6">© {{ date('Y') }} FTM Society · Muslimah-Only Gym</p>
    </div>
</body>
</html>
