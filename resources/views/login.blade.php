<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | FTM Society</title>

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
        }

        .input-focus:focus {
            border-color: #EE4E8B;
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.18);
        }

        .btn-primary {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1C1C1C 0%, #7A2B4A 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(122, 43, 74, 0.35);
        }

        .logo-shadow {
            box-shadow: 0 4px 16px rgba(122, 43, 74, 0.25);
        }

        .alert-error   { background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); }
        .alert-warning { background: linear-gradient(135deg, #C8851C 0%, #A66B12 100%); }

        a.brand-link  { color: #7A2B4A; font-weight: 600; }
        a.brand-link:hover { color: #EE4E8B; }

        hr { border-color: #F4C9DF; }
    </style>
</head>
<body class="brand-gradient min-h-screen flex items-center justify-center px-4 py-8">

    <div class="card-glass shadow-2xl rounded-2xl p-8 w-full max-w-md border border-[#F4C9DF]">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-3 rounded-full flex items-center justify-center logo-shadow"
                 style="background: linear-gradient(135deg, #FCF9F2 0%, #F4C9DF 100%);">
                <i class="fas fa-user-shield text-3xl" style="color: #7A2B4A;"></i>
            </div>
            <h1 class="text-2xl font-bold flex items-center justify-center gap-2" style="color: #7A2B4A;">
                <span class="font-nord font-black" style="color: #EE4E8B;">FTM</span>
                <span class="font-instrument italic" style="color: #7A2B4A;">Admin</span>
            </h1>
            <p class="text-sm mt-2" style="color: rgba(28, 28, 28, 0.65);">
                Masuk untuk mengelola sistem FTM Society
            </p>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert-error text-white p-3 mb-4 rounded text-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert-warning text-white p-3 mb-4 rounded text-sm flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm mb-1 font-medium" style="color: rgba(28, 28, 28, 0.8);">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    placeholder="admin@example.com"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none input-focus transition-all duration-200"
                    style="border-color: #F4C9DF; background: #FCF9F2; color: #1C1C1C;">
            </div>

            <div>
                <label for="password" class="block text-sm mb-1 font-medium" style="color: rgba(28, 28, 28, 0.8);">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3 pr-12 border rounded-lg focus:outline-none input-focus transition-all duration-200"
                        style="border-color: #F4C9DF; background: #FCF9F2; color: #1C1C1C;">
                    <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 transition-colors"
                            style="color: rgba(28, 28, 28, 0.4);">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-sm flex items-center justify-center gap-2 font-nord">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <hr class="my-6">

        <div class="text-center text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="brand-link hover:underline">Register Admin</a>
        </div>

        <p class="text-center text-xs mt-4" style="color: rgba(28, 28, 28, 0.4);">
            Akses terbatas hanya untuk admin terdaftar.
        </p>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const eyeIcon  = document.getElementById('eyeIcon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
