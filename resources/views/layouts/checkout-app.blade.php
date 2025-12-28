<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Checkout' }}</title>

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* PAKSA SNAP SELALU DI DEPAN */
        iframe[src*="snap"] {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 999999999 !important;
            pointer-events: auto !important;
        }

        /* Nonaktifkan semua overlay lain */
        .modal, .backdrop, .fixed, .absolute {
            z-index: auto !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Snap Midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    @stack('scripts')
</body>
</html>
