<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Class | Studio</title>
    @vite('resources/css/app.css')
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #FCF9F2;
            color: #1C1C1C;
        }
        
        /* ============================================
           LAYOUT WRAPPER
           ============================================ */
        .page-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Note: Sidebar menggunakan Tailwind classes langsung dari dashboard */
        
        /* ============================================
           MAIN CONTAINER
           ============================================ */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* ============================================
           BREADCRUMB
           ============================================ */
        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .breadcrumb a {
            color: #7A2B4A;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb a:hover {
            color: #EE4E8B;
        }
        
        /* ============================================
           PAGE HEADER
           ============================================ */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #7A2B4A;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #64748b;
            font-size: 1rem;
        }
        
        /* ============================================
           PACKAGE STATUS CARD
           ============================================ */
        .status-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #EE4E8B;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .status-item {
            display: flex;
            align-items: start;
            gap: 1rem;
        }
        
        .status-icon {
            width: 48px;
            height: 48px;
            background: rgba(241,204,227,0.35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .status-info {
            flex: 1;
        }
        
        .status-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        .status-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #7A2B4A;
            line-height: 1.2;
        }
        
        .status-meta {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }
        
        /* ============================================
           PACKAGE SELECTOR
           ============================================ */
        .package-selector {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .package-selector label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #7A2B4A;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .package-selector select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            color: #2d3748;
            font-weight: 500;
        }
        
        .package-selector select:hover {
            border-color: #EE4E8B;
        }
        
        .package-selector select:focus {
            outline: none;
            border-color: #EE4E8B;
            box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.15);
        }
        
        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #047857;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }
        
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        
        .alert-icon {
            font-size: 1.25rem;
        }
        
        /* ============================================
           SCHEDULE SECTION
           ============================================ */
        .schedule-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .day-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .day-badge {
            background: #7A2B4A;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .day-count {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .schedule-card.is-selected > div {
            border-color: #EE4E8B;
            background: #FFF7FB;
            box-shadow: 0 10px 24px rgba(238, 78, 139, 0.12);
        }

        .schedule-card.is-selected .schedule-card-check {
            opacity: 1;
            transform: scale(1);
        }

        .schedule-card.is-selected .schedule-card-accent {
            background: #EE4E8B;
            color: #FFFFFF;
        }

        .schedule-card.is-selected .schedule-card-status {
            background: #FFF7FB;
            color: #7A2B4A;
            border-color: #EE4E8B;
        }
        
        /* ============================================
           SCHEDULE TABLE
           ============================================ */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .schedule-table thead {
            background: linear-gradient(90deg, #FCF9F2 0%, rgba(241,204,227,0.40) 100%);
        }
        
        .schedule-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: #7A2B4A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(238, 78, 139,0.25);
        }
        
        .schedule-table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .schedule-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .schedule-table tbody tr:hover {
            background: rgba(241,204,227,0.15);
        }
        
        .class-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .class-icon {
            width: 40px;
            height: 40px;
            background: rgba(241,204,227,0.35);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .class-name {
            font-weight: 600;
            color: #7A2B4A;
            font-size: 0.95rem;
        }
        
        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(210,220,165,0.30);
            border-radius: 6px;
            font-weight: 600;
            color: #1D5A4B;
            font-size: 0.95rem;
        }
        
        .coach-name {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        /* ============================================
           STATUS BADGES
           ============================================ */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .status-badge.available {
            background: rgba(26, 122, 94,0.10);
            color: #1A7A5E;
        }
        
        .status-badge.booked {
            background: rgba(241,204,227,0.35);
            color: #7A2B4A;
        }
        
        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5A1F3A 0%, #B83863 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(122, 43, 74, 0.35);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #1D5A4B, #1A7A5E);
            color: white;
            cursor: not-allowed;
        }
        
        .btn-disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }
        
        /* ============================================
           EMPTY STATE
           ============================================ */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .empty-state-icon {
            font-size: 5rem;
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        
        .empty-state p {
            color: #64748b;
            font-size: 1rem;
        }
        
        /* ============================================
           RESPONSIVE DESIGN
           ============================================ */
        @media (max-width: 968px) {
            .main-content {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .status-grid {
                grid-template-columns: 1fr;
            }
            
            .schedule-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        @media (max-width: 640px) {
            .breadcrumb {
                font-size: 0.75rem;
            }
            
            .day-badge {
                font-size: 0.75rem;
                padding: 0.4rem 0.75rem;
            }
            
            .schedule-table th,
            .schedule-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
        }

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
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 30;
                transform: translateX(-100%);
                box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .hamburger-btn {
                display: flex !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .main-content {
                margin-top: 3rem;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
 <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-cream h-screen overflow-hidden">

<div class="flex h-screen">

    @include('partials.member-sidebar')

    {{-- ============================================
         MAIN CONTENT AREA
         ============================================ --}}
    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn fixed top-4 left-4 z-30 w-10 h-10 bg-dark text-white rounded-lg items-center justify-center shadow-lg hover:bg-secondary transition" onclick="toggleSidebar()">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <main class="flex-1 overflow-y-auto">
        
        <div class="main-content">
        
            {{-- ============================================
                 HEADER WITH USER INFO
                 ============================================ --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-dark">
                    Book Your Class
                </h1>
                <p class="text-sm text-cream0">
                    Welcome back, {{ $customer->name ?? 'Member' }}
                </p>
            </div>

        {{-- ============================================
             FLASH MESSAGES
             ============================================ --}}
        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span class="alert-icon">⚠</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ============================================
             PACKAGE STATUS CARD
             ============================================ --}}
        @if(isset($selectedPackage))
            <div class="status-card">
                <div class="status-grid">
                    <div class="status-item">
                        <div class="status-icon">
                            @if($selectedPackage->is_exclusive)
                                ⭐
                            @else
                                📦
                            @endif
                        </div>
                        <div class="status-info">
                            <div class="status-label">Active Package</div>
                            <div class="status-value">{{ $selectedPackage->name }}</div>
                            <div class="status-meta">
                                @if($selectedPackage->is_exclusive)
                                    Exclusive Package
                                @else
                                    Regular Package
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-icon">🎫</div>
                        <div class="status-info">
                            <div class="status-label">Credit</div>
                            <div class="status-value">
                                @if($selectedPackage->is_exclusive)
                                    0
                                    <div class="status-meta">Jadwal sudah di-assign otomatis</div>
                                @else
                                    {{ $remainingClasses ?? $customer->quota }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($customer->quota_expired_at)
                        <div class="status-item">
                            <div class="status-icon">📅</div>
                            <div class="status-info">
                                <div class="status-label">Valid Until</div>
                                <div class="status-value">{{ \Carbon\Carbon::parse($customer->quota_expired_at)->format('d M Y') }}</div>
                            </div>
                        </div>
                    @elseif(isset($selectedPackage) && $selectedPackage->duration_days)
                        <div class="status-item">
                            <div class="status-icon">📅</div>
                            <div class="status-info">
                                <div class="status-label">Valid Until</div>
                                <div class="status-value" style="font-size: 1rem; color: #7A2B4A;">Belum dimulai</div>
                                <div class="status-meta">Aktif {{ $selectedPackage->duration_days }} hari saat booking pertama</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ============================================
             PACKAGE SELECTOR (Multiple Packages)
             ============================================ --}}
        @if(isset($activeOrders) && $activeOrders->count() > 1)
            <div class="package-selector">
                <label for="packageSelect">Select Package</label>
                <form method="GET" action="{{ route('member.book') }}" id="packageForm">
                    <select name="order_id" id="packageSelect" onchange="document.getElementById('packageForm').submit()">
                        @foreach($activeOrders as $order)
                            <option 
                                value="{{ $order->id }}" 
                                {{ isset($selectedOrderId) && $selectedOrderId == $order->id ? 'selected' : '' }}>
                                {{ $order->package->name ?? 'Unknown Package' }}
                                @if($order->package && $order->package->is_exclusive)
                                    ⭐ (Exclusive)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        {{-- ============================================
             SCHEDULE SECTIONS - GROUPED BY DAY
             ============================================ --}}
        @if(isset($schedules) && $schedules->isNotEmpty())
            <div class="space-y-6 pb-32">
                @foreach($schedules as $day => $items)
                    <section class="rounded-3xl border border-[#F4C9DF] bg-white/80 p-4 shadow-sm backdrop-blur-sm md:p-5">
                        <div class="flex items-center justify-between gap-3 border-b border-[#F4C9DF] pb-4">
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-[#7A2B4A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-white">{{ $day }}</span>
                                <span class="text-sm font-medium text-[#7A2B4A]/70">{{ $items->count() }} classes available</span>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-[0.14em] text-[#7A2B4A]/45">Tap cards to select</span>
                        </div>

                        <div class="mt-4 grid gap-4">
                            @foreach($items as $s)
                                @php
                                    $isBooked = isset($bookedScheduleIds) && in_array($s->id, $bookedScheduleIds);
                                    $isDisabled = $customer->quota <= 0;
                                    $classIcon = match($s->classModel->class_name ?? '') {
                                        'Reformer Pilates' => '🧘‍♀️',
                                        'Mat Pilates' => '🧘',
                                        'Muaythai Beginner', 'Muaythai Intermediate' => '🥊',
                                        'Body Shaping' => '💪',
                                        default => '🎯',
                                    };
                                @endphp

                                {{-- Hidden form for single class booking --}}
                                <form id="book-form-{{ $s->id }}" action="{{ route('member.book.store') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                </form>
                                
                                <label class="schedule-card group block cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        class="peer sr-only schedule-checkbox"
                                        value="{{ $s->id }}"
                                        data-schedule-id="{{ $s->id }}"
                                        data-class-name="{{ e($s->classModel->class_name ?? 'Class') }}"
                                        data-day="{{ e($day) }}"
                                        data-date="{{ e($s->schedule_date_formatted) }}"
                                        data-time="{{ e(\Carbon\Carbon::parse($s->class_time)->format('H:i')) }}"
                                        data-instructor="{{ e($s->instructor ?? '-') }}"
                                        onclick="handleScheduleClick(event, this)"
                                        {{ $isBooked || $isDisabled ? 'disabled' : '' }}
                                    >

                                    <div class="relative overflow-hidden rounded-2xl border border-[#F4C9DF] bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md peer-checked:border-[#EE4E8B] peer-checked:bg-[#FFF7FB] peer-checked:shadow-md {{ $isBooked ? 'opacity-60' : '' }} {{ $isDisabled ? 'opacity-50' : '' }}">
                                        <div class="schedule-card-check absolute right-3 top-3 flex h-6 w-6 items-center justify-center rounded-full border border-[#EE4E8B] bg-white text-[0.75rem] text-[#EE4E8B] opacity-0 transition-all duration-200 peer-checked:opacity-100">
                                            <i class="fas fa-check"></i>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="schedule-card-accent flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#FBEAF0] text-lg text-[#EE4E8B] transition-colors duration-200">
                                                {{ $classIcon }}
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-start justify-between gap-2">
                                                    <div class="min-w-0">
                                                        <h3 class="truncate text-base font-extrabold leading-tight text-[#7A2B4A]">{{ $s->classModel->class_name ?? 'Class' }}</h3>
                                                        <p class="mt-1 text-sm font-medium text-[#1C1C1C]">Coach {{ $s->instructor ?? '-' }}</p>
                                                        <p class="mt-1 text-sm text-[#7A2B4A]/80">{{ \Carbon\Carbon::parse($s->schedule_date)->format('M d, Y') }} • {{ \Carbon\Carbon::parse($s->class_time)->format('h:i A') }}</p>
                                                    </div>

                                                    @if($isBooked)
                                                        <span class="schedule-card-status inline-flex items-center gap-2 rounded-full border border-transparent bg-[#F0FDF4] px-3 py-1 text-xs font-semibold text-[#1A7A5E]"><span class="h-2 w-2 rounded-full bg-[#1A7A5E]"></span>Booked</span>
                                                    @elseif($isDisabled)
                                                        <span class="schedule-card-status inline-flex items-center gap-2 rounded-full border border-transparent bg-[#F4C9DF]/50 px-3 py-1 text-xs font-semibold text-[#7A2B4A]">Quota empty</span>
                                                    @else
                                                        <span class="schedule-card-status inline-flex items-center gap-2 rounded-full border border-transparent bg-[#F0FDF4] px-3 py-1 text-xs font-semibold text-[#1A7A5E]"><span class="h-2 w-2 rounded-full bg-[#1A7A5E]"></span>Available</span>
                                                    @endif
                                                </div>

                                                <div class="mt-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-[#7A2B4A]/55">
                                                    <span class="rounded-full bg-[#7A2B4A] px-2.5 py-1 text-white">{{ $day }}</span>
                                                    <span class="text-[#7A2B4A]/45">•</span>
                                                    <span>{{ $isBooked ? 'Already booked' : 'Select this class' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                <div id="bulk-booking-bar" class="fixed inset-x-0 bottom-0 z-40 border-t border-[#F4C9DF] bg-[#FCF9F2]/95 px-4 py-4 backdrop-blur-md md:px-6">
                    <div class="mx-auto flex w-full max-w-5xl items-center gap-3 rounded-2xl border border-[#F4C9DF] bg-white px-4 py-4 shadow-sm md:px-5">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <p class="text-sm font-semibold text-[#7A2B4A]"><span id="selected-class-count">0</span> Classes Selected</p>
                                <span class="text-xs font-medium uppercase tracking-[0.14em] text-[#7A2B4A]/45">Single confirm booking</span>
                            </div>
                            <p class="mt-1 text-sm text-[#1C1C1C]/70"><span id="selected-session-count">0</span> booked sessions ready to submit</p>
                        </div>

                        <button type="button" id="book-selected-btn" onclick="bookSelectedClasses()" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl bg-[#EE4E8B] px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#D9467D] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Book Selected Classes</span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- ============================================
                 EMPTY STATE
                 ============================================ --}}
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No Schedules Available</h3>
                <p>There are no classes available for your package at the moment. Please contact admin for assistance.</p>
            </div>
        @endif

        </div>
        {{-- End of main-content --}}
        
    </main>
    {{-- End of main --}}

</div>
{{-- End of flex wrapper --}}

{{-- ============================================
     BOOKING CONFIRMATION MODAL
     ============================================ --}}
<div id="book-confirm-modal" style="display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:white; border-radius:16px; max-width:440px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.2); animation:modalIn 0.25s ease-out; overflow:hidden;">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, rgba(241,204,227,0.30) 0%, rgba(244,238,230,1) 100%); padding:1.5rem 2rem; border-bottom:1px solid rgba(238, 78, 139,0.20); text-align:center;">
            <div style="width:56px; height:56px; background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.5rem; color:white;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 style="font-size:1.15rem; font-weight:700; color:#7A2B4A; margin:0;">Konfirmasi Booking</h3>
            <p style="font-size:0.825rem; color:#64748b; margin-top:4px;">Apakah Anda yakin ingin booking jadwal ini?</p>
        </div>

        {{-- Class Details --}}
        <div style="padding:1.5rem 2rem;">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; margin-bottom:1.25rem;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div>
                        <div style="font-size:0.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Class</div>
                        <div id="confirm-class" style="font-size:0.95rem; font-weight:700; color:#7A2B4A;">—</div>
                    </div>
                    <div>
                        <div style="font-size:0.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Day</div>
                        <div id="confirm-day" style="font-size:0.95rem; font-weight:700; color:#7A2B4A;">—</div>
                    </div>
                    <div>
                        <div style="font-size:0.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Date</div>
                        <div id="confirm-date" style="font-size:0.95rem; font-weight:700; color:#7A2B4A;">—</div>
                    </div>
                    <div>
                        <div style="font-size:0.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Time</div>
                        <div id="confirm-time" style="font-size:0.95rem; font-weight:700; color:#7A2B4A;">—</div>
                    </div>
                    <div>
                        <div style="font-size:0.65rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Coach</div>
                        <div id="confirm-coach" style="font-size:0.95rem; font-weight:700; color:#7A2B4A;">—</div>
                    </div>
                </div>
            </div>

            {{-- Warning --}}
            <div style="background:#fef3c7; border-left:3px solid #f59e0b; border-radius:0 8px 8px 0; padding:0.75rem 1rem; font-size:0.8rem; color:#92400e; display:flex; gap:8px; align-items:flex-start; margin-bottom:1.25rem;">
                <i class="fas fa-exclamation-triangle" style="margin-top:2px; flex-shrink:0;"></i>
                <span>Booking ini akan mengurangi 1 kuota kelas Anda. Pastikan jadwal yang dipilih sudah benar.</span>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; gap:0.75rem;">
                <button onclick="closeBookConfirm()" 
                    style="flex:1; padding:0.8rem; border:2px solid #e2e8f0; background:white; color:#64748b; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; transition:all 0.2s; font-family:inherit;"
                    onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" 
                    onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-times" style="margin-right:6px;"></i>Batal
                </button>
                <button id="confirm-book-btn" onclick="confirmBook()" 
                    style="flex:1; padding:0.8rem; border:none; background:linear-gradient(135deg,#7A2B4A,#EE4E8B); color:white; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; transition:all 0.2s; font-family:inherit; box-shadow:0 4px 12px rgba(122, 43, 74,0.30);"
                    onmouseover="this.style.background='linear-gradient(135deg,#5A1F3A,#B83863)'" 
                    onmouseout="this.style.background='linear-gradient(135deg,#7A2B4A,#EE4E8B)'">
                    <i class="fas fa-check" style="margin-right:6px;"></i>Ya, Book Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

    <style>
        @keyframes modalIn {
            0% { opacity: 0; transform: scale(0.95) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>

<script>
    let pendingScheduleId = null;

    function handleScheduleClick(event, checkbox) {
        if (checkbox.disabled) return;
        
        // Jika checkbox belum checked, tampilkan modal konfirmasi
        if (!checkbox.checked) {
            const className = checkbox.dataset.className;
            const day = checkbox.dataset.day;
            const date = checkbox.dataset.date;
            const time = checkbox.dataset.time;
            const coach = checkbox.dataset.instructor;
            const scheduleId = checkbox.dataset.scheduleId;
            
            showBookConfirm(className, day, date, time, coach, scheduleId);
            
            // Prevent checkbox from being checked until user confirms
            event.preventDefault();
            event.stopPropagation();
            return false;
        } else {
            // If already checked, allow unchecking directly
            return true;
        }
    }

    function showBookConfirm(className, day, date, time, coach, scheduleId) {
        document.getElementById('confirm-class').textContent = className;
        document.getElementById('confirm-day').textContent = day;
        document.getElementById('confirm-date').textContent = date;
        document.getElementById('confirm-time').textContent = time;
        document.getElementById('confirm-coach').textContent = coach;
        pendingScheduleId = scheduleId;

        const modal = document.getElementById('book-confirm-modal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeBookConfirm() {
        const modal = document.getElementById('book-confirm-modal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        pendingScheduleId = null;
    }

    function confirmBook() {
        if (!pendingScheduleId) return;
        const form = document.getElementById('book-form-' + pendingScheduleId);
        const checkbox = document.querySelector(`.schedule-checkbox[data-schedule-id="${pendingScheduleId}"]`);
        
        if (form) {
            // Disable button to prevent double-click
            const btn = document.getElementById('confirm-book-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Booking...';
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
            
            // Check the checkbox visually
            if (checkbox) {
                checkbox.checked = true;
                updateSelectedCardStates();
                updateBulkBookingBar();
            }
            
            form.submit();
        }
    }

    const bookingStoreUrl = @json(route('member.book.store'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function getSelectedScheduleCheckboxes() {
        return Array.from(document.querySelectorAll('.schedule-checkbox:checked'));
    }

    function updateBulkBookingBar() {
        const selected = getSelectedScheduleCheckboxes();
        const count = selected.length;
        const selectedClassCount = document.getElementById('selected-class-count');
        const selectedSessionCount = document.getElementById('selected-session-count');
        const button = document.getElementById('book-selected-btn');

        if (selectedClassCount) selectedClassCount.textContent = count;
        if (selectedSessionCount) selectedSessionCount.textContent = count;
        if (button) button.disabled = count === 0;
    }

    function updateSelectedCardStates() {
        document.querySelectorAll('.schedule-checkbox').forEach(checkbox => {
            const card = checkbox.closest('.schedule-card');
            if (!card) return;
            card.classList.toggle('is-selected', checkbox.checked);
        });
    }

    async function bookSelectedClasses() {
        const selected = getSelectedScheduleCheckboxes();
        if (!selected.length) return;

        const button = document.getElementById('book-selected-btn');
        const originalHtml = button?.innerHTML || '';
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Booking...</span>';
        }

        try {
            for (const checkbox of selected) {
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('schedule_id', checkbox.value);

                await fetch(bookingStoreUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/plain, */*'
                    },
                    body: formData,
                });
            }

            window.location.reload();
        } catch (error) {
            console.error('Bulk booking failed', error);
            window.location.reload();
        } finally {
            if (button) {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        }
    }

    // Close on clicking backdrop
    document.getElementById('book-confirm-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeBookConfirm();
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeBookConfirm();
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.schedule-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCardStates();
                updateBulkBookingBar();
            });
        });
        updateSelectedCardStates();
        updateBulkBookingBar();
    });

    // ===== SIDEBAR TOGGLE FUNCTION =====
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');

        if (!sidebar) return;

        const willOpen = !sidebar.classList.contains('open') && !sidebar.classList.contains('active');
        sidebar.classList.toggle('open');
        sidebar.classList.toggle('active');

        if (willOpen) {
            document.body.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
            if (hamburger) hamburger.style.display = 'none';
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
        } else {
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = '';
            if (hamburger) { hamburger.style.display = ''; hamburger.innerHTML = '<i class="fas fa-bars text-lg"></i>'; }
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
        }
    }

    // Close sidebar when clicking on a nav link
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('aside nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar && sidebar.classList.contains('open')) toggleSidebar();
                }
            });
        });
    });

    // Reset sidebar on window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');
        
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            sidebar.classList.remove('active');
            if (hamburger) {
                hamburger.style.display = '';
                hamburger.innerHTML = '<i class="fas fa-bars text-lg"></i>';
            }
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>
