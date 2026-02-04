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
            background: #f5f7fa;
            color: #2d3748;
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
            color: #1e40af;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb a:hover {
            color: #1e3a8a;
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
            color: #1e3a8a;
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
            border-left: 4px solid #1e40af;
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
            background: #eff6ff;
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
            color: #1e3a8a;
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
            color: #1e3a8a;
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
            border-color: #1e40af;
        }
        
        .package-selector select:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
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
            background: #1e40af;
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
            background: #f8fafc;
        }
        
        .schedule-table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .schedule-table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .schedule-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .schedule-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .class-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .class-icon {
            width: 40px;
            height: 40px;
            background: #eff6ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .class-name {
            font-weight: 600;
            color: #1e3a8a;
            font-size: 0.95rem;
        }
        
        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border-radius: 6px;
            font-weight: 600;
            color: #1e3a8a;
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
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-badge.booked {
            background: #d1fae5;
            color: #047857;
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
            background: #1e40af;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e3a8a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-success {
            background: #10b981;
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

    {{-- ============================================
         MAIN CONTENT AREA
         ============================================ --}}
    <main class="flex-1 overflow-y-auto">
        
        <div class="main-content">
        
            {{-- ============================================
                 HEADER WITH USER INFO
                 ============================================ --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    Book Your Class
                </h1>
                <p class="text-sm text-gray-500">
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
                            <div class="status-label">Classes Remaining</div>
                            <div class="status-value">{{ $customer->quota }}</div>
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
                                            <form method="POST" action="{{ route('member.book.store') }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                                <button type="submit" class="btn btn-primary">BOOK NOW</button>
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

</body>
</html>