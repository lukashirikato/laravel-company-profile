<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? 'Checkout' }}</title>

    <!-- ✅ MIDTRANS SNAP (WAJIB DI HEAD & DI ATAS VITE) -->
    <script
        src="{{ config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    {{-- Tailwind & App --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- Navbar --}}
    @yield('navbar')

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
