<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member | FTM Society Gym Muslimah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .custom-gradient {
            background: linear-gradient(135deg, #BD8686 0%, #F7C6C6 35%, #D7B9A3 100%);
        }

        .input-focus:focus {
            border-color: #8C5A5A;
            box-shadow: 0 0 0 3px rgba(140, 90, 90, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #603943 0%, #8C5A5A 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #432129 0%, #603943 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 33, 41, 0.3);
        }

        .logo-shadow {
            box-shadow: 0 4px 12px rgba(140, 90, 90, 0.15);
        }

        .success-alert {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }

        .error-alert {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        }
    </style>
</head>
<body class="custom-gradient min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="Logo Gym" class="w-20 h-20 mx-auto mb-3 rounded-full logo-shadow">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-sm text-gray-500">FTM Society</p>
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
                <label for="login" class="block text-sm text-gray-700 mb-1">Email atau Nomor HP</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                    placeholder="Masukkan email atau nomor HP"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all duration-200 bg-white">
            </div>

            <div>
                <label for="password" class="block text-sm text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all duration-200 bg-white pr-12">
                    <button type="button" 
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full btn-primary text-white px-6 py-3 rounded-xl font-semibold shadow-sm">
                Masuk
            </button>
        </form>


        <hr class="my-6">

        <div class="text-center text-sm">
            Belum memiliki akun?
            <a href="{{ route('home') }}#join" class="text-purple-700 font-semibold hover:underline">Daftar Sekarang</a>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
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
