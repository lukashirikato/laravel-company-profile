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
            background: linear-gradient(135deg, #EA6993 0%, #793451 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* ===== MODAL STYLES ===== */
        .modal-backdrop {
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(0px);
        }
        .modal-backdrop.active {
            opacity: 1;
            backdrop-filter: blur(6px);
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
        .modal-tab:hover { color: #793451; }
        .modal-tab.active {
            color: #793451;
            border-bottom-color: #EA6993;
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
            border: 3px solid #F1CCE3;
            border-top-color: #EA6993;
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
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

 <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- ========================================
         SIDEBAR
    ======================================== -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <a href="{{ route('member.profile') }}" class="px-6 py-5 text-xl font-bold border-b border-white/20 hover:bg-slate-800 transition inline-block w-full">
            FTM SOCIETY
        </a>

        <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
            <a href="{{ route('member.dashboard') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>

            <a href="{{ route('member.packages.index') }}" 
               class="block px-4 py-2 rounded text-white font-medium" style="background: linear-gradient(90deg, #793451 0%, #EA6993 100%); border-left: 3px solid #F1CCE3;">
                <i class="fas fa-box mr-2"></i>My Packages
            </a>

            <a href="{{ route('member.book') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-calendar-plus mr-2"></i>Book Class
            </a>

            <a href="{{ route('member.my-classes') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-dumbbell mr-2"></i>My Classes
            </a>

            <a href="{{ route('member.transactions') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-receipt mr-2"></i>Transactions
            </a>

            <a href="{{ route('member.attendance') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-calendar-check mr-2"></i>Attendance
            </a>

            <a href="{{ route('member.account') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-user mr-2"></i>Profile
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
            &copy; {{ date('Y') }} FTM Society
        </div>
    </aside>

{{-- ================= MAIN ================= --}}
<main class="flex-1 p-8 overflow-y-auto">

{{-- ================= HEADER ================= --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-primary-dark text-lg"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Packages</h1>
            </div>
            <p class="text-gray-500 ml-[52px]">Kelola dan pantau paket membership Anda</p>
        </div>
        
        {{-- ✅ TOMBOL REDIRECT KE PRICING --}}
        <a href="{{ route('member.profile') }}#Packages" 
           class="bg-gradient-to-r from-primary-dark to-primary hover:from-[#5A1F3A] hover:to-[#B83863] text-white px-6 py-3 rounded-xl font-semibold transition-all inline-flex items-center shadow-lg hover:shadow-xl hover:-translate-y-0.5">
            <i class="fas fa-plus-circle mr-2"></i>
            Beli Paket Lagi
        </a>
    </div>
</div>

{{-- ================= ACTIVE PACKAGES ================= --}}
@if($activePackages?->count())

<div class="mb-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-check-circle text-green-500"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900">Paket Aktif</h2>
        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
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
                            @elseif($pkg && $pkg->duration_days)
                                <span class="text-blue-600">
                                    <i class="fas fa-clock mr-1"></i>Belum dimulai
                                </span>
                                <span class="block text-xs font-normal mt-1 text-gray-500">
                                    @if($pkg->is_exclusive)
                                        Masa aktif {{ $pkg->duration_days }} hari dimulai dari jadwal pertama
                                    @else
                                        Masa aktif {{ $pkg->duration_days }} hari dimulai saat booking pertama
                                    @endif
                                </span>
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

                    {{-- Usage Progress --}}
                    @php
                        $totalQuota = $pkg->quota ?? 0;
                        $classesLeft = $order->remaining_classes ?? $order->remaining_sessions ?? $totalQuota;
                        $used = max(0, $totalQuota - $classesLeft);
                        $usagePercent = $totalQuota > 0 ? ($used / $totalQuota) * 100 : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemakaian Kelas</span>
                            <span class="text-xs font-bold {{ $classesLeft <= 0 ? 'text-red-600' : ($classesLeft <= 2 ? 'text-amber-600' : 'text-primary-dark') }}">
                                {{ $used }}/{{ $totalQuota }} terpakai
                            </span>
                        </div>
                        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $classesLeft <= 0 ? 'bg-red-500' : ($classesLeft <= 2 ? 'bg-amber-500' : 'bg-primary') }}"
                                 style="width: {{ min($usagePercent, 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[11px] text-gray-400">{{ $classesLeft }} kelas tersedia</span>
                            @if($classesLeft <= 0)
                                <span class="text-[11px] text-red-500 font-medium"><i class="fas fa-exclamation-circle mr-0.5"></i>Habis</span>
                            @elseif($classesLeft <= 2)
                                <span class="text-[11px] text-amber-500 font-medium"><i class="fas fa-exclamation-triangle mr-0.5"></i>Hampir habis</span>
                            @endif
                        </div>
                    </div>

                    
                </div>

                {{-- Footer --}}
                <div class="px-6 pb-6">
                    <button onclick="openPackageModal({{ $order->id }})"
                       class="block w-full text-center bg-gradient-to-r from-primary-dark to-primary hover:from-[#5A1F3A] hover:to-[#B83863] text-white py-3 rounded-lg font-medium transition-all cursor-pointer shadow hover:shadow-md">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
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
        <i class="fas fa-box-open text-3xl" style="color: #793451;"></i>
    </div>
    
    <h3 class="text-2xl font-bold mb-3 text-gray-800">Belum Ada Paket Aktif</h3>
    
    <p class="text-gray-500 mb-6">
        Mulai perjalanan fitness Anda dengan membeli paket pertama
    </p>
    
    <a href="{{ route('member.book') }}"
       class="bg-gradient-to-r from-primary-dark to-primary hover:from-[#5A1F3A] hover:to-[#B83863] text-white px-8 py-3 rounded-lg font-medium transition-all inline-flex items-center shadow-md hover:shadow-lg">
        <i class="fas fa-plus-circle mr-2"></i>
        Lihat Paket Tersedia
    </a>
</div>

@endif

{{-- ================= PAST/EXPIRED PACKAGES ================= --}}
@if($pastPackages?->count())

<div class="mt-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-history text-gray-500"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900">Riwayat Paket</h2>
        <span class="bg-gray-100 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">
            {{ $pastPackages->count() }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
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
                            <button onclick="openPackageModal({{ $order->id }})"
                               class="inline-flex items-center text-primary-dark hover:text-primary text-sm font-medium cursor-pointer">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
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

<!-- ========================================
     PACKAGE DETAIL MODAL
======================================== -->
<div id="packageModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/50 flex items-center justify-center p-4" onclick="closePackageModal(event)">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div id="modalHeader" class="p-6 border-b border-gray-100 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(241,204,227,0.35);">
                            <i class="fas fa-box" style="color: #793451;"></i>
                        </div>
                        <div>
                            <h2 id="modalPackageName" class="text-xl font-bold text-gray-900">Loading...</h2>
                            <p id="modalOrderCode" class="text-sm text-gray-500"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="modalStatusBadge" class="status-badge"></span>
                        <button onclick="closePackageModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tabs -->
            <div class="flex border-b border-gray-200 px-6 shrink-0" id="modalTabs">
                <button class="modal-tab active" data-tab="info" onclick="switchTab('info')">
                    <i class="fas fa-info-circle mr-1.5"></i>Info Paket
                </button>
                <button class="modal-tab" data-tab="schedules" onclick="switchTab('schedules')">
                    <i class="fas fa-calendar mr-1.5"></i>Kelas Terdaftar
                </button>
                <button class="modal-tab" data-tab="attendance" onclick="switchTab('attendance')">
                    <i class="fas fa-clipboard-check mr-1.5"></i>Riwayat Kehadiran
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto" id="modalBody">
                <!-- Loading State -->
                <div id="modalLoading" class="modal-loading">
                    <div class="text-center">
                        <div class="spinner mx-auto mb-3"></div>
                        <p class="text-sm text-gray-500">Memuat detail paket...</p>
                    </div>
                </div>

                <!-- Tab: Info Paket -->
                <div id="tabInfo" class="tab-panel active p-6">
                    <!-- Usage Progress -->
                    <div id="usageSection" class="mb-6 p-4 rounded-xl" style="background: linear-gradient(135deg, rgba(241,204,227,0.30) 0%, rgba(210,220,165,0.20) 100%);"></div>
                    <!-- Package Info -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">
                            <i class="fas fa-cube mr-1.5" style="color: #793451;"></i>Detail Paket
                        </h4>
                        <div id="packageInfoRows" class="bg-gray-50 rounded-xl p-4"></div>
                    </div>
                    <!-- Order Info -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">
                            <i class="fas fa-receipt mr-1.5" style="color: #793451;"></i>Detail Pembelian
                        </h4>
                        <div id="orderInfoRows" class="bg-gray-50 rounded-xl p-4"></div>
                    </div>
                </div>

                <!-- Tab: Kelas Terdaftar -->
                <div id="tabSchedules" class="tab-panel p-6">
                    <div id="schedulesContent"></div>
                </div>

                <!-- Tab: Riwayat Kehadiran -->
                <div id="tabAttendance" class="tab-panel p-6">
                    <div id="attendanceContent"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <a id="btnExtend" href="#" class="flex-1 text-center text-white py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 inline-flex items-center justify-center" style="background: linear-gradient(135deg, #793451 0%, #EA6993 100%);">
                        <i class="fas fa-sync-alt mr-2"></i>Perpanjang Paket
                    </a>
                    <a href="{{ route('member.book') }}" class="flex-1 text-center bg-white py-3 rounded-xl font-semibold transition-all inline-flex items-center justify-center" style="border: 2px solid #793451; color: #793451;" onmouseover="this.style.background='rgba(241,204,227,0.25)'" onmouseout="this.style.background='white'">
                        <i class="fas fa-calendar-plus mr-2"></i>Book Class
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ===== MODAL LOGIC =====
let currentOrderId = null;

function openPackageModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('packageModal');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
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
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closePackageModal();
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
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <p class="text-sm text-red-600 font-medium">Gagal memuat detail paket</p>
                button onclick="fetchPackageDetail(${orderId})" class="mt-3 text-sm font-medium" style="color: #793451;">
                    <i class="fas fa-redo mr-1"></i>Coba Lagi
                </button>
            </div>
        `;
    }
}

function populateModal(data) {
    const { order, package: pkg, usage, booked_schedules, attendance_history, stats } = data;
    
    // Header
    document.getElementById('modalPackageName').textContent = pkg.name;
    document.getElementById('modalOrderCode').innerHTML = `<i class="fas fa-barcode mr-1"></i>${order.order_code}`;
    
    const badge = document.getElementById('modalStatusBadge');
    if (order.is_expired) {
        badge.className = 'status-badge status-expired';
        badge.innerHTML = '<i class="fas fa-times-circle"></i> Expired';
    } else {
        badge.className = 'status-badge status-active';
        badge.innerHTML = '<i class="fas fa-check-circle"></i> Active';
    }
    
    // Usage Progress
    const usageColor = usage.remaining <= 0 ? 'red' : (usage.remaining <= 2 ? 'amber' : 'brand');
    document.getElementById('usageSection').innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold" style="color:#26282B">Pemakaian Kelas</span>
            <span class="text-sm font-bold" style="color: ${usageColor === 'red' ? '#dc2626' : usageColor === 'amber' ? '#d97706' : '#793451'}">${usage.used}/${usage.total_quota} terpakai</span>
        </div>
        <div class="h-3 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.60);">
            <div class="h-full rounded-full transition-all duration-700" style="width: ${Math.min(usage.percentage, 100)}%; background: ${usageColor === 'red' ? '#ef4444' : usageColor === 'amber' ? '#f59e0b' : 'linear-gradient(90deg,#793451,#EA6993)'}"></div>
        </div>
        <div class="flex justify-between mt-2">
            <span class="text-xs text-gray-500">${usage.remaining} kelas tersisa</span>
            ${order.remaining_days > 0 ? `<span class="text-xs text-gray-500">${order.remaining_days} hari tersisa</span>` : ''}
        </div>
    `;
    
    // Package Info
    document.getElementById('packageInfoRows').innerHTML = `
        <div class="info-row"><span class="info-label">Nama Paket</span><span class="info-value">${pkg.name}</span></div>
        <div class="info-row"><span class="info-label">Deskripsi</span><span class="info-value">${pkg.description}</span></div>
        <div class="info-row"><span class="info-label">Harga</span><span class="info-value">${pkg.price_formatted}</span></div>
        <div class="info-row"><span class="info-label">Total Kuota</span><span class="info-value">${pkg.quota} kelas</span></div>
        <div class="info-row"><span class="info-label">Durasi</span><span class="info-value">${pkg.duration_days ? pkg.duration_days + ' hari' : 'Unlimited'}</span></div>
        <div class="info-row"><span class="info-label">Mode Jadwal</span><span class="info-value capitalize">${pkg.schedule_mode}</span></div>
    `;
    
    // Order Info
    document.getElementById('orderInfoRows').innerHTML = `
        <div class="info-row"><span class="info-label">Kode Order</span><span class="info-value">${order.order_code}</span></div>
        <div class="info-row"><span class="info-label">Tanggal Pembelian</span><span class="info-value">${order.created_at}</span></div>
        <div class="info-row"><span class="info-label">Berakhir</span><span class="info-value">${order.expired_at_full || '<span class="text-green-600"><i class="fas fa-infinity mr-1"></i>Unlimited</span>'}</span></div>
        <div class="info-row"><span class="info-label">Sisa Waktu</span><span class="info-value">${order.remaining_time}</span></div>
        <div class="info-row"><span class="info-label">Harga Dibayar</span><span class="info-value">${order.amount_formatted}</span></div>
        ${order.discount > 0 ? `<div class="info-row"><span class="info-label">Diskon</span><span class="info-value text-green-600">-Rp ${Number(order.discount).toLocaleString('id-ID')}</span></div>` : ''}
        <div class="info-row"><span class="info-label">Sisa Kelas</span><span class="info-value">${order.remaining_classes}</span></div>
        <div class="info-row"><span class="info-label">Sisa Kuota Check-in</span><span class="info-value">${order.remaining_quota}</span></div>
    `;
    
    // Booked Schedules Tab
    if (booked_schedules.length > 0) {
        let schedulesHtml = `<div class="space-y-3">`;
        booked_schedules.forEach(s => {
            const statusClass = s.status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700';
            schedulesHtml += `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(241,204,227,0.35);">
                            <i class="fas fa-dumbbell" style="color: #793451;"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">${s.schedule_name}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <i class="fas fa-calendar-day mr-1"></i>${s.day},
                                <i class="fas fa-calendar mr-1 ml-1"></i><span class="font-semibold" style="color:#793451">${s.date}</span> &bull;
                                <i class="fas fa-clock mr-1 ml-1"></i>${s.time} &bull;
                                <i class="fas fa-user mr-1 ml-1"></i>${s.instructor}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${statusClass} capitalize">${s.status}</span>
                </div>
            `;
        });
        schedulesHtml += `</div>`;
        document.getElementById('schedulesContent').innerHTML = schedulesHtml;
    } else {
        document.getElementById('schedulesContent').innerHTML = `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-xmark text-gray-400 text-xl"></i>
                </div>
                <p class="text-sm text-gray-500">Belum ada kelas terdaftar</p>
                <a href="{{ route('member.book') }}" class="inline-flex items-center text-sm font-medium mt-2" style="color: #793451;">
                    <i class="fas fa-plus mr-1"></i>Book Kelas Sekarang
                </a>
            </div>
        `;
    }
    
    // Attendance Tab
    if (attendance_history.length > 0) {
        let attendanceHtml = `
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Total kehadiran: <span class="font-bold text-gray-800">${stats.total_attendance}x</span>
                </p>
            </div>
            <div class="space-y-3">
        `;
        attendance_history.forEach(a => {
            const statusIcon = a.check_out_at ? 'fa-check-circle text-green-500' : 'fa-clock text-amber-500';
            attendanceHtml += `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">${a.program}</p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>${a.check_in_at}
                                ${a.check_out_at ? ` - ${a.check_out_at}` : ''}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-800">${a.short_duration}</span>
                        <p class="text-xs text-gray-400 capitalize">${a.check_in_type}</p>
                    </div>
                </div>
            `;
        });
        attendanceHtml += `</div>`;
        document.getElementById('attendanceContent').innerHTML = attendanceHtml;
    } else {
        document.getElementById('attendanceContent').innerHTML = `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clipboard text-gray-400 text-xl"></i>
                </div>
                <p class="text-sm text-gray-500">Belum ada riwayat kehadiran</p>
            </div>
        `;
    }
    
    // Set Extend button link
    document.getElementById('btnExtend').href = `{{ route('member.profile') }}#Packages`;
    
    // Hide loading, show first tab
    document.getElementById('modalLoading').style.display = 'none';
    switchTab('info');
    document.querySelectorAll('.tab-panel.active').forEach(p => p.style.display = 'block');
}
</script>

</body>
</html>