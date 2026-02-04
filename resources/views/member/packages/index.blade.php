<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Packages - FTM Society</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            transition: stroke-dashoffset 0.5s ease;
        }

        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .gradient-border {
            position: relative;
            background: white;
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 0.75rem;
            padding: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

 <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

<div class="flex h-screen">

   {{-- ============================================
     SIDEBAR COMPONENT
     Usage: @include('layouts.member-sidebar')
     ============================================ --}}

@php
    $currentRoute = Route::currentRouteName();
@endphp

<aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
    {{-- Brand Logo --}}
    <div class="px-6 py-5 text-xl font-bold border-b border-white/20">
        FTM SOCIETY
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
        
        {{-- Dashboard --}}
        <a href="{{ route('member.dashboard') }}" 
           class="block px-4 py-2 rounded transition
                  {{ $currentRoute === 'member.dashboard' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-home mr-2"></i>Dashboard
        </a>

        {{-- My Packages --}}
        <a href="{{ route('member.packages.index') }}" 
           class="block px-4 py-2 rounded transition
                  {{ str_starts_with($currentRoute, 'member.packages') ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-box mr-2"></i>My Packages
        </a>

        {{-- Book Class --}}
        <a href="{{ route('member.book') }}"
           class="block px-4 py-2 rounded transition
                  {{ $currentRoute === 'member.book' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-calendar-plus mr-2"></i>Book Class
        </a>

        {{-- My Classes --}}
        <a href="{{ route('member.my-classes') }}"
           class="block px-4 py-2 rounded transition
                  {{ $currentRoute === 'member.my-classes' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-dumbbell mr-2"></i>My Classes
        </a>

        {{-- Transactions --}}
        <a href="{{ route('member.transactions') }}" 
           class="block px-4 py-2 rounded transition
                  {{ $currentRoute === 'member.transactions' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-receipt mr-2"></i>Transactions
        </a>

        {{-- Profile --}}
        <a href="{{ route('member.profile') }}" 
           class="block px-4 py-2 rounded transition
                  {{ $currentRoute === 'member.profile' ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-white/10' }}">
            <i class="fas fa-user mr-2"></i>Profile
        </a>

        {{-- Divider --}}
        <div class="border-t border-white/20 my-4"></div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('member.logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full text-left px-4 py-2 rounded hover:bg-red-600/20 text-red-300 transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>

    </nav>

    {{-- Footer --}}
    <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
        © {{ date('Y') }} FTM Society
    </div>
</aside>

{{-- ================= MAIN ================= --}}
<main class="flex-1 p-8 overflow-y-auto">

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">My Packages</h1>
            <p class="text-gray-600">Kelola dan pantau paket membership Anda</p>
        </div>
        
       {{-- ✅ TOMBOL REDIRECT KE PRICING --}}
<a href="{{ route('member.profile') }}#Packages" 
   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center shadow-lg hover:shadow-xl">
    <i class="fas fa-plus-circle mr-2"></i>
    Beli Paket Lagi
</a>
    </div>

{{-- ================= ACTIVE PACKAGES ================= --}}
@if($activePackages?->count())

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            Paket Aktif
            <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $activePackages->count() }}
            </span>
        </h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($activePackages as $order)
            @php
                $pkg = $order->package;
                
                // ✅ GUNAKAN METHOD DARI MODEL
                $isExpired = method_exists($order, 'isExpired') ? $order->isExpired() : false;
                $remainingDays = method_exists($order, 'getRemainingDays') ? $order->getRemainingDays() : 0;
                $remainingTime = method_exists($order, 'getRemainingTime') ? $order->getRemainingTime() : '-';
                
                // Calculate progress percentage
                $totalDays = $pkg->duration_days ?? 30;
                $progressPercentage = $remainingDays > 0 
                    ? (($totalDays - $remainingDays) / $totalDays) * 100 
                    : 100;
                
                // Determine color based on remaining days
                $statusColor = $remainingDays > 10 
                    ? 'green' 
                    : ($remainingDays > 3 ? 'yellow' : 'red');
                
                $statusColorClasses = [
                    'green' => 'bg-green-100 text-green-800 border-green-200',
                    'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'red' => 'bg-red-100 text-red-800 border-red-200'
                ];
            @endphp

            <div class="bg-white rounded-xl shadow card-hover {{ $remainingDays <= 7 && $remainingDays > 0 ? 'gradient-border' : '' }}">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800 flex-1">
                            {{ $pkg->name ?? 'Package Tidak Ditemukan' }}
                        </h3>
                        
                        {{-- Status Badge --}}
                        @if($isExpired)
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                <i class="fas fa-times-circle"></i> Expired
                            </span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full {{ $remainingDays <= 3 && $remainingDays > 0 ? 'badge-pulse' : '' }}">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-barcode mr-1"></i>
                        {{ $order->order_code }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    {{-- Expiry Information --}}
                    <div class="mb-4 p-4 rounded-lg {{ $statusColorClasses[$statusColor] ?? 'bg-gray-100 text-gray-800' }} border">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium uppercase tracking-wide">
                                <i class="far fa-calendar mr-1"></i>
                                Masa Aktif
                            </span>
                            @if(!$isExpired && $order->expired_at)
                                <span class="text-xs font-bold">
                                    {{ $remainingDays }} hari lagi
                                </span>
                            @endif
                        </div>
                        
                        <p class="font-bold text-sm">
                            @if($order->expired_at)
                                {{ \Carbon\Carbon::parse($order->expired_at)->translatedFormat('d F Y') }}
                            @else
                                <span class="text-green-600">
                                    <i class="fas fa-infinity mr-1"></i>Unlimited
                                </span>
                            @endif
                        </p>
                        
                        <p class="text-xs mt-1 opacity-75">
                            {{ $remainingTime }}
                        </p>

                        {{-- Progress Bar --}}
                        @if($order->expired_at && !$isExpired)
                            <div class="mt-3">
                                <div class="h-2 bg-white/50 rounded-full overflow-hidden">
                                    <div class="h-full {{ $statusColor === 'green' ? 'bg-green-600' : ($statusColor === 'yellow' ? 'bg-yellow-600' : 'bg-red-600') }} transition-all duration-500" 
                                         style="width: {{ min($progressPercentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Warning for expiring soon --}}
                    @if(!$isExpired && $remainingDays <= 7 && $remainingDays > 0)
                        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-orange-500 mt-0.5 mr-2"></i>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-orange-800">
                                        Paket akan segera berakhir!
                                    </p>
                                    <p class="text-xs text-orange-600 mt-1">
                                        Perpanjang sekarang untuk tetap menikmati akses
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Session Info --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Total Sesi</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ $pkg->quota ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center p-3 bg-indigo-50 rounded-lg">
                            <p class="text-xs text-indigo-600 mb-1">Sesi Tersisa</p>
                            <p class="text-xl font-bold text-indigo-600">
                                {{ $order->remaining_sessions ?? ($pkg->quota ?? 0) }}
                            </p>
                        </div>
                    </div>

                    
                </div>

                {{-- Footer --}}
                <div class="px-6 pb-6">
                    <a href="{{ route('member.packages.detail', $order->id) }}"
                       class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endif

{{-- ================= EMPTY STATE ================= --}}
@if(!$activePackages?->count())

<div class="bg-white p-12 rounded-xl shadow text-center max-w-md mx-auto">
    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-box-open text-3xl text-indigo-600"></i>
    </div>
    
    <h3 class="text-2xl font-bold mb-3 text-gray-800">Belum Ada Paket Aktif</h3>
    
    <p class="text-gray-500 mb-6">
        Mulai perjalanan fitness Anda dengan membeli paket pertama
    </p>
    
    <a href="{{ route('schedule') }}#pricing"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
        <i class="fas fa-plus-circle mr-2"></i>
        Lihat Paket Tersedia
    </a>
</div>

@endif

{{-- ================= PAST/EXPIRED PACKAGES ================= --}}
@if($pastPackages?->count())

<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-history text-gray-500 mr-2"></i>
            Riwayat Paket
            <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $pastPackages->count() }}
            </span>
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">
                        Paket
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">
                        Dibeli
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">
                        Berakhir
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($pastPackages as $order)
                    @php 
                        $pkg = $order->package;
                        $expiredAt = $order->expired_at ? \Carbon\Carbon::parse($order->expired_at) : null;
                    @endphp

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $pkg->name ?? 'Package Deleted' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->order_code }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <p class="text-sm text-gray-800">
                                {{ $order->created_at?->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $order->created_at?->diffForHumans() }}
                            </p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($expiredAt)
                                <p class="text-sm text-gray-800">
                                    {{ $expiredAt->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $expiredAt->diffForHumans() }}
                                </p>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Expired
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('member.packages.detail', $order->id) }}"
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif

</main>
</div>

</body>
</html>