<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member | FTM Society Gym Muslimah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Brand Palette: Burnt Cherry #7A2B4A | Power Pink #EE4E8B |
           Soft Petals #F4C9DF | Patina Green #1A7A5E | Springs Ivy #1D5A4B |
           Grounded Green #C5D79B | Layl #1C1C1C | Rising #FCF9F2 */

        .custom-gradient {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 55%, #F4C9DF 100%);
        }

        .card-glass {
            background: rgba(244, 238, 230, 0.97);
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

        .success-alert {
            background: linear-gradient(135deg, #1A7A5E 0%, #1D5A4B 100%);
        }

        .error-alert {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
        }

        hr { border-color: #F4C9DF; }

        a.brand-link {
            color: #7A2B4A;
            font-weight: 600;
        }
        a.brand-link:hover {
            color: #EE4E8B;
        }
    </style>
</head>
<body class="custom-gradient min-h-screen flex items-center justify-center px-4">

    <div class="card-glass shadow-2xl rounded-2xl p-8 w-full max-w-md border border-[#F4C9DF]">
        <div class="text-center mb-6">
            <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="Logo Gym" class="w-20 h-20 mx-auto mb-3 rounded-full logo-shadow">
            <h1 class="text-3xl font-bold text-[#7A2B4A]">Selamat Datang</h1>
            <p class="text-sm text-[#1C1C1C]/60">FTM Society</p>
        </div>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="success-alert text-white p-3 mb-4 rounded text-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Notifikasi error --}}
        @if($errors->any())
            <div class="error-alert text-white p-3 mb-4 rounded text-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        {{-- Form login --}}
        <form method="POST" action="{{ route('member.login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="block text-sm text-[#1C1C1C]/80 mb-1 font-medium">Email atau Nomor HP</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                    placeholder="Masukkan email atau nomor HP"
                    class="w-full px-4 py-3 border border-[#F4C9DF] rounded-lg focus:outline-none input-focus transition-all duration-200 bg-[#FCF9F2] text-[#1C1C1C] placeholder-[#1C1C1C]/40">
            </div>

            <div>
                <label for="password" class="block text-sm text-[#1C1C1C]/80 mb-1 font-medium">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3 border border-[#F4C9DF] rounded-lg focus:outline-none input-focus transition-all duration-200 bg-[#FCF9F2] text-[#1C1C1C] placeholder-[#1C1C1C]/40 pr-12">
                    <button type="button" 
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#1C1C1C]/40 hover:text-[#7A2B4A] transition-colors">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="text-right -mt-2">
                <a href="{{ route('member.forgot-password.form') }}" class="text-sm brand-link hover:underline">
                    Lupa Password?
                </a>
            </div>

            <button type="submit"
                class="w-full btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-sm">
                Masuk
            </button>
        </form>


        <hr class="my-6">

        <div class="text-center text-sm">
            Belum memiliki akun?
            <a href="{{ route('home') }}#join" class="brand-link hover:underline">Daftar Sekarang</a>
        </div>

        <p class="text-center text-xs text-[#1C1C1C]/40 mt-4">
            Data Anda aman dan terlindungi
        </p>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

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
