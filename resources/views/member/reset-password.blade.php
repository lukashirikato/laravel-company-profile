<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru | FTM Society</title>
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
            display: flex; justify-content: center;
            gap: 0.5rem; margin: 1rem 0 1.5rem;
        }
        .ftm-step {
            width: 2rem; height: 0.3rem; border-radius: 2px;
            background: #F4C9DF; transition: background 0.3s;
        }
        .ftm-step.active { background: #EE4E8B; }
        .ftm-step.done { background: #1A7A5E; }

        .ftm-input {
            background: #FCF9F2;
            border: 1.5px solid #F4C9DF;
            border-radius: 10px;
            padding: 0.75rem 2.7rem 0.75rem 1rem;
            color: #1C1C1C;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: border-color 0.18s, box-shadow 0.18s;
            width: 100%;
        }
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

        .ftm-alert-error {
            background: rgba(238, 78, 139, 0.10);
            border-left: 4px solid #EE4E8B;
            color: #7A2B4A;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .toggle-eye {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(28, 28, 28, 0.4);
            cursor: pointer;
            transition: color 0.18s;
            background: none;
            border: none;
            padding: 0.25rem;
        }
        .toggle-eye:hover { color: #7A2B4A; }

        .pw-match-status {
            font-size: 0.75rem;
            margin-top: 0.35rem;
            font-weight: 600;
        }
        .pw-match-ok { color: #1A7A5E; }
        .pw-match-no { color: #EE4E8B; }
    </style>
</head>
<body class="flex items-center justify-center px-4 py-8">

    <div class="ftm-card w-full max-w-md p-8">
        {{-- Header --}}
        <div class="text-center mb-2">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                 style="background: #1A7A5E; box-shadow: 0 6px 16px rgba(26, 122, 94, 0.30);">
                <i class="fas fa-lock-open" style="color: #FFFFFF; font-size: 1.5rem;"></i>
            </div>
            <h1 style="color: #7A2B4A; font-size: 1.5rem; font-weight: 800; margin: 0;">
                Buat Password Baru
            </h1>
            <p style="color: rgba(28, 28, 28, 0.55); font-size: 0.85rem; margin-top: 0.25rem;">
                Verifikasi berhasil. Silakan buat password baru.
            </p>
        </div>

        {{-- Step indicator --}}
        <div class="ftm-step-indicator">
            <span class="ftm-step done"></span>
            <span class="ftm-step done"></span>
            <span class="ftm-step active"></span>
        </div>

        <p class="text-center text-xs mb-5" style="color: rgba(28, 28, 28, 0.6);">
            <span style="color: #EE4E8B; font-weight: 700;">Langkah 3 dari 3</span>
            &mdash; Set password baru
        </p>

        @if($errors->any())
            <div class="ftm-alert-error mb-4 text-sm flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('member.forgot-password.reset.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="new_password" class="ftm-label">Password Baru</label>
                <div class="relative">
                    <input type="password"
                           name="new_password"
                           id="new_password"
                           class="ftm-input"
                           placeholder="Minimal 8 karakter"
                           required
                           minlength="8"
                           autofocus>
                    <button type="button" data-toggle="new_password" class="toggle-eye toggle-pw">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="text-xs mt-1" style="color: rgba(28, 28, 28, 0.5);">
                    <i class="fas fa-info-circle mr-1"></i>
                    Gunakan minimal 8 karakter yang mudah Anda ingat.
                </p>
            </div>

            <div>
                <label for="new_password_confirmation" class="ftm-label">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password"
                           name="new_password_confirmation"
                           id="new_password_confirmation"
                           class="ftm-input"
                           placeholder="Ulangi password baru"
                           required
                           minlength="8">
                    <button type="button" data-toggle="new_password_confirmation" class="toggle-eye toggle-pw">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <p id="pw-match" class="pw-match-status" style="display: none;"></p>
            </div>

            <button type="submit" class="ftm-btn-primary mt-2">
                <i class="fas fa-check-circle"></i>
                <span>Simpan Password Baru</span>
            </button>
        </form>
    </div>

    <script>
        // Toggle visibility
        document.querySelectorAll('.toggle-pw').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.toggle);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Live match indicator
        const pw1 = document.getElementById('new_password');
        const pw2 = document.getElementById('new_password_confirmation');
        const status = document.getElementById('pw-match');
        function check() {
            if (!pw2.value) { status.style.display = 'none'; return; }
            status.style.display = 'block';
            if (pw1.value === pw2.value) {
                status.textContent = '\u2713 Password cocok';
                status.className = 'pw-match-status pw-match-ok';
            } else {
                status.textContent = '\u2717 Password belum cocok';
                status.className = 'pw-match-status pw-match-no';
            }
        }
        pw1.addEventListener('input', check);
        pw2.addEventListener('input', check);
    </script>
</body>
</html>
