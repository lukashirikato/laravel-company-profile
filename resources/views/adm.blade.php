<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - FTM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-secondary mb-6 text-center">Admin Login</h2>
        @if(session('error'))
            <div class="mb-4 text-secondary text-center">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-dark mb-2" for="email">Email</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="admin@example.com">
            </div>
            <div class="mb-6">
                <label class="block text-dark mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="********">
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-primary text-white rounded hover:bg-secondary transition font-semibold">
                Login
            </button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('admin.register.form') }}" class="text-primary hover:underline">Belum punya akun? Register</a>
        </div>
    </div>
</body>
</html>