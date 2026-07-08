<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | FTM Society</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <style>
        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            background-color: #FCF9F2;
            background-image:
                radial-gradient(circle at 12% 18%, rgba(238, 78, 139, 0.10) 0%, transparent 35%),
                radial-gradient(circle at 88% 82%, rgba(244, 201, 223, 0.45) 0%, transparent 40%);
            min-height: 100vh;
        }
        h1, h2, h3 { font-family: 'Nord', 'Poppins', sans-serif; letter-spacing: -0.01em; }

        .ftm-card {
            background: #FFFFFF;
            border: 1px solid #F4C9DF;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(122, 43, 74, 0.10);
            position: relative;
            overflow: hidden;
        }
        .ftm-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 4px; background: #EE4E8B;
        }

        .ftm-step-indicator {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1rem 0 1.5rem;
        }
        .ftm-step {
            width: 2rem; height: 0.3rem;
            border-radius: 2px;
            background: #F4C9DF;
            transition: background 0.3s;
        }
        .ftm-step.active { background: #EE4E8B; }
        .ftm-step.done { background: #1A7A5E; }

        /* OTP Input boxes */
        .otp-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0.5rem;
            margin: 0 auto;
            max-width: 100%;
        }
        .otp-box {
            width: 100%;
            aspect-ratio: 1;
            min-height: 48px;
            text-align: center;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: #7A2B4A;
            background: #FCF9F2;
            border: 2px solid #F4C9DF;
            border-radius: 10px;
            transition: border-color 0.18s, background 0.18s, transform 0.12s;
        }
        .otp-box:focus {
            outline: none;
            border-color: #EE4E8B;
            background: #FFFFFF;
            transform: scale(1.05);
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.18);
        }
        .otp-box.filled { background: #FFFFFF; border-color: #EE4E8B; color: #1C1C1C; }

        .ftm-btn-primary {
            background: #EE4E8B;
            color: #FFFFFF;
            border-radius: 10px;
            padding: 0.85rem 1.25rem;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            box-shadow: 0 6px 16px rgba(238, 78, 139, 0.30);
            transition: background 0.18s, transform 0.15s;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .ftm-btn-primary:hover { background: #7A2B4A; transform: translateY(-1px); }
        .ftm-btn-primary:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

        .ftm-btn-resend {
            background: transparent;
            color: #7A2B4A;
            border: 1.5px solid #F4C9DF;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: background 0.18s, border-color 0.18s, color 0.18s;
            cursor: pointer;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .ftm-btn-resend:hover:not(:disabled) {
            background: #F4C9DF;
            border-color: #EE4E8B;
            color: #7A2B4A;
        }
        .ftm-btn-resend:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .ftm-alert-success {
            background: #C5D79B;
            border-left: 4px solid #1A7A5E;
            color: #1D5A4B;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .ftm-alert-error {
            background: rgba(238, 78, 139, 0.10);
            border-left: 4px solid #EE4E8B;
            color: #7A2B4A;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .ftm-link { color: #7A2B4A; font-weight: 700; text-decoration: none; }
        .ftm-link:hover { color: #EE4E8B; }

        .countdown-text {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            color: #EE4E8B;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body class="flex items-center justify-center px-4 py-8">

    <div class="ftm-card w-full max-w-md p-8">
        {{-- Header --}}
        <div class="text-center mb-2">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                 style="background: #EE4E8B; box-shadow: 0 6px 16px rgba(238, 78, 139, 0.30);">
                <i class="fab fa-whatsapp" style="color: #FFFFFF; font-size: 1.8rem;"></i>
            </div>
            <h1 style="color: #7A2B4A; font-size: 1.5rem; font-weight: 800; margin: 0;">
                Verifikasi OTP
            </h1>
            <p style="color: rgba(28, 28, 28, 0.55); font-size: 0.85rem; margin-top: 0.25rem;">
                Masukkan kode dari WhatsApp
            </p>
        </div>

        {{-- Step indicator --}}
        <div class="ftm-step-indicator">
            <span class="ftm-step done"></span>
            <span class="ftm-step active"></span>
            <span class="ftm-step"></span>
        </div>

        <p class="text-center text-xs mb-5" style="color: rgba(28, 28, 28, 0.6);">
            <span style="color: #EE4E8B; font-weight: 700;">Langkah 2 dari 3</span>
            &mdash; Verifikasi OTP
        </p>

        {{-- Pesan info nomor tujuan --}}
        <div class="text-center mb-5 p-3 rounded-lg"
             style="background: #FCF9F2; border: 1px solid #F4C9DF;">
            <p class="text-xs mb-1" style="color: rgba(28, 28, 28, 0.55);">
                Kode {{ $codeLength }} digit dikirim ke WhatsApp
            </p>
            <p class="text-base font-bold" style="color: #7A2B4A; letter-spacing: 0.05em;">
                <i class="fas fa-mobile-alt mr-1" style="color: #EE4E8B;"></i>
                {{ $phoneMasked }}
            </p>
        </div>

        {{-- Notif --}}
        @if(session('success'))
            <div class="ftm-alert-success mb-4 text-sm flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="ftm-alert-error mb-4 text-sm flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form OTP --}}
        <form method="POST" action="{{ route('member.forgot-password.otp.verify') }}" id="otp-form" class="space-y-5">
            @csrf
            <input type="hidden" name="code" id="otp-code-hidden">

            <div class="otp-grid" id="otp-input-group">
                @for ($i = 0; $i < $codeLength; $i++)
                    <input type="text"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           maxlength="1"
                           class="otp-box"
                           data-index="{{ $i }}"
                           autocomplete="one-time-code"
                           {{ $i === 0 ? 'autofocus' : '' }}>
                @endfor
            </div>

            @if($expiresAt)
                <p class="text-center text-xs" style="color: rgba(28, 28, 28, 0.55);">
                    Kode berlaku selama
                    <span class="countdown-text" id="otp-expires-display" data-expires="{{ \Carbon\Carbon::parse($expiresAt)->timestamp }}">
                        --:--
                    </span>
                </p>
            @endif

            <button type="submit" class="ftm-btn-primary" id="otp-submit-btn" disabled>
                <i class="fas fa-shield-check"></i>
                <span>Verifikasi Kode</span>
            </button>
        </form>

        {{-- Resend --}}
        <form method="POST" action="{{ route('member.forgot-password.otp.resend') }}" class="mt-3">
            @csrf
            <button type="submit"
                    class="ftm-btn-resend"
                    id="resend-btn"
                    @if($cooldownSeconds > 0) disabled @endif>
                <i class="fas fa-paper-plane"></i>
                <span id="resend-label">
                    @if($cooldownSeconds > 0)
                        Kirim ulang dalam <span id="resend-countdown">{{ $cooldownSeconds }}</span>s
                    @else
                        Kirim Ulang Kode
                    @endif
                </span>
            </button>
        </form>

        <hr class="my-6" style="border-color: #F4C9DF;">

        <div class="text-center text-sm" style="color: rgba(28, 28, 28, 0.7);">
            Salah nomor?
            <a href="{{ route('member.forgot-password.form') }}" class="ftm-link">Ganti email/HP</a>
        </div>
    </div>

    <script>
        /* ============================================================
           OTP — auto-focus, paste support, auto-submit when filled
           ============================================================ */
        (function() {
            const inputs   = document.querySelectorAll('.otp-box');
            const hidden   = document.getElementById('otp-code-hidden');
            const submit   = document.getElementById('otp-submit-btn');
            const form     = document.getElementById('otp-form');
            const len      = inputs.length;

            function syncHiddenAndButton() {
                let value = '';
                inputs.forEach(inp => {
                    value += inp.value;
                    inp.classList.toggle('filled', !!inp.value);
                });
                hidden.value = value;
                submit.disabled = value.length !== len;
            }

            inputs.forEach((inp, idx) => {
                // Only allow digits
                inp.addEventListener('input', (e) => {
                    inp.value = inp.value.replace(/\D/g, '').slice(0, 1);
                    syncHiddenAndButton();
                    if (inp.value && idx < len - 1) {
                        inputs[idx + 1].focus();
                    }
                    // Auto submit when all filled
                    if (hidden.value.length === len) {
                        setTimeout(() => form.submit(), 150);
                    }
                });

                // Backspace -> jump back
                inp.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !inp.value && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                    if (e.key === 'ArrowLeft' && idx > 0) inputs[idx - 1].focus();
                    if (e.key === 'ArrowRight' && idx < len - 1) inputs[idx + 1].focus();
                });

                // Paste
                inp.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const txt = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = txt.replace(/\D/g, '').slice(0, len);
                    digits.split('').forEach((d, i) => {
                        if (inputs[i]) inputs[i].value = d;
                    });
                    syncHiddenAndButton();
                    if (digits.length === len) {
                        inputs[len - 1].focus();
                        setTimeout(() => form.submit(), 200);
                    } else if (digits.length > 0) {
                        inputs[Math.min(digits.length, len - 1)].focus();
                    }
                });
            });

            /* ============================================================
               Resend countdown
               ============================================================ */
            const cooldown    = {{ $cooldownSeconds ?? 0 }};
            const resendBtn   = document.getElementById('resend-btn');
            const resendLabel = document.getElementById('resend-label');
            const cdSpan      = document.getElementById('resend-countdown');

            if (cooldown > 0 && resendBtn && cdSpan) {
                let remaining = cooldown;
                const timer = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        resendLabel.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Ulang Kode';
                    } else {
                        cdSpan.textContent = remaining;
                    }
                }, 1000);
            }

            /* ============================================================
               OTP Expiry countdown
               ============================================================ */
            const otpExpiryEl = document.getElementById('otp-expires-display');
            if (otpExpiryEl) {
                const expiresAt = parseInt(otpExpiryEl.dataset.expires) * 1000;
                function tickOtpExpiry() {
                    const diff = expiresAt - Date.now();
                    if (diff <= 0) {
                        otpExpiryEl.textContent = 'Kedaluwarsa';
                        otpExpiryEl.style.color = '#EE4E8B';
                        return;
                    }
                    const m = Math.floor(diff / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    otpExpiryEl.textContent = m + ':' + String(s).padStart(2, '0');
                    setTimeout(tickOtpExpiry, 1000);
                }
                tickOtpExpiry();
            }
        })();
    </script>
</body>
</html>
