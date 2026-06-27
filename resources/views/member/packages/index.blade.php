<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Packages - FTM Society</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ===============================================================
           FTM SOCIETY — FINAL MEMBERSHIP CARD PICKER
           =============================================================== */
        #availablePackagesModal { z-index: 9999 !important; }
        #availablePackagesModal .apm-backdrop {
            display: flex; align-items: center; justify-content: center;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0);
            transition: opacity 0.3s ease; z-index: 9999;
            padding: 1.5rem; opacity: 0; pointer-events: none;
        }
        #availablePackagesModal.open-modal .apm-backdrop {
            opacity: 1; pointer-events: auto;
            background: rgba(28,28,28,0.6);
        }
        #availablePackagesModal .apm-container {
            width: 100%; max-width: 1100px; max-height: 90vh;
            border-radius: 24px;
            background-image: linear-gradient(145deg, #1C1C1C, #7A2B4A);
            filter: drop-shadow(0 30px 60px rgba(0,0,0,0.5));
            overflow: hidden; display: flex; flex-direction: column;
            margin: auto;
            transform: translateY(30px) scale(0.97);
            transition: transform 0.35s ease, opacity 0.35s ease;
            opacity: 0; position: relative; contain: layout style;
        }
        #availablePackagesModal.open-modal .apm-container {
            transform: translateY(0) scale(1); opacity: 1;
        }
        @media (max-width: 768px) {
            #availablePackagesModal .apm-container {
                max-width: 100%; max-height: 95vh;
                border-radius: 20px 20px 0 0;
                margin-bottom: 0; margin-top: auto;
            }
            #availablePackagesModal .apm-backdrop { padding: 0; align-items: flex-end; }
        }
        #availablePackagesModal .apm-header {
            background-image: linear-gradient(135deg, #EE4E8B, #7A2B4A);
            padding: 1.5rem 2rem; flex-shrink: 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        #availablePackagesModal .apm-header-left { display: flex; align-items: center; gap: 1rem; }
        #availablePackagesModal .apm-header-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
            color: #FFF; font-size: 1.1rem; flex-shrink: 0;
        }
        #availablePackagesModal .apm-header-title {
            color: #FFF; font-family: 'Inter','Poppins',sans-serif;
            font-weight: 800; font-size: 1.25rem; letter-spacing: -0.02em;
        }
        #availablePackagesModal .apm-header-sub {
            color: rgba(255,255,255,0.65); font-family: 'Inter',sans-serif;
            font-size: 0.75rem; margin-top: 2px;
        }
        #availablePackagesModal .apm-close-btn {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.15); border: none; color: #FFF;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.25s ease; font-size: 0.8rem;
        }
        #availablePackagesModal .apm-close-btn:hover { background: rgba(255,255,255,0.28); }
        #availablePackagesModal .apm-body {
            flex: 1; overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain; contain: layout style;
            background: #FCF9F2; padding: 24px;
        }
        #availablePackagesModal .apm-body::-webkit-scrollbar { width: 5px; }
        #availablePackagesModal .apm-body::-webkit-scrollbar-track { background: #FCF9F2; }
        #availablePackagesModal .apm-body::-webkit-scrollbar-thumb { background: #F4C9DF; border-radius: 10px; }
        #availablePackagesModal .apm-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 24px; background: #FCF9F2;
        }
        @media (max-width: 1024px) { #availablePackagesModal .apm-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) {
            #availablePackagesModal .apm-grid { grid-template-columns: 1fr; gap: 16px; }
            #availablePackagesModal .apm-body { padding: 16px; }
            #availablePackagesModal .apm-header { padding: 1rem 1.25rem; }
            #availablePackagesModal .apm-header-icon { display: none; }
        }
        #availablePackagesModal .apm-card {
            background: #FCF9F2;
            border: 1.5px solid rgba(238,78,139,0.15);
            border-radius: 16px;
            filter: drop-shadow(0 4px 12px rgba(122,43,74,0.08));
            padding: 24px; display: flex; flex-direction: column;
            height: 100%; transition: transform 0.3s ease;
            position: relative; overflow: visible;
            will-change: transform; transform: translateZ(0);
            backface-visibility: hidden;
        }
        #availablePackagesModal .apm-card:hover {
            transform: translateY(-4px) translateZ(0);
        }
        #availablePackagesModal .apm-card.is-exclusive { border: 2px solid #EE4E8B; }
        #availablePackagesModal .apm-card.is-exclusive::after {
            content: ''; position: absolute; inset: -2px; border-radius: 17px;
            background-image: linear-gradient(135deg, rgba(238,78,139,0.08) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
        #availablePackagesModal .apm-card.is-exclusive > * { position: relative; z-index: 1; }
        #availablePackagesModal .apm-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 14px; border-radius: 999px;
            font-family: 'Inter',sans-serif; font-size: 11px;
            font-weight: 700; letter-spacing: 0.08em;
            text-transform: uppercase; border: none; outline: none;
            min-height: 32px;
        }
        #availablePackagesModal .apm-badge-exclusive {
            background-image: linear-gradient(135deg, #EE4E8B, #7A2B4A); color: #FFF;
        }
        #availablePackagesModal .apm-badge-regular { background: #F4C9DF; color: #7A2B4A; }
        #availablePackagesModal .apm-card-name {
            font-family: 'Inter','Poppins',sans-serif;
            font-size: 20px; font-weight: 700; color: #1C1C1C;
            line-height: 1.3; margin: 8px 0;
            min-height: 64px; display: flex; align-items: flex-start;
            border: none !important; outline: none !important;
            background: transparent !important; box-shadow: none !important; padding: 0 !important;
        }
        .apm-card-price,
        #availablePackagesModal .apm-card-price,
        .apm-card [class*="price"],
        .apm-card [class*="harga"],
        .apm-card [class*="Price"],
        .apm-card [class*="Harga"] {
            display: flex; align-items: center; gap: 2px;
            padding: 0 !important; min-height: 56px;
            border: none !important; outline: none !important;
            background: transparent !important; box-shadow: none !important;
            border-radius: 0 !important;
        }
        #availablePackagesModal .apm-card-price-currency {
            font-family: 'Inter',sans-serif; font-size: 13px;
            font-weight: 600; color: #EE4E8B; vertical-align: super; line-height: 1;
            border: none; outline: none; background: transparent; box-shadow: none;
        }
        #availablePackagesModal .apm-card-price-amount {
            font-family: 'Inter',sans-serif; font-weight: 800;
            font-size: 38px; letter-spacing: -0.035em; line-height: 1; color: #EE4E8B;
            border: none; outline: none; background: transparent; box-shadow: none;
        }
        #availablePackagesModal .apm-stats {
            display: flex; gap: 10px; margin: 12px 0;
            min-height: 68px; align-items: center;
        }
        #availablePackagesModal .apm-stat-item {
            flex: 1; background: #F4C9DF; border-radius: 10px;
            padding: 8px 16px; display: flex;
            flex-direction: column; align-items: center;
        }
        #availablePackagesModal .apm-stat-label {
            font-family: 'Inter',sans-serif; font-size: 10px;
            font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; color: #7A2B4A; display: block;
        }
        #availablePackagesModal .apm-stat-value {
            font-family: 'Inter',sans-serif; font-size: 14px;
            font-weight: 700; color: #1C1C1C; display: block;
        }
        #availablePackagesModal .apm-desc {
            border-left: 3px solid #EE4E8B;
            background-image: linear-gradient(to right, rgba(244,201,223,0.35), transparent);
            padding: 10px 14px; border-radius: 0 8px 8px 0;
            margin: 12px 0; min-height: 72px;
            display: flex; align-items: flex-start;
        }
        #availablePackagesModal .apm-desc-text {
            font-family: 'Inter',sans-serif; font-size: 14px;
            font-weight: 400; font-style: italic; color: #7A2B4A; line-height: 1.5;
        }
        #availablePackagesModal .apm-features { flex: 1; margin-bottom: 14px; }
        #availablePackagesModal .apm-feature-item { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        #availablePackagesModal .apm-feature-icon {
            width: 18px; height: 18px; background: #1A7A5E;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0; color: #FFF; font-size: 11px;
        }
        #availablePackagesModal .apm-feature-icon i { color: #FFF; font-size: 11px; }
        #availablePackagesModal .apm-feature-text {
            font-family: 'Inter',sans-serif; font-size: 14px;
            font-weight: 400; color: #1C1C1C;
        }
        #availablePackagesModal .apm-cta {
            display: flex; align-items: center; justify-content: center;
            gap: 0.5rem; width: 100%; padding: 14px 24px;
            border-radius: 12px; border: none; cursor: pointer;
            font-family: 'Inter',sans-serif; font-size: 15px;
            font-weight: 700; letter-spacing: 0.05em; text-decoration: none;
            background-image: linear-gradient(135deg, #EE4E8B, #7A2B4A);
            color: #FFF; margin-top: auto;
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        #availablePackagesModal .apm-cta:hover {
            transform: scale(1.02) translateZ(0); filter: brightness(1.08);
        }
        #availablePackagesModal .apm-cta:active { transform: scale(0.98); }
        #availablePackagesModal .apm-cta i { transition: transform 0.3s ease; }
        #availablePackagesModal .apm-cta:hover i.fa-arrow-right { transform: translateX(4px); }
        @media (prefers-reduced-motion: reduce) {
            #availablePackagesModal *,
            #availablePackagesModal *::before,
            #availablePackagesModal *::after {
                transition: none !important; will-change: auto !important;
            }
        }

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
            background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .btn-ftm-pink {
            background: #EE4E8B !important; color: #FFFFFF !important;
            border: none !important; border-radius: 12px !important;
            font-family: 'Poppins', sans-serif !important; font-weight: 600 !important;
            box-shadow: 0 4px 14px rgba(238, 78, 139, 0.3) !important;
            transition: all 0.3s ease !important; cursor: pointer;
        }
        .btn-ftm-pink:hover {
            background: #7A2B4A !important; transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(122, 43, 74, 0.4) !important;
        }

        /* ===== MODAL STYLES ===== */
        .modal-backdrop {
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(0px);
            background: rgba(0, 0, 0, 0);
        }
        .modal-backdrop.active {
            opacity: 1;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.75) !important;
        }
        
        /* Hide page content when modal is open */
        body.modal-open main > *:not(#packageModal) {
            filter: blur(4px);
            pointer-events: none;
        }
        .modal-content {
            transform: translateY(40px) scale(0.95);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .modal-backdrop.active .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        .modal-tab {
            position: relative;
            color: #6b7280;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
        }
        .modal-tab:hover { color: #7A2B4A; }
        .modal-tab.active {
            color: #7A2B4A;
            border-bottom-color: #EE4E8B;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .modal-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        .spinner {
            width: 40px; height: 40px;
            border: 3px solid #F4C9DF;
            border-top-color: #EE4E8B;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.625rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; font-size: 0.875rem; }
        .info-value { color: #111827; font-size: 0.875rem; font-weight: 600; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.25rem 0.75rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }
        .status-active { background: #dcfce7; color: #166534; }
        .status-expired { background: #fee2e2; color: #991b1b; }

        /* ═══════════════════════════════════════════ RESPONSIVE SIDEBAR ═══════════════════════════════════════════ */
        .sidebar {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 20;
            backdrop-filter: blur(4px);
        }

        .hamburger-btn {
            display: none !important;
            position: fixed !important;
            top: 1rem !important;
            left: 1rem !important;
            z-index: 9999 !important;
            width: 3rem !important;
            height: 3rem !important;
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(122, 43, 74, 0.35) !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            font-size: 1.25rem !important;
        }

        .hamburger-btn:hover {
            background: linear-gradient(135deg, #5A1F3A 0%, #B83863 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(122, 43, 74, 0.45) !important;
        }

        .hamburger-btn:active {
            transform: translateY(0) !important;
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: flex !important;
            }

            .sidebar-overlay.active {
                display: block !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
</head>

<body class="bg-cream h-screen overflow-hidden">

<div class="flex h-screen">

    @include('partials.member-sidebar')

    <!-- Mobile Sidebar Overlay removed to avoid dark backdrop -->

{{-- ================= MAIN ================= --}}
<main class="flex-1 p-4 md:p-8 overflow-y-auto">

    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

{{-- ================= HEADER ================= --}}
<div class="bg-white rounded-2xl shadow-sm border border-light-pink/30 p-4 md:p-8 mb-6 md:mb-10 mt-12 md:mt-0">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-primary-dark text-lg"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-dark tracking-tight">My Packages</h1>
            </div>
            <p class="text-cream0 ml-[52px]">Kelola dan pantau paket membership Anda</p>
        </div>
        
        {{-- ✅ TOMBOL BUKA MODAL AVAILABLE PACKAGES --}}
        <button type="button" onclick="openAvailablePackagesModal()"
           class="btn-ftm-pink text-white px-6 py-3.5 rounded-xl font-bold inline-flex items-center text-sm md:text-base">
            <i class="fas fa-plus-circle mr-2"></i>
            Beli Paket Baru
        </button>
    </div>
</div>

{{-- ================= ACTIVE PACKAGES ================= --}}
@if($activePackages?->count())

<div class="mb-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 bg-grounded-green/40 rounded-lg flex items-center justify-center">
            <i class="fas fa-check-circle text-accent"></i>
        </div>
        <h2 class="text-xl font-bold text-dark">Paket Aktif</h2>
        <span class="bg-grounded-green/40 text-springs-ivy text-xs font-bold px-3 py-1 rounded-full">
            {{ $activePackages->count() }}
        </span>
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
                    'green' => 'bg-grounded-green/40 text-springs-ivy border-accent/30',
                    'yellow' => 'bg-grounded-green/40 text-springs-ivy border-grounded-green/40',
                    'red' => 'bg-light-pink/50 text-secondary border-secondary/30'
                ];
            @endphp

            <div class="bg-white rounded-xl shadow card-hover {{ $remainingDays <= 7 && $remainingDays > 0 ? 'gradient-border' : '' }}">
                {{-- Header --}}
                <div class="p-6 border-b border-light-pink/30">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-xl font-bold text-dark flex-1">
                            {{ $pkg->name ?? 'Package Tidak Ditemukan' }}
                        </h3>
                        
                        {{-- Status Badge --}}
                        @if($isExpired)
                            <span class="bg-light-pink/50 text-secondary text-xs font-medium px-2.5 py-1 rounded-full">
                                <i class="fas fa-times-circle"></i> Expired
                            </span>
                        @else
                            <span class="bg-grounded-green/40 text-springs-ivy text-xs font-medium px-2.5 py-1 rounded-full {{ $remainingDays <= 3 && $remainingDays > 0 ? 'badge-pulse' : '' }}">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-sm text-cream0">
                        <i class="fas fa-barcode mr-1"></i>
                        {{ $order->order_code }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    {{-- Expiry Information --}}
                    <div class="mb-4 p-4 rounded-lg {{ $statusColorClasses[$statusColor] ?? 'bg-cream text-dark' }} border">
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
                            @elseif($pkg && $pkg->duration_days)
                                <span class="text-primary">
                                    <i class="fas fa-clock mr-1"></i>Belum dimulai
                                </span>
                                <span class="block text-xs font-normal mt-1 text-cream0">
                                    @if($pkg->is_exclusive)
                                        Masa aktif {{ $pkg->duration_days }} hari dimulai dari jadwal pertama
                                    @else
                                        Masa aktif {{ $pkg->duration_days }} hari dimulai saat booking pertama
                                    @endif
                                </span>
                            @else
                                <span class="text-accent">
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
                                    <div class="h-full {{ $statusColor === 'green' ? 'bg-accent' : ($statusColor === 'yellow' ? 'bg-springs-ivy' : 'bg-secondary') }} transition-all duration-500" 
                                         style="width: {{ min($progressPercentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Warning for expiring soon --}}
                    @if(!$isExpired && $remainingDays <= 7 && $remainingDays > 0)
                        <div class="mb-4 p-3 bg-light-pink/30 border border-light-pink rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-secondary mt-0.5 mr-2"></i>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-secondary">
                                        Paket akan segera berakhir!
                                    </p>
                                    <p class="text-xs text-secondary mt-1">
                                        Perpanjang sekarang untuk tetap menikmati akses
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Usage Progress --}}
                    @php
                        $totalQuota = $pkg->quota ?? 0;
                        $classesLeft = $order->remaining_classes ?? $order->remaining_sessions ?? $totalQuota;
                        $used = max(0, $totalQuota - $classesLeft);
                        $usagePercent = $totalQuota > 0 ? ($used / $totalQuota) * 100 : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-cream0 uppercase tracking-wider">Pemakaian Kelas</span>
                            <span class="text-xs font-bold {{ $classesLeft <= 0 ? 'text-secondary' : ($classesLeft <= 2 ? 'text-springs-ivy' : 'text-primary-dark') }}">
                                {{ $used }}/{{ $totalQuota }} terpakai
                            </span>
                        </div>
                        <div class="h-2.5 bg-cream rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $classesLeft <= 0 ? 'bg-light-pink/300' : ($classesLeft <= 2 ? 'bg-grounded-green/200' : 'bg-primary') }}"
                                 style="width: {{ min($usagePercent, 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[11px] text-dark/40">{{ $classesLeft }} kelas tersedia</span>
                            @if($classesLeft <= 0)
                                <span class="text-[11px] text-secondary font-medium"><i class="fas fa-exclamation-circle mr-0.5"></i>Habis</span>
                            @elseif($classesLeft <= 2)
                                <span class="text-[11px] text-springs-ivy font-medium"><i class="fas fa-exclamation-triangle mr-0.5"></i>Hampir habis</span>
                            @endif
                        </div>
                    </div>

                    
                </div>

                {{-- Footer --}}
                <div class="px-6 pb-6 pt-2 border-t border-dashed border-light-pink/40 bg-cream/20 rounded-b-xl">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-cream0">Aksi Paket</span>
                        <span class="text-[11px] text-cream0">Klik tombol untuk lihat info paket user</span>
                    </div>
                    <button type="button" onclick="openPackageModal({{ $order->id }})"
                       class="block w-full text-center text-white py-4 rounded-xl font-bold transition-all cursor-pointer shadow-lg hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-primary/20"
                       style="background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); border: 0; min-height: 56px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 12px 24px rgba(122, 43, 74, 0.22);">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Info Paket</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endif

{{-- ================= EMPTY STATE ================= --}}
@if(!$activePackages?->count())

<div class="bg-white p-12 rounded-xl shadow text-center max-w-md mx-auto">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(241,204,227,0.40);">
        <i class="fas fa-box-open text-3xl" style="color: #7A2B4A;"></i>
    </div>
    
    <h3 class="text-2xl font-bold mb-3 text-dark">Belum Ada Paket Aktif</h3>
    
    <p class="text-cream0 mb-6">
        Mulai perjalanan fitness Anda dengan membeli paket pertama
    </p>
    
    <button type="button" onclick="openAvailablePackagesModal()"
       class="btn-ftm-pink text-white px-8 py-3.5 rounded-xl font-bold inline-flex items-center">
        <i class="fas fa-plus-circle mr-2"></i>
        Lihat Paket Tersedia
    </button>
</div>

@endif

{{-- ================= PAST/EXPIRED PACKAGES ================= --}}
@if($pastPackages?->count())

<div class="mt-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 bg-cream rounded-lg flex items-center justify-center">
            <i class="fas fa-history text-cream0"></i>
        </div>
        <h2 class="text-xl font-bold text-dark">Riwayat Paket</h2>
        <span class="bg-cream text-dark text-xs font-bold px-3 py-1 rounded-full">
            {{ $pastPackages->count() }}
        </span>
    </div>

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary-dark to-primary text-white">
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
            <tbody class="divide-y divide-light-pink/40">
                @foreach($pastPackages as $order)
                    @php 
                        $pkg = $order->package;
                        $expiredAt = $order->expired_at ? \Carbon\Carbon::parse($order->expired_at) : null;
                    @endphp

                    <tr class="hover:bg-cream transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-light-pink/30 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-cream0"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-dark">
                                        {{ $pkg->name ?? 'Package Deleted' }}
                                    </p>
                                    <p class="text-xs text-cream0">
                                        {{ $order->order_code }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <p class="text-sm text-dark">
                                {{ $order->created_at?->format('d M Y') }}
                            </p>
                            <p class="text-xs text-cream0">
                                {{ $order->created_at?->diffForHumans() }}
                            </p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($expiredAt)
                                <p class="text-sm text-dark">
                                    {{ $expiredAt->format('d M Y') }}
                                </p>
                                <p class="text-xs text-cream0">
                                    {{ $expiredAt->diffForHumans() }}
                                </p>
                            @else
                                <span class="text-dark/40 text-sm">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-light-pink/50 text-secondary">
                                <i class="fas fa-times-circle mr-1"></i>
                                Expired
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button onclick="openPackageModal({{ $order->id }})"
                               class="inline-flex items-center text-primary-dark hover:text-primary text-sm font-medium transition-all hover:underline"
                               style="cursor: pointer; position: relative; z-index: 10;">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View (visible on mobile only) -->
    <div class="md:hidden space-y-4">
        @foreach($pastPackages as $order)
            @php 
                $pkg = $order->package;
                $expiredAt = $order->expired_at ? \Carbon\Carbon::parse($order->expired_at) : null;
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-light-pink/30">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-primary-dark to-primary p-4 rounded-t-xl">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-box text-white text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-white text-base leading-tight mb-1">
                                    {{ $pkg->name ?? 'Package Deleted' }}
                                </h3>
                                <p class="text-xs text-white/80 font-mono">
                                    {{ $order->order_code }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/20 text-white ml-2 flex-shrink-0">
                            <i class="fas fa-times-circle mr-1"></i>
                            Expired
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 space-y-3">
                    <!-- Purchase Date -->
                    <div class="flex items-center justify-between py-2 border-b border-light-pink/20">
                        <div class="flex items-center flex-1">
                            <div class="w-8 h-8 bg-light-pink/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-calendar-plus text-primary-dark text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-cream0 font-medium uppercase tracking-wide">Dibeli</p>
                                <p class="text-sm font-bold text-dark">
                                    {{ $order->created_at?->format('d M Y') }}
                                </p>
                                <p class="text-xs text-cream0">
                                    {{ $order->created_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div class="flex items-center justify-between py-2 border-b border-light-pink/20">
                        <div class="flex items-center flex-1">
                            <div class="w-8 h-8 bg-light-pink/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-calendar-times text-secondary text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-cream0 font-medium uppercase tracking-wide">Berakhir</p>
                                @if($expiredAt)
                                    <p class="text-sm font-bold text-dark">
                                        {{ $expiredAt->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-cream0">
                                        {{ $expiredAt->diffForHumans() }}
                                    </p>
                                @else
                                    <p class="text-sm text-dark/40">-</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Section -->
                    <div class="pt-4 pb-2" style="min-height: 80px !important; display: block !important;">
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-cream0">Aksi Paket</span>
                            <span class="text-[11px] text-cream0">Klik tombol untuk lihat info paket user</span>
                        </div>
                        <button type="button" onclick="openPackageModal({{ $order->id }})"
                               class="w-full text-white py-4 rounded-xl font-bold text-base shadow-lg"
                               style="display: flex !important; align-items: center !important; justify-content: center !important; gap: 0.5rem !important; cursor: pointer !important; background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%) !important; border: none !important; min-height: 56px !important; position: relative !important; z-index: 100 !important; box-shadow: 0 12px 24px rgba(122, 43, 74, 0.22) !important;">
                            <i class="fas fa-eye text-lg"></i>
                            <span>Lihat Info Paket</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endif

</main>
</div>

@if(isset($availablePackages) && $availablePackages->count() > 0)
<div id="availablePackagesModal" class="fixed inset-0 z-[9999] hidden">
    <div class="apm-backdrop" onclick="closeAvailablePackagesModal(event)">
        <div class="apm-container" onclick="event.stopPropagation()">
            <div class="apm-header">
                <div class="apm-header-left">
                    <div class="apm-header-icon"><i class="fas fa-crown"></i></div>
                    <div>
                        <h2 class="apm-header-title">Pilih Paket Membership</h2>
                        <p class="apm-header-sub">Mulai perjalanan sehatmu bersama FTM Society</p>
                    </div>
                </div>
                <button onclick="closeAvailablePackagesModal()" class="apm-close-btn" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="apm-body">
                <div class="apm-grid">
                    @foreach($availablePackages as $package)
                    <div class="apm-card {{ $package->is_exclusive ? 'is-exclusive' : '' }}">
                        @if($package->is_exclusive)
                        <span class="apm-badge apm-badge-exclusive"><i class="fas fa-crown" style="font-size:0.5rem"></i> Eksklusif</span>
                        @else
                        <span class="apm-badge apm-badge-regular"><i class="fas fa-heart" style="font-size:0.5rem"></i> Reguler</span>
                        @endif
                        <h3 class="apm-card-name">{{ $package->name }}</h3>
                        <div class="apm-card-price">
                            <span class="apm-card-price-currency">Rp</span>
                            <span class="apm-card-price-amount">{{ number_format($package->price, 0, ',', '.') }}</span>
                        </div>
                        @if($package->duration_days || $package->quota)
                        <div class="apm-stats">
                            @if($package->duration_days)
                            <div class="apm-stat-item">
                                <span class="apm-stat-label">Durasi</span>
                                <span class="apm-stat-value">{{ $package->duration_days }} hari</span>
                            </div>
                            @endif
                            @if($package->quota)
                            <div class="apm-stat-item">
                                <span class="apm-stat-label">Sesi</span>
                                <span class="apm-stat-value">{{ $package->quota }} kelas</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($package->description)
                        <div class="apm-desc">
                            <span class="apm-desc-text">{{ $package->description }}</span>
                        </div>
                        @endif
                        <div class="apm-features">
                            @if($package->quota)
                            <div class="apm-feature-item">
                                <div class="apm-feature-icon"><i class="fas fa-check"></i></div>
                                <span class="apm-feature-text">{{ $package->quota }} sessions tersedia</span>
                            </div>
                            @endif
                            @if($package->duration_days)
                            <div class="apm-feature-item">
                                <div class="apm-feature-icon"><i class="fas fa-check"></i></div>
                                <span class="apm-feature-text">Valid {{ $package->duration_days }} hari</span>
                            </div>
                            @endif
                            <div class="apm-feature-item">
                                <div class="apm-feature-icon"><i class="fas fa-check"></i></div>
                                <span class="apm-feature-text">Akses ke semua fasilitas</span>
                            </div>
                        </div>
                        <a href="{{ route('join.package', ['package' => $package->slug ?? $package->id]) }}" class="apm-cta">
                            <span>Beli Sekarang</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ========================================
     PACKAGE DETAIL MODAL - PROFESSIONAL DESIGN
======================================== -->
<div id="packageModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/60 flex items-end md:items-center justify-center" onclick="closePackageModal(event)">
        <div class="modal-content bg-white w-full md:max-w-2xl md:rounded-2xl rounded-t-3xl shadow-2xl max-h-[95vh] md:max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <!-- Modal Header - Gradient -->
            <div class="bg-gradient-to-r from-primary-dark to-primary p-4 md:p-6 shrink-0 rounded-t-3xl md:rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <button onclick="closePackageModal()" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                        <i class="fas fa-arrow-left text-white"></i>
                    </button>
                    <h2 class="text-white font-bold text-lg">Detail Paket</h2>
                    <button class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                        <i class="fas fa-share-alt text-white"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body - Scrollable -->
            <div class="flex-1 overflow-y-auto bg-cream">
                <!-- Loading State -->
                <div id="modalLoading" class="flex items-center justify-center min-h-[400px]">
                    <div class="text-center">
                        <div class="spinner mx-auto mb-3"></div>
                        <p class="text-sm text-cream0">Memuat detail paket...</p>
                    </div>
                </div>

                <!-- Content Container -->
                <div id="modalContent" class="hidden p-4 md:p-6 space-y-4">
                    
                    <!-- Package Info Card -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h3 id="modalPackageName" class="text-xl font-bold text-primary-dark mb-2">Loading...</h3>
                        <p id="modalOrderCode" class="text-sm text-cream0 mb-4"></p>
                        
                        <!-- Info Boxes Grid -->
                        <div class="grid grid-cols-3 gap-3" id="infoBoxes">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div id="warningMessage" class="hidden bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-amber-900" id="warningTitle"></p>
                                <p class="text-xs text-amber-700 mt-1" id="warningText"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-dark">Kelas digunakan</h4>
                            <span id="usageText" class="text-sm font-bold text-primary-dark"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-cream0">Kelas tersedia</span>
                            <span id="availableBadge" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                        </div>
                    </div>

                    <!-- Detail Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- HARGA -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">HARGA</p>
                            <p id="priceValue" class="text-2xl font-bold mb-1"></p>
                            <p class="text-xs text-white/70">Sudah termasuk pajak</p>
                        </div>

                        <!-- METODE BAYAR -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">METODE BAYAR</p>
                            <p id="paymentMethod" class="text-lg font-bold mb-1"></p>
                            <p id="paymentDate" class="text-xs text-white/70"></p>
                        </div>

                        <!-- SISA KELAS -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">SISA KELAS</p>
                            <p id="remainingClasses" class="text-2xl font-bold mb-1"></p>
                            <p id="classType" class="text-xs text-white/70"></p>
                        </div>

                        <!-- STATUS -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">STATUS</p>
                            <p id="statusValue" class="text-lg font-bold mb-2"></p>
                            <span id="statusBadge" class="inline-block px-3 py-1 rounded-full text-xs font-bold"></span>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h4 class="font-bold text-dark mb-4 flex items-center">
                            <i class="fas fa-clock mr-2 text-primary-dark"></i>
                            RIWAYAT WAKTU
                        </h4>
                        
                        <div class="space-y-4">
                            <!-- Purchase Date -->
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-grounded-green/40 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-calendar-plus text-accent text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">TANGGAL PEMBELIAN</p>
                                    <p id="purchaseDate" class="text-sm font-bold text-dark mb-1"></p>
                                    <span id="purchaseBadge" class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-grounded-green/40 text-springs-ivy">Paket berhasil</span>
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-light-pink/50 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-calendar-times text-secondary text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">TANGGAL BERAKHIR</p>
                                    <p id="expiryDate" class="text-sm font-bold text-dark mb-1"></p>
                                    <span id="expiryBadge" class="inline-block px-2.5 py-1 rounded-full text-xs font-bold"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h4 class="font-bold text-dark mb-3">Yang kamu dapatkan</h4>
                        <div id="benefitsList" class="space-y-3">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
// ===== AVAILABLE PACKAGES MODAL =====
function openAvailablePackagesModal() {
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');
    requestAnimationFrame(() => { modal.classList.add('open-modal'); });
}
function closeAvailablePackagesModal(event) {
    if (event && event.target !== event.currentTarget) return;
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    modal.classList.remove('open-modal');
    document.body.classList.remove('modal-open');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 350);
}

// ===== MODAL LOGIC =====
let currentOrderId = null;

function openPackageModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('packageModal');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    // Show modal and disable page interaction
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');
    
    // Show loading, hide content
    document.getElementById('modalLoading').style.display = 'flex';
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    
    // Animate in
    requestAnimationFrame(() => {
        backdrop.classList.add('active');
    });
    
    // Fetch data
    fetchPackageDetail(orderId);
}

function closePackageModal(event) {
    if (event && event.target !== event.currentTarget) return;
    
    const modal = document.getElementById('packageModal');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    backdrop.classList.remove('active');
    document.body.classList.remove('modal-open');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closePackageModal();
        closeAvailablePackagesModal();
    }
});

function switchTab(tabName) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.remove('active');
        p.style.display = 'none';
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    const activePanel = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
    activePanel.classList.add('active');
    activePanel.style.display = 'block';
}

async function fetchPackageDetail(orderId) {
    try {
        const response = await fetch(`/member/packages/${orderId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Failed to load');
        
        const data = await response.json();
        
        if (!data.success) throw new Error(data.message || 'Error');
        
        populateModal(data);
        
    } catch (error) {
        console.error('Error fetching package detail:', error);
        document.getElementById('modalLoading').innerHTML = `
            <div class="text-center">
                <div class="w-16 h-16 bg-light-pink/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-secondary text-2xl"></i>
                </div>
                <p class="text-sm text-secondary font-medium">Gagal memuat detail paket</p>
                button onclick="fetchPackageDetail(${orderId})" class="mt-3 text-sm font-medium" style="color: #7A2B4A;">
                    <i class="fas fa-redo mr-1"></i>Coba Lagi
                </button>
            </div>
        `;
    }
}

function populateModal(data) {
    const { order, package: pkg, usage, booked_schedules, attendance_history, stats } = data;
    
    // Package Name & Order Code
    document.getElementById('modalPackageName').textContent = pkg.name;
    document.getElementById('modalOrderCode').innerHTML = `<i class="fas fa-barcode mr-1"></i>${order.order_code}`;
    
    // Info Boxes (DIBELI, BERAKHIR, DURASI)
    const infoBoxes = document.getElementById('infoBoxes');
    infoBoxes.innerHTML = `
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">DIBELI</p>
            <p class="text-sm font-bold text-dark">${order.created_at_short || order.created_at}</p>
        </div>
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">BERAKHIR</p>
            <p class="text-sm font-bold text-dark">${order.expired_at_short || order.expired_at_full || '-'}</p>
        </div>
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">DURASI</p>
            <p class="text-sm font-bold text-dark">${pkg.duration_days ? pkg.duration_days + ' hari' : 'Unlimited'}</p>
        </div>
    `;
    
    // Warning Message (if applicable)
    const warningMsg = document.getElementById('warningMessage');
    if (!order.is_expired && order.remaining_days <= 7 && order.remaining_days > 0) {
        warningMsg.classList.remove('hidden');
        document.getElementById('warningTitle').textContent = 'Belum dimulai';
        document.getElementById('warningText').textContent = 'Masa aktif dihitung sejak booking pertama dilakukan.';
    } else if (order.is_expired) {
        warningMsg.classList.remove('hidden');
        warningMsg.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-lg';
        document.getElementById('warningTitle').textContent = 'Paket Expired';
        document.getElementById('warningText').textContent = 'Paket ini sudah tidak aktif. Perpanjang untuk melanjutkan.';
    } else {
        warningMsg.classList.add('hidden');
    }
    
    // Usage Section
    document.getElementById('usageText').textContent = `${usage.used}/${usage.total_quota} kelas`;
    const availableBadge = document.getElementById('availableBadge');
    if (usage.remaining <= 0) {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
        availableBadge.textContent = 'Habis';
    } else if (usage.remaining <= 2) {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700';
        availableBadge.textContent = 'Hampir habis';
    } else {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
        availableBadge.textContent = 'Tersedia';
    }
    
    // Detail Cards
    document.getElementById('priceValue').textContent = pkg.price_formatted || order.amount_formatted;
    document.getElementById('paymentMethod').textContent = order.payment_method || 'Menunggu Pembayaran';
    document.getElementById('paymentDate').textContent = order.created_at_short || order.created_at;
    document.getElementById('remainingClasses').textContent = usage.remaining;
    document.getElementById('classType').textContent = pkg.is_exclusive ? 'Eksklusif' : 'Reguler';
    
    // Status
    document.getElementById('statusValue').textContent = order.is_expired ? 'Expired' : 'Aktif';
    const statusBadge = document.getElementById('statusBadge');
    if (order.is_expired) {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white';
        statusBadge.textContent = 'Tidak aktif';
    } else if (!order.expired_at) {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-500 text-white';
        statusBadge.textContent = 'Belum dimulai';
    } else {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white';
        statusBadge.textContent = 'Aktif';
    }
    
    // Timeline
    document.getElementById('purchaseDate').textContent = order.created_at;
    document.getElementById('expiryDate').textContent = order.expired_at_full || 'Belum dimulai';
    const expiryBadge = document.getElementById('expiryBadge');
    if (order.is_expired) {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
        expiryBadge.textContent = 'Paket berakhir';
    } else if (!order.expired_at) {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700';
        expiryBadge.textContent = 'Belum dimulai';
    } else {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
        expiryBadge.textContent = `${order.remaining_days} hari lagi`;
    }
    
    // Benefits List
    const benefitsList = document.getElementById('benefitsList');
    const benefits = [
        { icon: 'fa-dumbbell', text: `${pkg.quota} kelas ${pkg.is_exclusive ? 'eksklusif' : 'reguler'}` },
        { icon: 'fa-calendar-check', text: `Durasi ${pkg.duration_days ? pkg.duration_days + ' hari' : 'unlimited'}` },
        { icon: 'fa-user-check', text: '1 kelas / 1 personal' },
        { icon: 'fa-certificate', text: 'Sertifikat kehadiran' }
    ];
    
    benefitsList.innerHTML = benefits.map(b => `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-light-pink/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas ${b.icon} text-primary-dark text-sm"></i>
            </div>
            <p class="text-sm text-dark">${b.text}</p>
        </div>
    `).join('');
    
    // Hide loading, show content
    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').classList.remove('hidden');
}

// ===== SIDEBAR TOGGLE FUNCTION =====
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');
        if (!sidebar) return;

        const willOpen = !sidebar.classList.contains('active') && !sidebar.classList.contains('open');
        // toggle both class names to support pages using either 'active' or 'open'
        sidebar.classList.toggle('active');
        sidebar.classList.toggle('open');

        if (willOpen) {
            document.body.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
            if (hamburger) hamburger.style.display = 'none';
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
        } else {
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = '';
            if (hamburger) { hamburger.style.display = ''; hamburger.innerHTML = '<i class="fas fa-bars"></i>'; }
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
        }
}

// Close sidebar when clicking on a nav link
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up sidebar');
    
    const navLinks = document.querySelectorAll('#sidebar nav a');
    console.log('Found nav links:', navLinks.length);
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            }
        });
    });
});

// Reset sidebar on window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('active');
        if (hamburger) hamburger.style.display = '';
        if (hamburger) hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.style.overflow = '';
    }
});

</script>

</body>
</html>