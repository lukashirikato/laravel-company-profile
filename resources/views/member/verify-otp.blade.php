<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | FTM Society</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- FTM Brand Typography --}}
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">

    <style>
        /* FTM Brand Palette 2025
           Power Pink #EE4E8B | Burnt Cherry #7A2B4A | Soft Petals #F4C9DF
           Patina Green #1A7A5E | Layl #1C1C1C | Rising #FCF9F2 */

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: #1C1C1C;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            font-family: 'Nord', 'Poppins', sans-serif;
            letter-spacing: -0.015em;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 55%, #F4C9DF 100%);
        }

        .card-glass {
            background: rgba(252, 249, 242, 0.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
        }

        /* Supergraphic petal dekoratif — sudut kanan bawah */
        .card-glass::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cellipse cx='100' cy='55' rx='38' ry='55' fill='%23EE4E8B' opacity='0.08' transform='rotate(0 100 100)'/%3E%3Cellipse cx='100' cy='55' rx='38' ry='55' fill='%23EE4E8B' opacity='0.08' transform='rotate(90 100 100)'/%3E%3Cellipse cx='100' cy='55' rx='38' ry='55' fill='%23EE4E8B' opacity='0.08' transform='rotate(180 100 100)'/%3E%3Cellipse cx='100' cy='55' rx='38' ry='55' fill='%23EE4E8B' opacity='0.08' transform='rotate(270 100 100)'/%3E%3Ccircle cx='100' cy='100' r='14' fill='%237A2B4A' opacity='0.10'/%3E%3C/svg%3E") no-repeat center/contain;
            pointer-events: none;
            z-index: 0;
        }

        .card-glass > * {
            position: relative;
            z-index: 1;
        }

        .logo-shadow {
            box-shadow: 0 4px 16px rgba(122, 43, 74, 0.25);
        }

        .otp-input {
            width: 48px;
            height: 58px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border: 2px solid #F4C9DF;
            border-radius: 14px;
            background: #FCF9F2;
            color: #1C1C1C;
            transition: all 0.2s ease;
            font-family: 'Nord', 'Poppins', sans-serif;
        }
        .otp-input:focus {
            outline: none;
            border-color: #EE4E8B;
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.18);
            background: #FFFFFF;
        }
        .otp-input.filled {
            border-color: #7A2B4A;
            background: #FFFFFF;
            box-shadow: 0 2px 8px rgba(122, 43, 74, 0.12);
        }

        .btn-primary {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            transition: all 0.3s ease;
            font-family: 'Nord', 'Poppins', sans-serif;
        }
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #1C1C1C 0%, #7A2B4A 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(122, 43, 74, 0.35);
        }
        .btn-primary:disabled {
            opacity: 0.50;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #FCF9F2;
            border: 1px solid #F4C9DF;
            color: #7A2B4A;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover { background: #F4C9DF; }

        .success-alert  { background: linear-gradient(135deg, #1A7A5E 0%, #1D5A4B 100%); }
        .error-alert    { background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); }
        .warning-alert  { background: linear-gradient(135deg, #C8851C 0%, #A66B12 100%); }

        a.brand-link    { color: #7A2B4A; font-weight: 600; }
        a.brand-link:hover { color: #EE4E8B; }

        .change-phone-panel {
            display: none;
            border: 1px solid #F4C9DF;
            background: rgba(255, 255, 255, 0.68);
        }
        .change-phone-panel.active { display: block; }

        .phone-input {
            width: 100%;
            border: 1px solid #F4C9DF;
            background: #FCF9F2;
            color: #1C1C1C;
            transition: all 0.2s ease;
        }
        .phone-input:focus {
            outline: none;
            border-color: #EE4E8B;
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.14);
            background: #FFFFFF;
        }

        hr { border-color: #F4C9DF; }

        .masked-phone {
            font-family: 'Nord', 'Poppins', sans-serif;
            letter-spacing: 0.08em;
        }

        .expiry-badge {
            background: rgba(244, 201, 223, 0.35);
            border: 1px solid #F4C9DF;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            font-size: 0.75rem;
            color: rgba(28, 28, 28, 0.65);
        }

        .expiry-badge .expiry-time {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            color: #7A2B4A;
        }
    </style>
</head>
<body class="brand-gradient min-h-screen flex items-center justify-center px-4 py-8">

    <div class="card-glass shadow-2xl rounded-2xl p-8 w-full max-w-md border border-[#F4C9DF]">

        {{-- Header Logo & Wordmark --}}
        <div class="text-center mb-6">

            {{-- Logo Logogram --}}
            <div class="w-20 h-20 mx-auto mb-3 rounded-full flex items-center justify-center logo-shadow"
                 style="background: linear-gradient(135deg, #FCF9F2 0%, #F4C9DF 100%);">
                <img src="{{ asset('images/LOGOGRAM PINK.png') }}"
                     alt="FTM Society Logogram"
                     class="w-14 h-14 object-contain">
            </div>

            {{-- Wordmark --}}
            <h1 class="text-2xl font-bold flex items-center justify-center gap-1 mb-1">
                <span class="font-nord font-black" style="color: #EE4E8B; letter-spacing: -0.02em;">FTM</span>
                <span class="font-instrument italic" style="color: #7A2B4A;">Society</span>
            </h1>

            {{-- Divider ornamen --}}
            <div class="flex items-center justify-center gap-2 mb-3">
                <div style="width:28px; height:1px; background: linear-gradient(90deg, transparent, #F4C9DF);"></div>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <ellipse cx="12" cy="5" rx="5" ry="7" fill="#EE4E8B" opacity="0.5" transform="rotate(0 12 12)"/>
                    <ellipse cx="12" cy="5" rx="5" ry="7" fill="#EE4E8B" opacity="0.5" transform="rotate(90 12 12)"/>
                    <ellipse cx="12" cy="5" rx="5" ry="7" fill="#EE4E8B" opacity="0.5" transform="rotate(180 12 12)"/>
                    <ellipse cx="12" cy="5" rx="5" ry="7" fill="#EE4E8B" opacity="0.5" transform="rotate(270 12 12)"/>
                    <circle cx="12" cy="12" r="2.5" fill="#7A2B4A" opacity="0.6"/>
                </svg>
                <div style="width:28px; height:1px; background: linear-gradient(90deg, #F4C9DF, transparent);"></div>
            </div>

            {{-- Subtitle --}}
            <p class="text-sm font-semibold mb-1" style="color: #7A2B4A;">Verifikasi Nomor WhatsApp</p>
            <p class="text-sm" style="color: rgba(28, 28, 28, 0.65);">
                Kami telah mengirim kode <strong>{{ $codeLength }} digit</strong> ke:
            </p>
            <p class="masked-phone text-base font-bold mt-1" style="color: #7A2B4A;">
                {{ $maskedPhone }}
            </p>
            <button type="button" id="toggleChangePhone"
                    class="brand-link text-xs mt-2 inline-flex items-center gap-1 hover:underline">
                <i class="fas fa-pen-to-square"></i> Nomor salah? Ganti nomor
            </button>
        </div>

        {{-- Panel Ganti Nomor --}}
        <div id="changePhonePanel"
             class="change-phone-panel rounded-xl p-4 mb-4 {{ $errors->has('phone_number') ? 'active' : '' }}">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white flex-shrink-0"
                     style="background: linear-gradient(135deg, #7A2B4A, #EE4E8B);">
                    <i class="fab fa-whatsapp text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-sm" style="color: #7A2B4A;">Perbarui Nomor WhatsApp</p>
                    <p class="text-xs" style="color: rgba(28,28,28,0.60);">
                        Masukkan nomor aktif. OTP baru akan dikirim ke nomor ini.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('member.otp.change-phone') }}" class="space-y-3">
                @csrf
                <input type="tel"
                       name="phone_number"
                       value="{{ old('phone_number') }}"
                       placeholder="Contoh: 088212345678"
                       maxlength="20"
                       class="phone-input rounded-lg px-4 py-3 text-sm"
                       autocomplete="tel">
                <div class="flex gap-2">
                    <button type="button" id="cancelChangePhone"
                            class="btn-secondary flex-1 px-4 py-2 rounded-lg text-sm font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn-primary flex-1 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                        Kirim OTP Baru
                    </button>
                </div>
            </form>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="success-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-check-circle flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="warning-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-triangle flex-shrink-0"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="error-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form OTP --}}
        <form method="POST" action="{{ route('member.otp.verify') }}" id="otpForm">
            @csrf

            {{-- Hidden field untuk menampung kode utuh --}}
            <input type="hidden" name="code" id="codeInput" maxlength="{{ $codeLength }}">

            {{-- 6 input boxes --}}
            <div class="flex justify-center gap-2 mb-3" id="otpBoxes">
                @for($i = 0; $i < $codeLength; $i++)
                    <input type="text"
                           inputmode="numeric"
                           pattern="[0-9]"
                           maxlength="1"
                           class="otp-input"
                           data-index="{{ $i }}"
                           autocomplete="one-time-code"
                           @if($i === 0) autofocus @endif>
                @endfor
            </div>

            {{-- Countdown expiration --}}
            @if($expiresAt)
                <div class="flex justify-center mb-5">
                    <div class="expiry-badge">
                        <i class="fas fa-clock" style="color:#EE4E8B;"></i>
                        OTP berlaku selama
                        <span class="expiry-time" id="expiryTime" data-expires="{{ $expiresAt->timestamp }}">--:--</span>
                    </div>
                </div>
            @endif

            <button type="submit"
                    id="submitBtn"
                    class="w-full btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-sm flex items-center justify-center gap-2"
                    disabled>
                <i class="fas fa-check-circle"></i> Verifikasi Sekarang
            </button>
        </form>

        <hr class="my-6">

        {{-- Resend OTP --}}
        <div class="text-center text-sm">
            <p class="mb-2" style="color: rgba(28,28,28,0.65);">Tidak menerima kode?</p>
            <form method="POST" action="{{ route('member.otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit"
                        id="resendBtn"
                        class="brand-link hover:underline disabled:opacity-50 disabled:cursor-not-allowed disabled:no-underline inline-flex items-center gap-1">
                    <span id="resendText">
                        @if($cooldownSeconds > 0)
                            <i class="fas fa-clock mr-1"></i>
                            Kirim Ulang dalam <span id="cooldownCounter">{{ $cooldownSeconds }}</span>s
                        @else
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Ulang OTP
                        @endif
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // ============================================================
        // OTP INPUT HANDLER
        // ============================================================
        const inputs     = document.querySelectorAll('.otp-input');
        const codeInput  = document.getElementById('codeInput');
        const submitBtn  = document.getElementById('submitBtn');
        const codeLength = inputs.length;
        const changePhonePanel  = document.getElementById('changePhonePanel');
        const toggleChangePhone = document.getElementById('toggleChangePhone');
        const cancelChangePhone = document.getElementById('cancelChangePhone');

        toggleChangePhone?.addEventListener('click', () => {
            changePhonePanel.classList.add('active');
            changePhonePanel.querySelector('input[name="phone_number"]')?.focus();
        });

        cancelChangePhone?.addEventListener('click', () => {
            changePhonePanel.classList.remove('active');
        });

        function updateHiddenInput() {
            const code = Array.from(inputs).map(i => i.value).join('');
            codeInput.value = code;
            submitBtn.disabled = code.length !== codeLength;
        }

        inputs.forEach((input, idx) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);

                if (e.target.value) {
                    e.target.classList.add('filled');
                    if (idx < codeLength - 1) inputs[idx + 1].focus();
                } else {
                    e.target.classList.remove('filled');
                }

                updateHiddenInput();

                if (codeInput.value.length === codeLength) {
                    setTimeout(() => document.getElementById('otpForm').submit(), 200);
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                    inputs[idx - 1].focus();
                    inputs[idx - 1].value = '';
                    inputs[idx - 1].classList.remove('filled');
                    updateHiddenInput();
                }
                if (e.key === 'ArrowLeft'  && idx > 0)              inputs[idx - 1].focus();
                if (e.key === 'ArrowRight' && idx < codeLength - 1) inputs[idx + 1].focus();
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData)
                    .getData('text')
                    .replace(/\D/g, '')
                    .slice(0, codeLength);

                pasted.split('').forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                        inputs[i].classList.add('filled');
                    }
                });
                updateHiddenInput();

                if (codeInput.value.length === codeLength) {
                    setTimeout(() => document.getElementById('otpForm').submit(), 200);
                }
            });
        });

        // ============================================================
        // RESEND COOLDOWN
        // ============================================================
        const resendBtn  = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');
        const cooldownEl = document.getElementById('cooldownCounter');
        let cooldown     = {{ $cooldownSeconds }};

        function tickCooldown() {
            if (cooldown <= 0) {
                resendBtn.disabled = false;
                resendText.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Kirim Ulang OTP';
                return;
            }
            cooldown--;
            if (cooldownEl) cooldownEl.textContent = cooldown;
            setTimeout(tickCooldown, 1000);
        }

        if (cooldown > 0) {
            resendBtn.disabled = true;
            tickCooldown();
        }

        // ============================================================
        // OTP EXPIRY COUNTDOWN
        // ============================================================
        const expiryEl = document.getElementById('expiryTime');
        if (expiryEl) {
            const expiresAt = parseInt(expiryEl.dataset.expires) * 1000;
            function tickExpiry() {
                const diff = expiresAt - Date.now();
                if (diff <= 0) {
                    expiryEl.textContent = 'Kedaluwarsa';
                    expiryEl.style.color = '#EE4E8B';
                    return;
                }
                const m = Math.floor(diff / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                expiryEl.textContent = m + ':' + String(s).padStart(2, '0');
                setTimeout(tickExpiry, 1000);
            }
            tickExpiry();
        }
    </script>
</body>
</html>
