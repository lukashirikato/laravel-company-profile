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

    {{-- FTM Brand Typography & Member Portal Theme --}}
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
    {{-- Font Awesome icons used across member pages (ensure icons render on pages that extend this layout) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-cream">

    {{-- Navbar --}}
    @yield('navbar')

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
