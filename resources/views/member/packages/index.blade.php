<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paket Saya - FTM Society</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ===============================================================
   FTM SOCIETY — MEMBERSHIP PACKAGE PICKER (Tailwind-based shell)
   Warna: maroon #4a041f / brand-800 #7A2B4A / rose accents
   =============================================================== */
#availablePackagesModal { z-index: 9999 !important; }
#availablePackagesModal .apm-backdrop {
    display: flex; align-items: center; justify-content: center;
    position: fixed; inset: 0;
    background: rgba(15, 5, 10, 0);
    transition: opacity 0.3s ease, background 0.3s ease;
    z-index: 9999; padding: 1rem; opacity: 0; pointer-events: none;
}
#availablePackagesModal.open-modal .apm-backdrop {
    opacity: 1; pointer-events: auto;
    background: rgba(15, 5, 10, 0.55);
}
#availablePackagesModal .apm-shell {
    width: 100%; max-width: 64rem; /* max-w-5xl */
    height: auto; max-height: min(92vh, 960px);
    display: flex; flex-direction: column;
    background: #faf7f8;
    border: 1px solid #ffe4e6; /* rose-100 */
    border-radius: 1.5rem;
    box-shadow: 0 25px 50px -12px rgba(15, 5, 10, 0.35);
    overflow: hidden; position: relative; margin: auto;
    opacity: 0;
    /* Tanpa scale — scale menyebabkan text buram (GPU raster) */
    transform: translateY(12px);
    transition: transform 0.3s ease, opacity 0.3s ease;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}
