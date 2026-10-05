<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanjutkan Verifikasi | FTM Society</title>

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

        .success-alert  { background: linear-gradient(135deg, #1A7A5E 0%, #1D5A4B 100%); }
        .error-alert    { background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); }

        a.brand-link    { color: #7A2B4A; font-weight: 600; }
        a.brand-link:hover { color: #EE4E8B; }

        hr { border-color: #F4C9DF; }

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
            <p class="text-sm font-semibold mb-1" style="color: #7A2B4A;">Lanjutkan Verifikasi OTP</p>
            <p class="text-sm" style="color: rgba(28, 28, 28, 0.65);">
                Sesi verifikasi Anda berakhir atau halaman ditutup.
                Masukkan nomor WhatsApp yang terdaftar untuk melanjutkan.
            </p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="success-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-check-circle flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="error-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-triangle flex-shrink-0"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(!empty($notice))
            <div class="error-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                <span>{{ $notice }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="error-alert text-white p-3 mb-4 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form Resume --}}
        <form method="POST" action="{{ route('member.otp.resume') }}">
            @csrf
            <label class="block text-xs font-semibold mb-1" style="color: #7A2B4A;">Nomor WhatsApp Terdaftar</label>
            <input type="tel"
                   name="phone_number"
                   value="{{ old('phone_number') }}"
                   placeholder="Contoh: 088212345678"
                   maxlength="20"
                   class="phone-input rounded-lg px-4 py-3 text-sm mb-4"
                   autocomplete="tel"
                   required
                   autofocus>

            <button type="submit"
                    class="w-full btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-arrow-right"></i> Lanjutkan Verifikasi
            </button>
        </form>

        <hr class="my-6">

        <div class="text-center text-sm">
            <p style="color: rgba(28,28,28,0.65);">
                Sudah aktif?
                <a href="{{ route('member.login') }}" class="brand-link hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

</body>
</html>
