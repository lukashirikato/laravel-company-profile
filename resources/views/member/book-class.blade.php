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
</head>
<body class="bg-cream h-screen overflow-hidden">

 <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
</head>

<body class="bg-cream h-screen overflow-hidden">

<div class="flex h-screen">

    @include('partials.member-sidebar')

    {{-- ============================================
         MAIN CONTENT AREA
         ============================================ --}}
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

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
            @foreach($schedules as $day => $items)
                <div class="schedule-section">
                    <div class="day-header">
                        <div class="day-badge">{{ $day }}</div>
                        <div class="day-count">{{ $items->count() }} {{ $items->count() === 1 ? 'class' : 'classes' }} available</div>
                    </div>
                    
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Coach</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $s)
                                @php
                                    $isBooked = isset($bookedScheduleIds) && in_array($s->id, $bookedScheduleIds);
                                    $isDisabled = $customer->quota <= 0;
                                @endphp
                                
                                <tr>
                                    <td>
                                        <div class="class-info">
                                            <div class="class-icon">
                                                @if(isset($s->classModel))
                                                    @switch($s->classModel->class_name)
                                                        @case('Reformer Pilates')
                                                            🧘‍♀️
                                                            @break
                                                        @case('Mat Pilates')
                                                            🧘
                                                            @break
                                                        @case('Muaythai Beginner')
                                                        @case('Muaythai Intermediate')
                                                            🥊
                                                            @break
                                                        @case('Body Shaping')
                                                            💪
                                                            @break
                                                        @default
                                                            🎯
                                                    @endswitch
                                                @else
                                                    🎯
                                                @endif
                                            </div>
                                            <div class="class-name">{{ $s->classModel->class_name ?? 'Class' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600; color:#7A2B4A; font-size:0.95rem;">
                                            {{ $s->schedule_date_formatted }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-badge">
                                            <span>🕐</span>
                                            <span>{{ \Carbon\Carbon::parse($s->class_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="coach-name"> {{ $s->instructor ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if($isBooked)
                                            <span class="status-badge booked">✓ Booked</span>
                                        @else
                                            <span class="status-badge available">Available</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isBooked)
                                            <button class="btn btn-success" disabled>BOOKED</button>
                                        @elseif($isDisabled)
                                            <button class="btn btn-disabled" disabled>QUOTA EMPTY</button>
                                        @else
                                            <form method="POST" action="{{ route('member.book.store') }}" style="display: inline;" id="book-form-{{ $s->id }}">
                                                @csrf
                                                <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                                <button type="button" class="btn btn-primary" 
                                                    onclick="showBookConfirm('{{ $s->classModel->class_name ?? 'Class' }}', '{{ $day }}', '{{ $s->schedule_date_formatted }}', '{{ \Carbon\Carbon::parse($s->class_time)->format('H:i') }}', '{{ $s->instructor ?? '-' }}', '{{ $s->id }}')">
                                                    BOOK NOW
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
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
        if (form) {
            // Disable button to prevent double-click
            const btn = document.getElementById('confirm-book-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Booking...';
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
            form.submit();
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

    // ===== SIDEBAR TOGGLE FUNCTION =====
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburger = document.getElementById('hamburger-btn');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
        
        if (sidebar.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
            hamburger.innerHTML = '<i class="fas fa-times text-lg"></i>';
        } else {
            document.body.style.overflow = '';
            hamburger.innerHTML = '<i class="fas fa-bars text-lg"></i>';
        }
    }

    // Close sidebar when clicking on a nav link
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('aside nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });
    });

    // Reset sidebar on window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburger = document.getElementById('hamburger-btn');
        
        if (window.innerWidth > 768) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            hamburger.innerHTML = '<i class="fas fa-bars text-lg"></i>';
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>