#availablePackagesModal.open-modal .apm-shell {
    transform: none; /* none, bukan scale(1) — sharp text */
    opacity: 1;
}
#availablePackagesModal .apm-handle {
    display: none; position: absolute; top: 8px; left: 50%;
    transform: translateX(-50%);
    width: 40px; height: 4px; border-radius: 999px;
    background: rgba(255,255,255,0.45); z-index: 5; pointer-events: none;
}
#availablePackagesModal .apm-scroll {
    flex: 1; min-height: 0; overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    padding: 1rem; padding-bottom: 5.5rem; /* space for sticky footer */
}
#availablePackagesModal .apm-scroll::-webkit-scrollbar { width: 6px; }
#availablePackagesModal .apm-scroll::-webkit-scrollbar-track { background: transparent; }
#availablePackagesModal .apm-scroll::-webkit-scrollbar-thumb {
    background-color: #fbcfe8; border-radius: 9999px;
}
#availablePackagesModal .pb-safe {
    padding-bottom: calc(0.875rem + env(safe-area-inset-bottom, 0px));
}
/* Mobile: full-height sheet */
@media (max-width: 639px) {
    #availablePackagesModal .apm-backdrop { padding: 0; align-items: flex-end; justify-content: stretch; }
    #availablePackagesModal .apm-shell {
        max-width: 100%;
        height: 100dvh; max-height: 100dvh;
        border-radius: 0; border: none;
        margin: 0; margin-top: auto;
    }
    #availablePackagesModal .apm-handle { display: block; }
    #availablePackagesModal .apm-scroll { padding: 1rem; padding-bottom: 5.5rem; }
}
@media (min-width: 640px) {
    #availablePackagesModal .apm-backdrop { padding: 1rem; }
    #availablePackagesModal .apm-scroll { padding: 1.5rem; padding-bottom: 5.5rem; }
}
@media (min-width: 1024px) {
    #availablePackagesModal .apm-scroll { padding: 2rem; padding-bottom: 5.5rem; }
}
/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce) {
    #availablePackagesModal *,
    #availablePackagesModal *::before,
    #availablePackagesModal *::after {
        transition: none !important; animation: none !important;
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
        
        /* Page dimming is handled by each modal backdrop; do NOT blur main content
           (previously caused the whole page to go blurry when a modal opened). */
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
<main class="flex-1 p-6 md:p-10 overflow-y-auto bg-cream">

    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    {{-- ================= HEADER (Greeting) ================= --}}
    <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-light-pink/20 p-5 md:p-7 mb-8 mt-14 md:mt-0">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-baseline gap-1.5 mb-1">
                    <span class="font-nord font-black text-primary text-xl md:text-2xl">Assalamu'alaikum</span>
                    <span class="font-poppins text-dark font-semibold text-base md:text-lg">, {{ auth('customer')->user()->name ?? 'Member' }}</span>
                </div>
                <p class="font-poppins text-dark/45 text-sm leading-relaxed">"Setiap langkah kecil membawamu lebih dekat ke versi terbaik dirimu."</p>
                <p class="font-poppins text-dark/25 text-xs mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>

    {{-- ================= PAGE TITLE ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="font-nord font-bold text-[30px] md:text-[32px] text-dark leading-tight">Paket Saya</h1>
            <p class="font-poppins text-dark/45 text-[15px] mt-1.5">Kelola dan pantau paket membership Anda</p>
        </div>
        <button type="button" onclick="openAvailablePackagesModal()"
           class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl font-poppins font-medium text-[14px] text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
           style="background: linear-gradient(135deg, #EE4E8B, #C2185B, #7A2B4A); box-shadow: 0 4px 16px rgba(238,78,139,0.3);">
            <i class="fas fa-plus-circle text-sm"></i>
            Beli Paket Baru
        </button>
    </div>

    {{-- ================= ACTIVE PACKAGES ================= --}}
    @if($activePackages?->count())
        <div class="grid grid-cols-1 {{ $activePackages->count() >= 2 ? 'md:grid-cols-2' : 'md:grid-cols-1' }} gap-6">
        @foreach($activePackages as $order)
            @php
                $pkg = $order->package;
                $isExpired = method_exists($order, 'isExpired') ? $order->isExpired() : false;
                $remainingDays = method_exists($order, 'getRemainingDays') ? $order->getRemainingDays() : 0;
                $remainingTime = method_exists($order, 'getRemainingTime') ? $order->getRemainingTime() : '-';
                $totalDays = $pkg->duration_days ?? 30;
                $progressPercentage = $remainingDays > 0 ? (($totalDays - $remainingDays) / $totalDays) * 100 : 100;
                $statusColor = $remainingDays > 10 ? 'green' : ($remainingDays > 3 ? 'yellow' : 'red');
            @endphp

            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden relative">
                {{-- Top accent bar --}}
                <div class="h-1 w-full bg-gradient-to-r from-primary to-secondary"></div>

                <div class="p-6 md:p-7">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <div class="flex items-center gap-3 mb-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full font-poppins font-semibold text-[11px] uppercase tracking-wider {{ $isExpired ? 'bg-[rgba(238,78,139,0.1)] text-secondary' : 'bg-[rgba(26,122,94,0.1)] text-accent' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isExpired ? 'bg-secondary' : 'bg-accent' }}"></span>
                                    {{ $isExpired ? 'Kedaluwarsa' : 'Aktif' }}
                                </span>
                                <span class="font-poppins text-dark/30 text-[12px] font-mono tracking-wider">#{{ $order->order_code }}</span>
                            </div>
                            <h3 class="font-nord font-bold text-[22px] md:text-[24px] text-dark leading-tight">{{ $pkg->name ?? 'Package' }}</h3>
                        </div>
                    </div>

                    {{-- Main Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        {{-- Days Left --}}
                        <div class="bg-cream rounded-xl p-5">
                            <p class="font-poppins font-semibold text-[11px] uppercase tracking-widest text-dark/45 mb-2">Sisa Hari</p>
                            <p class="font-nord font-bold text-[36px] md:text-[40px] leading-none" style="color: {{ $statusColor === 'green' ? '#1A7A5E' : ($statusColor === 'yellow' ? '#1D5A4B' : '#7A2B4A') }}">
                                {{ $isExpired ? 0 : max($remainingDays, 0) }}
                            </p>
                            <p class="font-poppins text-dark/35 text-[13px] mt-1.5">{{ $remainingTime }}</p>
                        </div>

                        {{-- Progress --}}
                        <div class="bg-cream rounded-xl p-5">
                            <p class="font-poppins font-semibold text-[11px] uppercase tracking-widest text-dark/45 mb-2">Progress Pemakaian</p>
                            @php
                                $totalQuota = $pkg->quota ?? 0;
                                $classesLeft = $order->remaining_classes ?? $order->remaining_sessions ?? $totalQuota;
                                $used = max(0, $totalQuota - $classesLeft);
                                $usagePercent = $totalQuota > 0 ? ($used / $totalQuota) * 100 : 0;
                            @endphp
                            <p class="font-nord font-bold text-[36px] md:text-[40px] leading-none text-dark">{{ $used }}<span class="font-poppins text-lg text-dark/35 font-normal">/{{ $totalQuota }}</span></p>
                            <div class="mt-3 h-2.5 bg-white/80 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full rounded-full bg-gradient-to-r from-primary to-secondary transition-all duration-700 shadow-[inset_0_1px_2px_rgba(255,255,255,0.3)]"
                                     style="width: {{ min($usagePercent, 100) }}%"></div>
                            </div>
                            <p class="font-poppins text-dark/35 text-[13px] mt-1.5">{{ $classesLeft }} kelas tersisa</p>
                        </div>
                    </div>

                    {{-- Start / Expire Info --}}
                    <div class="flex flex-wrap gap-x-8 gap-y-2 font-poppins text-[14px] text-dark/50 mb-5">
                        <span><span class="font-medium text-dark">Mulai:</span> {{ $order->created_at?->format('d M Y') ?? '-' }}</span>
                        <span><span class="font-medium text-dark">Berakhir:</span> {{ $order->expired_at ? \Carbon\Carbon::parse($order->expired_at)->format('d M Y') : 'Tak Terbatas' }}</span>
                    </div>

                    {{-- Warning for expiring --}}
                    @if(!$isExpired && $remainingDays <= 7 && $remainingDays > 0)
                        <div class="mb-5 p-3.5 bg-[rgba(238,78,139,0.06)] border border-[rgba(238,78,139,0.15)] rounded-xl flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-secondary mt-0.5 text-sm"></i>
                            <div>
                                <p class="font-poppins font-semibold text-[13px] text-secondary">Paket segera berakhir!</p>
                                <p class="font-poppins text-[12px] text-secondary/60 mt-0.5">Perpanjang sekarang untuk terus menikmati akses</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-10 md:p-14 text-center">
            <div class="w-20 h-20 rounded-full bg-[rgba(238,78,139,0.08)] flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-box-open text-3xl text-secondary"></i>
            </div>
            <h3 class="font-nord font-bold text-[22px] text-dark mb-2">Belum Ada Paket Aktif</h3>
            <p class="font-poppins text-dark/45 text-[15px] mb-7">Mulai perjalanan fitness-mu dengan membeli paket pertama</p>
            <button type="button" onclick="openAvailablePackagesModal()"
               class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-xl font-poppins font-medium text-[14px] text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
               style="background: linear-gradient(135deg, #EE4E8B, #C2185B, #7A2B4A); box-shadow: 0 4px 16px rgba(238,78,139,0.3);">
                <i class="fas fa-plus-circle text-sm"></i>
                Lihat Paket
            </button>
        </div>
    @endif

    {{-- ================= RECENT HISTORY ================= --}}
    @if($pastPackages?->count())
    <div class="mt-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-nord font-semibold text-[22px] text-dark">Riwayat</h2>
            <span class="font-poppins bg-[rgba(238,78,139,0.1)] text-secondary text-[12px] font-semibold px-3.5 py-1.5 rounded-full">{{ $pastPackages->count() }} item</span>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-[rgba(238,78,139,0.06)]">
                        <th class="px-6 py-4 text-left font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Nama Paket</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Tipe</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Tanggal</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Status</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[rgba(238,78,139,0.06)]">
                    @foreach($pastPackages->take(5) as $order)
                        @php $pkg = $order->package; @endphp
                        <tr class="hover:bg-[rgba(244,201,223,0.12)] transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[rgba(238,78,139,0.08)] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-box text-secondary text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-poppins font-semibold text-[14px] text-dark">{{ $pkg->name ?? 'Package' }}</p>
                                        <p class="font-poppins text-[12px] text-dark/35 font-mono">{{ $order->order_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-poppins text-[14px] text-dark">{{ $pkg->is_exclusive ? 'Eksklusif' : 'Reguler' }}</td>
                            <td class="px-6 py-4 text-center font-poppins text-[14px] text-dark">{{ $order->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-poppins font-semibold text-[11px] bg-[rgba(238,78,139,0.1)] text-secondary">
                                    <i class="fas fa-times-circle text-[10px]"></i>
                                    Kedaluwarsa
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openPackageModal({{ $order->id }})"
                                   class="font-poppins font-medium text-[13px] text-primary hover:text-secondary transition-colors duration-150 inline-flex items-center gap-1.5">
                                    <i class="fas fa-eye text-[12px]"></i>
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3.5">
            @foreach($pastPackages->take(5) as $order)
                @php $pkg = $order->package; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-[rgba(238,78,139,0.1)] p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-poppins font-semibold text-[14px] text-dark">{{ $pkg->name ?? 'Package' }}</p>
                            <p class="font-poppins text-[12px] text-dark/35 font-mono">{{ $order->order_code }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-poppins font-semibold text-[11px] bg-[rgba(238,78,139,0.1)] text-secondary">
                            Kedaluwarsa
                        </span>
                    </div>
                    <div class="flex items-center justify-between font-poppins text-[12px] text-dark/50 mt-2.5 pt-2.5 border-t border-[rgba(238,78,139,0.06)]">
                        <span>{{ $order->created_at?->format('d M Y') }}</span>
                        <button onclick="openPackageModal({{ $order->id }})" class="text-primary font-medium hover:text-secondary transition-colors">
                            <i class="fas fa-eye mr-1"></i>Lihat Paket
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($pastPackages->count() > 5)
        <div class="text-center mt-5">
            <button class="font-poppins font-medium text-[14px] text-primary hover:text-secondary transition-colors duration-150 inline-flex items-center gap-1.5">
                Lihat Semua Riwayat
                <i class="fas fa-arrow-right text-[12px]"></i>
            </button>
        </div>
        @endif
    </div>
    @endif

</main>
</div>

<div id="availablePackagesModal" class="fixed inset-0 z-[9999] hidden">
    <div class="apm-backdrop" onclick="closeAvailablePackagesModal(event)">
        <div class="apm-shell" onclick="event.stopPropagation()" role="dialog" aria-modal="true" aria-label="Pilih Paket Membership">
            <span class="apm-handle" aria-hidden="true"></span>

            <!-- ===== HEADER ===== -->
            <header class="relative flex-shrink-0 bg-gradient-to-r from-[#9d174d] via-[#831843] to-[#4a041f] text-white px-5 py-4 sm:px-8 sm:py-6 shadow-md overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
                <div class="absolute right-32 -bottom-8 w-36 h-36 rounded-full bg-rose-400/10 blur-lg pointer-events-none"></div>
                <div class="relative flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white/15 flex items-center justify-center border border-white/20">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rose-100" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2.5 19h19a1 1 0 0 0 1-1V9a1 1 0 0 0-1.6-.8l-4.4 3.3-3.9-6.5a1 1 0 0 0-1.7 0l-3.9 6.5-4.4-3.3A1 1 0 0 0 2.5 9v9a1 1 0 0 0 1 1zM4.5 17v-5.2l3 2.25a1 1 0 0 0 1.4-.25L12 8.3l3.1 5.2a1 1 0 0 0 1.4.25l3-2.25V17h-15zM3 21h18a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base sm:text-2xl font-bold tracking-tight text-white leading-tight">Pilih Paket Membership</h2>
                            <p class="text-xs sm:text-sm text-rose-100/90 mt-0.5 font-normal leading-snug">Pilih paket sesuai kebutuhanmu.</p>
                        </div>
                    </div>
                    <button onclick="closeAvailablePackagesModal()" type="button" title="Tutup" aria-label="Tutup"
                        class="apm-close-btn flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center bg-white/10 hover:bg-white/20 active:scale-95 transition-all text-white border border-white/15 focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- ===== SCROLLABLE BODY ===== -->
            <main class="apm-scroll" id="apmBody">
                @if(isset($availablePackages) && $availablePackages->count() > 0)
                @php
                    $sortedPackages = $availablePackages->sortByDesc('is_exclusive')->values();
                    $featuredPackage = $sortedPackages->first(fn ($p) => !empty($p->is_exclusive));
                    $regularPackages = $sortedPackages->filter(fn ($p) => empty($p->is_exclusive))->values();
                @endphp

                @if($featuredPackage)
                @php
                    $fp = $featuredPackage;
                    $fpPrice = (int) ($fp->price ?? 0);
                @endphp
                <!-- EXCLUSIVE PACKAGE -->
                <section class="relative bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 border-2 border-rose-300/80 shadow-lg shadow-rose-900/5 transition-all duration-200" aria-label="Paket unggulan">
                    <div class="flex flex-wrap items-center gap-2 mb-3 sm:mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#831843] text-white shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-amber-300" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Paling Populer
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-rose-50 text-[#9d174d] border border-rose-200">
                            Eksklusif
                        </span>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Left -->
                        <div class="flex-1 space-y-3.5">
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#4a041f] tracking-tight leading-tight">{{ $fp->name }}</h3>
                            @if($fp->description)
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl">{{ $fp->description }}</p>
                            @endif
                            @if($fp->duration_days || $fp->quota)
                            <div class="flex flex-wrap items-center gap-2.5 pt-1">
                                @if($fp->duration_days)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-50/80 border border-rose-100 text-xs sm:text-sm font-medium text-[#831843]">
                                    <span aria-hidden="true">📅</span> {{ $fp->duration_days }} Hari
                                </span>
                                @endif
                                @if($fp->quota)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-50/80 border border-rose-100 text-xs sm:text-sm font-medium text-[#831843]">
                                    <span aria-hidden="true">🏋️</span> {{ $fp->quota }} Sesi
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Right: price + CTA -->
                        <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between lg:justify-center gap-4 pt-4 lg:pt-0 border-t lg:border-t-0 border-rose-100 lg:min-w-[270px]">
                            <div class="w-full sm:w-auto lg:w-full bg-[#fdf8fa] p-4 sm:p-5 rounded-2xl border border-rose-100 text-left lg:text-right">
                                <span class="text-xs font-semibold tracking-wider uppercase text-slate-500 block mb-0.5">Mulai Dari</span>
                                @if($fpPrice > 0)
                                <div class="flex items-baseline gap-1 lg:justify-end">
                                    <span class="text-sm font-semibold text-[#831843]">Rp</span>
                                    <span class="text-3xl sm:text-4xl font-extrabold text-[#9d174d] tracking-tight">{{ number_format($fpPrice, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Sudah termasuk pajak</span>
                                @else
                                <div class="flex items-baseline gap-1 lg:justify-end">
                                    <span class="text-2xl sm:text-3xl font-extrabold text-[#9d174d] tracking-tight">Hubungi Kami</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Harga menyesuaikan kebutuhanmu</span>
                                @endif
                            </div>
                            @if($fpPrice > 0)
                            <a href="{{ route('join.package', ['package' => $fp->slug ?? $fp->id]) }}"
                               class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#9d174d] to-[#831843] hover:from-[#b01e5a] hover:to-[#9d174d] active:scale-[0.98] text-white font-semibold text-sm sm:text-base tracking-wide shadow-md shadow-[#500724]/20 hover:shadow-lg transition duration-200 min-h-[48px]">
                                <span>Beli Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                </svg>
                            </a>
                            @else
                            <a href="{{ $fp->whatsapp_inquiry_url }}" target="_blank" rel="noopener"
                               class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#128C7E] to-[#075E54] hover:from-[#1DAE9E] hover:to-[#128C7E] active:scale-[0.98] text-white font-semibold text-sm sm:text-base tracking-wide shadow-md shadow-[#075E54]/20 hover:shadow-lg transition duration-200 min-h-[48px]">
                                <i class="fab fa-whatsapp text-lg" aria-hidden="true"></i>
                                <span>Hubungi Kami via WhatsApp</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </section>
                @endif

                @if($regularPackages->count())
                <!-- REGULAR SECTION -->
                <div class="pt-2 mt-6" role="region" aria-labelledby="apm-regular-label">
                    <div class="flex items-center gap-3 mb-4">
                        <h3 class="text-base sm:text-lg font-extrabold uppercase tracking-wider text-[#4a041f] flex-shrink-0" id="apm-regular-label">Paket Reguler</h3>
                        <div class="h-px bg-rose-200 flex-1" aria-hidden="true"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        @foreach($regularPackages as $package)
                            @include('member.packages.partials.modal-card', ['package' => $package])
                        @endforeach
                    </div>
                </div>
                @endif

                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <i class="fas fa-box-open text-2xl text-[#9d174d]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#4a041f] mb-2">Belum Ada Paket Tersedia</h3>
                    <p class="text-slate-500 text-sm">Saat ini belum ada paket membership yang dapat dibeli.</p>
                </div>
                @endif
            </main>

            <!-- ===== STICKY SECURITY FOOTER ===== -->
            <footer class="absolute inset-x-0 bottom-0 bg-white border-t border-rose-100/80 py-3.5 px-4 pb-safe flex items-center justify-center shadow-lg z-10">
                <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600 font-medium">
                    <span class="text-emerald-600 text-sm" aria-hidden="true">🔒</span>
                    <span><strong class="font-semibold text-slate-700">Pembayaran Aman</strong></span>
                    <span class="w-1 h-1 rounded-full bg-slate-300" aria-hidden="true"></span>
                    <span class="text-slate-500 font-normal">Didukung Midtrans</span>
                </div>
            </footer>
        </div>
    </div>
</div>

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
                    <div class="w-10 h-10"></div>
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
let apmTriggerEl = null;
function openAvailablePackagesModal() {
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    apmTriggerEl = document.activeElement;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');
    requestAnimationFrame(() => { modal.classList.add('open-modal'); });
    const closeBtn = modal.querySelector('.apm-close-btn');
    if (closeBtn) closeBtn.focus();
}
function closeAvailablePackagesModal(event) {
    if (event && event.target !== event.currentTarget) return;
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    modal.classList.remove('open-modal');
    document.body.classList.remove('modal-open');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (apmTriggerEl && typeof apmTriggerEl.focus === 'function') {
            apmTriggerEl.focus();
        }
        apmTriggerEl = null;
    }, 350);
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