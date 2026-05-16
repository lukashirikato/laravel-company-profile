<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | FTM Society</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <style>
        /* FTM Society — Brand Palette */
        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            background-color: #FCF9F2;
            background-image:
                radial-gradient(circle at 12% 18%, rgba(238, 78, 139, 0.10) 0%, transparent 35%),
                radial-gradient(circle at 88% 82%, rgba(244, 201, 223, 0.45) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(122, 43, 74, 0.05) 0%, transparent 60%);
            min-height: 100vh;
        }

        h1, h2, h3, h4 {
            font-family: 'Nord', 'Poppins', sans-serif;
            letter-spacing: -0.01em;
        }

        .ftm-card {
            background: #FFFFFF;
            border: 1px solid #F4C9DF;
            border-radius: 18px;
            box-shadow:
                0 12px 30px rgba(122, 43, 74, 0.10),
                0 1px 3px rgba(28, 28, 28, 0.04);
            position: relative;
            overflow: hidden;
        }
        .ftm-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: #EE4E8B;
        }

        .ftm-input {
            background: #FCF9F2;
            border: 1.5px solid #F4C9DF;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #1C1C1C;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: border-color 0.18s, box-shadow 0.18s;
            width: 100%;
        }
        .ftm-input::placeholder { color: rgba(28, 28, 28, 0.4); }
        .ftm-input:focus {
            outline: none;
            border-color: #EE4E8B;
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.15);
            background: #FFFFFF;
        }

        .ftm-label {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #7A2B4A;
            margin-bottom: 0.4rem;
            display: block;
        }

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
            transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .ftm-btn-primary:hover {
            background: #7A2B4A;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(122, 43, 74, 0.32);
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
        .ftm-info {
            background: #F4C9DF;
            border-left: 4px solid #7A2B4A;
            color: #7A2B4A;
            border-radius: 8px;
            padding: 0.85rem 1rem;
            font-size: 0.78rem;
            line-height: 1.55;
        }

        .ftm-step-indicator {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1rem 0 1.5rem;
        }
        .ftm-step {
            width: 2rem;
            height: 0.3rem;
            border-radius: 2px;
            background: #F4C9DF;
            transition: background 0.3s;
        }
        .ftm-step.active { background: #EE4E8B; }
        .ftm-step.done { background: #1A7A5E; }

        .ftm-link {
            color: #7A2B4A;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.18s;
        }
        .ftm-link:hover { color: #EE4E8B; }
    </style>
</head>
<body class="flex items-center justify-center px-4 py-8">

    <div class="ftm-card w-full max-w-md p-8">
        {{-- Header --}}
        <div class="text-center mb-2">
            <img src="{{ asset('icons/logo-ftm.jpg') }}"
                 alt="FTM Society"
                 class="w-16 h-16 mx-auto mb-3 rounded-full"
                 style="box-shadow: 0 4px 12px rgba(122, 43, 74, 0.20); border: 2px solid #F4C9DF;">
            <h1 style="color: #7A2B4A; font-size: 1.6rem; font-weight: 800; margin: 0;">
                Lupa Password
            </h1>
            <p style="color: rgba(28, 28, 28, 0.55); font-size: 0.85rem; margin-top: 0.25rem;">
                FTM Society Member
            </p>
        </div>

        {{-- Step indicator --}}
        <div class="ftm-step-indicator">
            <span class="ftm-step active" title="Verifikasi"></span>
            <span class="ftm-step" title="OTP"></span>
            <span class="ftm-step" title="Password baru"></span>
        </div>

        <p class="text-center text-xs mb-5" style="color: rgba(28, 28, 28, 0.6);">
            <span style="color: #EE4E8B; font-weight: 700;">Langkah 1 dari 3</span>
            &mdash; Verifikasi identitas
        </p>

        {{-- Notif sukses --}}
        @if(session('success'))
            <div class="ftm-alert-success mb-4 text-sm flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Notif error --}}
        @if($errors->any())
            <div class="ftm-alert-error mb-4 text-sm flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Info --}}
        <div class="ftm-info mb-5 flex items-start gap-2">
            <i class="fas fa-shield-alt mt-0.5"></i>
            <span>
                Masukkan <strong>email</strong> dan <strong>nomor HP</strong> yang terdaftar.
                Kami akan mengirim kode OTP ke WhatsApp Anda untuk verifikasi.
            </span>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('member.forgot-password.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="ftm-label">Email Terdaftar</label>
                <input type="email"
                       name="email"
                       id="email"
                       class="ftm-input"
                       value="{{ old('email') }}"
                       placeholder="contoh@email.com"
                       required
                       autofocus>
            </div>

            <div>
                <label for="phone_number" class="ftm-label">Nomor HP Terdaftar</label>
                <input type="tel"
                       name="phone_number"
                       id="phone_number"
                       class="ftm-input"
                       value="{{ old('phone_number') }}"
                       placeholder="08xxxxxxxxxx"
                       required>
            </div>

            <button type="submit" class="ftm-btn-primary mt-2">
                <i class="fab fa-whatsapp"></i>
                <span>Kirim Kode OTP via WhatsApp</span>
            </button>
        </form>

        <hr class="my-6" style="border-color: #F4C9DF;">

        <div class="text-center text-sm" style="color: rgba(28, 28, 28, 0.7);">
            Ingat password Anda?
            <a href="{{ route('member.login.form') }}" class="ftm-link">Kembali ke Login</a>
        </div>

        <p class="text-center text-xs mt-4" style="color: rgba(28, 28, 28, 0.45);">
            Jika mengalami kendala, hubungi admin via WhatsApp.
        </p>
    </div>

</body>
</html>
