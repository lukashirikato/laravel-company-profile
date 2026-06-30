<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book Class | Studio</title>
    @vite('resources/css/app.css')
    
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Serif', Georgia, serif;
            background: #FCF9F2;
            color: #1C1C1C;
        }

        :root {
            --power-pink: #EE4E8B;
            --burnt-cherry: #7A2B4A;
            --soft-petals: #F4C9DF;
            --patina-green: #1A7A5E;
            --springs-ivy: #1D5A4B;
            --grounded-green: #C5D79B;
            --layl: #1C1C1C;
            --rising: #FCF9F2;
        }

        h1, h2, h3, .brand-label, .day-pill, .schedule-action, .schedule-status {
            font-family: 'Nord', 'Arial Narrow', sans-serif;
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

        .booking-card {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            border: 1.5px solid rgba(244, 201, 223, 0.95);
            background:
                radial-gradient(circle at 92% 0%, rgba(244, 201, 223, 0.44) 0 68px, transparent 69px),
                linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(252, 249, 242, 0.92));
            padding: 1rem;
            box-shadow: 0 16px 40px rgba(122, 43, 74, 0.08);
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .booking-card::after {
            content: "";
            position: absolute;
            right: -32px;
            top: -32px;
            width: 112px;
            height: 112px;
            border-radius: 999px;
            border: 22px solid rgba(238, 78, 139, 0.08);
            pointer-events: none;
        }

        .schedule-card:not(.is-booked):not(.is-disabled):hover .booking-card {
            transform: translateY(-3px);
            border-color: rgba(238, 78, 139, 0.85);
            box-shadow: 0 22px 48px rgba(122, 43, 74, 0.14);
        }

        .class-mark {
            position: relative;
            width: 3.25rem;
            height: 3.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: linear-gradient(145deg, #FFF7FB, #FCF9F2);
            border: 1px solid rgba(238, 78, 139, 0.22);
            color: var(--power-pink);
            box-shadow: inset 0 0 0 6px rgba(244, 201, 223, 0.18);
        }

        .class-mark i {
            font-size: 1.1rem;
            z-index: 1;
        }

        .class-mark::before,
        .class-mark::after {
            content: "";
            position: absolute;
            width: 0.72rem;
            height: 0.72rem;
            border-radius: 999px 999px 999px 0;
            background: rgba(26, 122, 94, 0.18);
            transform: rotate(-45deg);
        }

        .class-mark::before { left: 0.62rem; top: 0.62rem; }
        .class-mark::after { right: 0.62rem; bottom: 0.62rem; background: rgba(238, 78, 139, 0.18); }

        .schedule-card.is-booked .booking-card {
            background:
                linear-gradient(135deg, rgba(26, 122, 94, 0.08), rgba(197, 215, 155, 0.28)),
                rgba(252, 249, 242, 0.94);
            border-color: rgba(26, 122, 94, 0.42);
            box-shadow: inset 0 0 0 1px rgba(26, 122, 94, 0.10), 0 12px 30px rgba(26, 122, 94, 0.08);
        }

        .schedule-card.is-booked .class-mark {
            color: #1A7A5E;
            border-color: rgba(26, 122, 94, 0.28);
            background: linear-gradient(145deg, #F0F7E3, #FCF9F2);
        }

        .schedule-card.is-booked .booked-ribbon {
            display: inline-flex;
        }

        @media (max-width: 640px) {
            .main-content {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .main-content > .space-y-6 {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
            }

            .main-content > .space-y-6 > section {
                padding: 0.85rem !important;
                border-radius: 1.25rem !important;
                overflow: hidden;
            }

            .main-content > .space-y-6 > section > .flex:first-child {
                align-items: flex-start !important;
                flex-wrap: wrap;
                gap: 0.75rem !important;
            }

            .main-content > .space-y-6 > section > .flex:first-child > .flex {
                min-width: 0;
                flex: 1 1 auto;
                flex-wrap: wrap;
                row-gap: 0.45rem;
            }

            .main-content > .space-y-6 > section > .flex:first-child > span:last-child {
                flex: 0 0 100%;
                text-align: left;
                letter-spacing: 0.12em;
            }

            .schedule-card.is-booked .booking-card {
                padding-top: 1rem;
            }

            .booking-card {
                width: 100%;
                max-width: 100%;
                padding: 0.85rem;
                border-radius: 1.1rem;
                overflow: hidden;
            }

            .booking-card > .flex {
                gap: 0.6rem;
            }

            .class-mark {
                width: 2.55rem;
                height: 2.55rem;
                border-radius: 0.9rem;
            }

            .class-mark::before,
            .class-mark::after {
                width: 0.55rem;
                height: 0.55rem;
            }

            .class-mark::before { left: 0.5rem; top: 0.5rem; }
            .class-mark::after { right: 0.5rem; bottom: 0.5rem; }

            .booking-card h3 {
                display: -webkit-box;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: normal !important;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                max-width: 100%;
                font-size: 0.98rem !important;
                line-height: 1.15 !important;
                letter-spacing: 0.02em !important;
                overflow-wrap: anywhere;
            }

            .booking-card .min-w-0 {
                min-width: 0;
                max-width: 100%;
            }

            .booking-card > .flex > .min-w-0 {
                width: calc(100% - 3.15rem);
            }

            .booking-card p.mt-1.flex > span:not(.text-\[\#F4C9DF\]) {
                min-width: 0;
                overflow-wrap: anywhere;
            }

            .booking-card .flex-wrap.items-start.justify-between {
                display: block;
            }

            .schedule-card-status {
                width: fit-content;
                max-width: 100%;
                margin-top: 0.65rem;
                white-space: normal;
            }

            .booking-card p,
            .booking-card span {
                max-width: 100%;
            }

            .booking-card p.mt-1.flex {
                align-items: flex-start;
                gap: 0.35rem 0.5rem;
                font-size: 0.8rem !important;
                line-height: 1.35;
            }

            .booking-card .mt-4.flex {
                flex-wrap: wrap;
                align-items: center;
                gap: 0.45rem;
                margin-top: 0.9rem;
                letter-spacing: 0.11em;
                line-height: 1.35;
            }

            .schedule-action {
                flex: 1 1 9rem;
                min-width: 0;
                overflow-wrap: anywhere;
            }

            .schedule-card.is-booked .booked-ribbon {
                position: static;
                width: fit-content;
                margin: 0 0 0.75rem auto;
                padding: 0.35rem 0.7rem;
                font-size: 0.6rem;
                letter-spacing: 0.08em;
            }

            .schedule-card.is-booked .booking-card::after {
                top: -48px;
                right: -48px;
            }

            .schedule-card-status {
                margin-top: 0.65rem;
            }

            .schedule-card-check {
                right: 0.65rem !important;
                top: 0.65rem !important;
            }

            .day-pill {
                max-width: 100%;
                overflow-wrap: anywhere;
            }
        }

        @media (max-width: 380px) {
            .main-content {
                padding-left: 0.55rem;
                padding-right: 0.55rem;
            }

            .booking-card {
                padding: 0.75rem;
            }

            .class-mark {
                width: 2.25rem;
                height: 2.25rem;
                border-radius: 0.8rem;
            }

            .booking-card > .flex {
                gap: 0.5rem;
            }

            .booking-card > .flex > .min-w-0 {
                width: calc(100% - 2.75rem);
            }

            .booking-card h3 {
                font-size: 0.92rem !important;
                letter-spacing: 0 !important;
            }

            .booking-card p,
            .booking-card span {
                font-size: 0.76rem !important;
            }
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

        /* ------------------------------------------- RESPONSIVE SIDEBAR ------------------------------------------- */
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

    <main class="flex-1 p-6 md:p-10 overflow-y-auto bg-cream">
        
        {{-- MOBILE HAMBURGER --}}
        <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-5 md:p-7 mb-8 mt-14 md:mt-0">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-nord font-bold text-[30px] md:text-[32px] text-dark leading-tight">Book Your Class</h1>
                    <div class="flex items-baseline gap-1.5 mt-1.5">
                        <span class="font-poppins text-dark/45 text-[15px]">Assalamu'alaikum,</span>
                        <span class="font-poppins text-dark font-semibold text-[15px]">{{ $customer->name ?? 'Member' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center shadow-sm border border-[rgba(238,78,139,0.2)] text-secondary relative transition-all duration-200 hover:border-primary/40 hover:shadow-md cursor-pointer">
                        <i class="fas fa-bell text-sm"></i>
                        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-primary rounded-full border-2 border-white"></span>
                    </div>
                    @php $initial = strtoupper(substr($customer->name ?? 'M', 0, 1)); @endphp
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-nord font-bold text-sm shadow-md border-2 border-white flex-shrink-0">
                        {{ $initial }}
                    </div>
                </div>
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="bg-[rgba(26,122,94,0.08)] border border-[rgba(26,122,94,0.15)] rounded-xl p-4 mb-6 flex items-center gap-3 font-poppins text-[14px] text-accent font-medium">
                <i class="fas fa-check-circle text-accent"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-[rgba(238,78,139,0.06)] border border-[rgba(238,78,139,0.15)] rounded-xl p-4 mb-6 flex items-center gap-3 font-poppins text-[14px] text-secondary font-medium">
                <i class="fas fa-exclamation-circle text-secondary"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- CREDIT STATUS BAR --}}
        <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-4 md:p-5 mb-8">
            <div class="flex flex-wrap items-center gap-4 md:gap-8">
                @if($selectedPackage)
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[rgba(238,78,139,0.1)] flex items-center justify-center text-primary">
                        <i class="fas fa-box text-sm"></i>
                    </div>
                    <div>
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Package</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold">{{ $selectedPackage->name ?? '-' }}</p>
                    </div>
                </div>
                @endif
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[rgba(26,122,94,0.1)] flex items-center justify-center text-accent">
                        <i class="fas fa-coins text-sm"></i>
                    </div>
                    <div>
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Credit</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold" id="credit-value-top">{{ $remainingClasses ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[rgba(122,43,74,0.1)] flex items-center justify-center text-secondary">
                        <i class="fas fa-calendar text-sm"></i>
                    </div>
                    <div>
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Valid Until</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold">{{ $activeOrders?->first()?->expired_at ? \Carbon\Carbon::parse($activeOrders->first()->expired_at)->format('d M Y') : ($selectedPackage?->duration_days ? 'After 1st booking' : 'Unlimited') }}</p>
                    </div>
                </div>
                @if($activeOrders && $activeOrders->count() > 1)
                <div class="ml-auto">
                    <select id="package-select" onchange="switchPackage(this.value)" class="font-poppins text-[13px] bg-cream border border-[rgba(238,78,139,0.15)] rounded-xl px-4 py-2.5 text-dark font-medium focus:outline-none focus:border-primary focus:ring-2 focus:ring-[rgba(238,78,139,0.1)]">
                        @foreach($activeOrders as $ord)
                            <option value="{{ $ord->id }}" {{ $ord->id == $selectedOrderId ? 'selected' : '' }}>
                                {{ $ord->package?->name ?? 'Package' }} ({{ $ord->remaining_classes ?? 0 }} credits)
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        {{-- DATE SELECTOR --}}
        @php
            $today = \Carbon\Carbon::today();
            $weekStart = $today->copy()->startOfWeek();
            $weekDates = [];
            for ($i = 0; $i < 7; $i++) {
                $weekDates[] = $weekStart->copy()->addDays($i);
            }
            $selectedDate = request('date', $today->format('Y-m-d'));
        @endphp
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-nord font-semibold text-[17px] text-dark">SELECT DATE <span class="font-poppins font-normal text-dark/40 text-[15px]">� {{ $today->isoFormat('MMMM YYYY') }}</span></h2>
                <div class="flex items-center gap-2">
                    <button onclick="changeWeek(-1)" class="w-8 h-8 rounded-lg bg-white border border-[rgba(238,78,139,0.15)] flex items-center justify-center text-dark/50 hover:text-primary hover:border-primary transition-all">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button onclick="changeWeek(1)" class="w-8 h-8 rounded-lg bg-white border border-[rgba(238,78,139,0.15)] flex items-center justify-center text-dark/50 hover:text-primary hover:border-primary transition-all">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            <div id="week-dates" class="grid grid-cols-7 gap-2">
                @foreach($weekDates as $date)
                    @php
                        $isSelected = $date->format('Y-m-d') === $selectedDate;
                        $isToday = $date->isToday();
                    @endphp
                    <button onclick="selectDate('{{ $date->format('Y-m-d') }}')"
                        class="rounded-xl p-3 text-center transition-all duration-200 {{ $isSelected ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white border border-[rgba(238,78,139,0.1)] text-dark hover:border-primary/30 hover:shadow-sm' }}">
                        <p class="font-poppins text-[10px] font-semibold uppercase tracking-wider {{ $isSelected ? 'text-white/70' : 'text-dark/40' }}">{{ $date->isoFormat('dd') }}</p>
                        <p class="font-nord font-bold text-lg leading-tight mt-0.5">{{ $date->format('d') }}</p>
                        @if($isToday)
                            <p class="font-poppins text-[9px] mt-0.5 font-semibold {{ $isSelected ? 'text-white/70' : 'text-primary' }}">TODAY</p>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <button class="px-4 py-2 rounded-xl bg-primary text-white font-poppins font-medium text-[12px] shadow-sm hover:shadow-md transition-all">Class Type: ALL</button>
            <button class="px-4 py-2 rounded-xl bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 font-poppins font-medium text-[12px] hover:border-primary/30 hover:text-dark transition-all">Instructor</button>
            <button class="px-4 py-2 rounded-xl bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 font-poppins font-medium text-[12px] hover:border-primary/30 hover:text-dark transition-all">Time</button>
            <a href="{{ route('member.book') }}" class="ml-auto font-poppins text-[12px] text-primary hover:text-secondary font-medium transition-colors">RESET</a>
        </div>

        {{-- SCHEDULE GRID --}}
        @if($schedules && $schedules->count() > 0)
            @foreach($schedules as $day => $daySchedules)
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-5">
                        <h3 class="font-nord font-bold text-[18px] text-dark">{{ $day }}</h3>
                        <span class="font-poppins text-[12px] text-dark/35 bg-white px-3 py-1 rounded-full border border-[rgba(238,78,139,0.08)]">{{ $daySchedules->count() }} classes</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($daySchedules as $schedule)
                            @php
                                $isBooked = in_array($schedule->id, $bookedScheduleIds ?? []);
                                $capacity = $schedule->capacity ?? null;
                                $bookedCount = $schedule->booked_count ?? null;
                                $hasCapacity = !is_null($capacity) && !is_null($bookedCount);
                                $remaining = $hasCapacity ? $capacity - $bookedCount : null;
                                $isFull = $hasCapacity ? $remaining <= 0 : false;
                                $classIcon = 'fa-dumbbell';
                                $iconBg = 'from-primary to-secondary';
                                if ($schedule->classModel) {
                                    $name = strtolower($schedule->classModel->class_name ?? '');
                                    if (strpos($name, 'pilates') !== false) { $classIcon = 'fa-spa'; $iconBg = 'from-accent to-springs-ivy'; }
                                    elseif (strpos($name, 'muaythai') !== false || strpos($name, 'boxing') !== false) { $classIcon = 'fa-fist-raised'; $iconBg = 'from-secondary to-primary'; }
                                    elseif (strpos($name, 'yoga') !== false) { $classIcon = 'fa-peace'; $iconBg = 'from-accent to-patina-green'; }
                                }
                            @endphp

                            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 schedule-card {{ $isBooked ? 'is-booked opacity-60' : '' }} {{ $isFull ? 'opacity-50' : '' }} {{ (!$isBooked && !$isFull) ? 'cursor-pointer' : 'cursor-default' }}"
                                 data-schedule-id="{{ $schedule->id }}"
                                 data-class-name="{{ $schedule->className ?? 'Class' }}"
                                 data-day="{{ $day }}"
                                 data-date="{{ $schedule->schedule_date ?? '' }}"
                                 data-time="{{ $schedule->class_time ?? '' }}"
                                 data-instructor="{{ $schedule->instructor ?? 'Instructor' }}"
                                 onclick="{{ $isBooked || $isFull ? '' : "toggleCard(this)" }}">
                                <div class="p-4 pb-3">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $iconBg }} flex items-center justify-center text-white shadow-sm">
                                            <i class="fas {{ $classIcon }} text-sm"></i>
                                        </div>
                                        <span class="font-poppins text-[10px] font-semibold uppercase tracking-wider {{ $isFull ? 'text-secondary' : 'text-accent' }} bg-[rgba(26,122,94,0.06)] px-2.5 py-1 rounded-full border border-[rgba(26,122,94,0.1)]">
                                            CAPACITY {{ $hasCapacity ? "$bookedCount/$capacity" : '-/-' }}
                                        </span>
                                    </div>
                                    <h4 class="font-nord font-bold text-[16px] text-dark leading-tight mb-0.5">{{ $schedule->className ?? 'Class' }}</h4>
                                    <p class="font-poppins text-[13px] text-dark/50 flex items-center gap-1.5">
                                        <i class="fas fa-user text-[10px]"></i>
                                        {{ $schedule->instructor ?? 'Instructor' }}
                                    </p>
                                </div>
                                <div class="border-t border-[rgba(238,78,139,0.06)] px-4 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 font-poppins text-[12px] text-dark/50">
                                        <i class="fas fa-clock text-primary text-[10px]"></i>
                                        <span>{{ $schedule->class_time ? \Carbon\Carbon::parse($schedule->class_time)->format('H:i') : '--:--' }}</span>
                                    </div>
                                    @if($isBooked)
                                        <span class="font-poppins text-[11px] font-semibold text-accent bg-[rgba(26,122,94,0.08)] px-3 py-1.5 rounded-full">
                                            <i class="fas fa-check mr-1"></i>Booked
                                        </span>
                                    @elseif($isFull)
                                        <span class="font-poppins text-[11px] font-semibold text-dark/30 bg-[rgba(28,28,28,0.05)] px-3 py-1.5 rounded-full">
                                            Full
                                        </span>
                                    @else
                                        <span class="font-poppins text-[11px] font-semibold text-primary bg-[rgba(238,78,139,0.08)] px-3 py-1.5 rounded-full schedule-card-accent">
                                            <i class="fas fa-plus-circle mr-1"></i>Select
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-[rgba(238,78,139,0.08)] flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-2xl text-secondary"></i>
                </div>
                <h3 class="font-nord font-bold text-[20px] text-dark mb-1">No Classes Available</h3>
                <p class="font-poppins text-dark/45 text-[14px]">There are no classes scheduled for this period. Check back later.</p>
            </div>
        @endif

        {{-- PROMO BANNER --}}
        <div class="mt-10 rounded-2xl overflow-hidden relative shadow-[0_4px_20px_rgba(122,43,74,0.08)]"
             style="background: linear-gradient(135deg, #1C1C1C 0%, #7A2B4A 100%); min-height: 180px;">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: url('https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=800&q=80'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#1C1C1C]/90 via-[#1C1C1C]/60 to-transparent"></div>
            <div class="relative z-10 p-6 md:p-8 flex items-center h-full">
                <div class="max-w-md">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white/80 text-[10px] font-bold uppercase tracking-[0.2em] mb-3 border border-white/20">
                        MEMBER EXCLUSIVE
                    </span>
                    <h3 class="font-nord font-bold text-[22px] text-white leading-tight mb-2">Elevate your wellness journey.</h3>
                    <p class="font-poppins text-white/70 text-[13px] leading-relaxed">Unlock premium classes, priority booking, and exclusive perks with our Diamond membership.</p>
                    <a href="{{ route('member.packages.index') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-xl bg-white text-secondary font-poppins font-semibold text-[13px] hover:bg-white/90 transition-all shadow-lg">
                        Learn More <i class="fas fa-arrow-right text-[11px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="h-8"></div>

    </main>
    {{-- End of main --}}

    {{-- FLOATING BOOKING BAR --}}
    <div id="bulk-booking-bar">
        <div class="bulk-bar-inner">
            <button onclick="clearAllSelections()" class="bulk-bar-close" aria-label="Clear selection">
                <i class="fas fa-times"></i>
            </button>
            <div class="bulk-bar-info">
                <span class="bulk-bar-label">Selected: <span id="selected-class-count" class="bulk-bar-count">0</span></span>
                <span class="bulk-bar-sep">|</span>
                <span class="bulk-bar-credits"><span id="credit-display">{{ $remainingClasses ?? 0 }}</span> credits</span>
            </div>
            <button id="book-selected-btn" class="bulk-bar-book-btn" onclick="bookSelectedClasses()">
                <i class="fas fa-calendar-check"></i>
                Book Now
            </button>
        </div>
    </div>
    {{-- End of floating booking bar --}}

</div>
{{-- End of flex wrapper --}}

{{-- ============================================
     BULK BOOKING CONFIRMATION MODAL
     ============================================ --}}
<div id="bulk-confirm-modal" style="display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:white; border-radius:16px; max-width:540px; width:100%; max-height:90vh; box-shadow:0 25px 60px rgba(0,0,0,0.2); animation:modalIn 0.25s ease-out; overflow:hidden; display:flex; flex-direction:column;">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, rgba(241,204,227,0.30) 0%, rgba(244,238,230,1) 100%); padding:1.5rem 2rem; border-bottom:1px solid rgba(238, 78, 139,0.20); text-align:center; flex-shrink:0;">
            <div style="width:56px; height:56px; background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.5rem; color:white;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 style="font-size:1.15rem; font-weight:700; color:#7A2B4A; margin:0;">Konfirmasi Booking</h3>
            <p style="font-size:0.825rem; color:#64748b; margin-top:4px;">Anda akan booking <span id="bulk-confirm-count">0</span> kelas sekaligus</p>
        </div>

        {{-- Class List (Scrollable) --}}
        <div style="flex:1; overflow-y:auto; padding:1.5rem 2rem;">
            <div style="font-size:0.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">Daftar Kelas:</div>
            <div id="bulk-confirm-list" style="display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1.25rem;">
                {{-- Will be populated by JavaScript --}}
            </div>

            {{-- Warning --}}
            <div style="background:#fef3c7; border-left:3px solid #f59e0b; border-radius:0 8px 8px 0; padding:0.75rem 1rem; font-size:0.8rem; color:#92400e; display:flex; gap:8px; align-items:flex-start;">
                <i class="fas fa-exclamation-triangle" style="margin-top:2px; flex-shrink:0;"></i>
                <span>Booking ini akan mengurangi kuota kelas Anda sesuai jumlah kelas yang dipilih. Pastikan semua jadwal sudah benar.</span>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="padding:1.5rem 2rem; border-top:1px solid #e2e8f0; flex-shrink:0; display:flex; gap:0.75rem;">
            <button onclick="closeBulkConfirm()" 
                style="flex:1; padding:0.8rem; border:2px solid #e2e8f0; background:white; color:#64748b; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; transition:all 0.2s; font-family:inherit;"
                onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'" 
                onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                <i class="fas fa-times" style="margin-right:6px;"></i>Batal
            </button>
            <button id="confirm-bulk-book-btn" onclick="confirmBulkBooking()" 
                style="flex:1; padding:0.8rem; border:none; background:linear-gradient(135deg,#7A2B4A,#EE4E8B); color:white; border-radius:10px; font-weight:600; font-size:0.875rem; cursor:pointer; transition:all 0.2s; font-family:inherit; box-shadow:0 4px 12px rgba(122, 43, 74,0.30);"
                onmouseover="this.style.background='linear-gradient(135deg,#5A1F3A,#B83863)'" 
                onmouseout="this.style.background='linear-gradient(135deg,#7A2B4A,#EE4E8B)'">
                <i class="fas fa-check" style="margin-right:6px;"></i>Ya, Book Semua
            </button>
        </div>
    </div>
</div>

    <style>
        @keyframes modalIn {
            0% { opacity: 0; transform: scale(0.95) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .schedule-card { cursor: pointer; }
        .schedule-card.is-booked { cursor: default; }
        .schedule-card.opacity-50 { cursor: default; }

        .schedule-card.is-selected {
            border-color: #EE4E8B !important;
            background: rgba(238, 78, 139, 0.03) !important;
            box-shadow: 0 4px 24px rgba(238, 78, 139, 0.18) !important;
            transform: translateY(-4px);
        }

        .schedule-card.is-selected .schedule-card-accent {
            display: none !important;
        }

        .schedule-card.is-selected .schedule-card-check {
            display: inline-flex !important;
        }

        .schedule-card:hover:not(.is-booked):not(.opacity-50):not(.is-selected) {
            border-color: rgba(238, 78, 139, 0.3) !important;
        }

        /* ===== FLOATING BOOKING BAR ===== */
        #bulk-booking-bar {
            position: fixed;
            left: calc(13.5rem + (100vw - 13.5rem) / 2);
            transform: translateX(-50%);
            bottom: 24px;
            z-index: 9999;
            width: calc(100% - 32px);
            max-width: 620px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        }
        #bulk-booking-bar.visible {
            opacity: 1;
            visibility: visible;
        }
        .bulk-bar-inner {
            position: relative;
            background: rgba(28,28,28,0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: white;
            padding: 1rem 1.25rem;
            border-radius: 20px;
            box-shadow: 0 12px 48px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.06);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .bulk-bar-close {
            position: absolute;
            top: 0.5rem;
            right: 0.75rem;
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            font-size: 14px;
            padding: 0.25rem;
            transition: color 0.2s;
            line-height: 1;
        }
        .bulk-bar-close:hover {
            color: rgba(255,255,255,0.7);
        }
        .bulk-bar-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 0 0 auto;
        }
        .bulk-bar-label {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-bar-count {
            font-weight: 700;
            color: #EE4E8B;
            font-size: 18px;
        }
        .bulk-bar-sep {
            color: rgba(255,255,255,0.15);
        }
        .bulk-bar-credits {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-bar-book-btn {
            background: #EE4E8B;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(238,78,139,0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
            opacity: 0.5;
            pointer-events: none;
        }
        .bulk-bar-book-btn:not(:disabled) {
            opacity: 1;
            pointer-events: auto;
        }
        .bulk-bar-book-btn:hover:not(:disabled) {
            box-shadow: 0 6px 24px rgba(238,78,139,0.45);
            transform: translateY(-1px);
        }
        .bulk-bar-book-btn:disabled {
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            #bulk-booking-bar {
                left: 50%;
                width: calc(100% - 24px);
            }
            .bulk-bar-info {
                flex: 0 0 100%;
                justify-content: center;
            }
            .bulk-bar-inner {
                padding: 0.85rem 1rem;
                border-radius: 16px;
            }
            .bulk-bar-book-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>

<script>
    const maxSelectableClasses = {{ (int) ($remainingClasses ?? $customer->quota ?? 0) }};
    const bookingStoreUrl = @json(route('member.book.store'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function getSelectedScheduleCheckboxes() {
        return Array.from(document.querySelectorAll('.schedule-card.is-selected'));
    }

    function toggleCard(card) {
        card.classList.toggle('is-selected');
        updateBulkBookingBar();
    }

    function showCreditLimitAlert() {
        const popup = document.createElement('div');
        popup.style.cssText = 'position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; padding:1rem; animation:modalIn 0.25s ease-out;';
        popup.innerHTML = `
            <div style="background:white; border-radius:20px; max-width:400px; width:100%; padding:2.5rem 2rem; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.2);">
                <div style="width:72px; height:72px; border-radius:50%; background:rgba(238,78,139,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size:2rem; color:#EE4E8B;"></i>
                </div>
                <h3 style="font-size:1.25rem; font-weight:700; color:#7A2B4A; margin:0 0 0.5rem; font-family:Nord,'Poppins',sans-serif;">Credit Tidak Mencukupi</h3>
                <p style="font-size:0.9rem; color:#64748b; margin:0 0 1.5rem; line-height:1.5;">Credit aktif kamu hanya <strong>${maxSelectableClasses}</strong>. Silakan pilih maksimal ${maxSelectableClasses} kelas atau beli package tambahan untuk booking lebih banyak kelas.</p>
                <button onclick="this.closest('div[style]').remove()"
                   style="padding:0.85rem 2.5rem; border-radius:12px; font-weight:700; font-size:0.9rem; border:none; background:linear-gradient(135deg, #EE4E8B, #7A2B4A); color:white; cursor:pointer; box-shadow:0 4px 16px rgba(238,78,139,0.3); font-family:'Poppins',sans-serif;">
                    <i class="fas fa-check mr-2"></i>Mengerti
                </button>
            </div>
        `;
        document.body.appendChild(popup);
        popup.addEventListener('click', function(e) {
            if (e.target === this) this.remove();
        });
    }

    function updateBulkBookingBar() {
        const selected = getSelectedScheduleCheckboxes();
        const count = selected.length;
        const bar = document.getElementById('bulk-booking-bar');
        const selectedClassCount = document.getElementById('selected-class-count');
        const button = document.getElementById('book-selected-btn');
        const creditTop = document.getElementById('credit-value-top');
        const creditDisplay = document.getElementById('credit-display');

        if (selectedClassCount) selectedClassCount.textContent = count;
        if (creditTop) creditTop.textContent = maxSelectableClasses - count;
        if (creditDisplay) creditDisplay.textContent = maxSelectableClasses - count;

        if (bar) {
            if (count > 0) {
                bar.classList.add('visible');
            } else {
                bar.classList.remove('visible');
            }
        }

        if (button) {
            button.disabled = count === 0 || count > maxSelectableClasses;
        }
    }

    function clearAllSelections() {
        document.querySelectorAll('.schedule-card.is-selected').forEach(card => {
            card.classList.remove('is-selected');
        });
        updateBulkBookingBar();
    }

    function switchPackage(orderId) {
        window.location.href = '{{ route("member.book") }}?order_id=' + orderId;
    }

    function changeWeek(direction) {
        // Placeholder for week navigation
    }

    function selectDate(dateStr) {
        window.location.href = '{{ route("member.book") }}?date=' + dateStr;
    }

    function bookSingleSchedule(scheduleId) {
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('schedule_id', scheduleId);

        const button = event?.target?.closest('button');
        if (button) { button.disabled = true; button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }

        fetch(bookingStoreUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Booking failed');
                if (button) { button.disabled = false; button.innerHTML = 'BOOK NOW'; }
            }
        })
        .catch(err => {
            alert('Error booking class');
            if (button) { button.disabled = false; button.innerHTML = 'BOOK NOW'; }
        });
    }

    function bookSelectedClasses() {
        const selected = getSelectedScheduleCheckboxes();
        if (!selected.length) return;

        if (selected.length > maxSelectableClasses) {
            showCreditLimitAlert();
            return;
        }
        
        showBulkBookingConfirm(selected);
    }
    
    function showBulkBookingConfirm(selectedCheckboxes) {
        let classListHtml = '';
        selectedCheckboxes.forEach((checkbox, index) => {
            const className = checkbox.dataset.className;
            const day = checkbox.dataset.day;
            const date = checkbox.dataset.date;
            const time = checkbox.dataset.time;
            
            classListHtml += `
                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:${index % 2 === 0 ? '#f8fafc' : 'white'}; border-radius:8px;">
                    <div style="width:32px; height:32px; background:#EE4E8B; color:white; border-radius:6px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.75rem; flex-shrink:0;">
                        ${index + 1}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:700; color:#7A2B4A; font-size:0.85rem; margin-bottom:2px;">${className}</div>
                        <div style="font-size:0.75rem; color:#64748b;">${day}, ${date} � ${time}</div>
                    </div>
                    <i class="fas fa-check-circle" style="color:#10b981; font-size:1.25rem;"></i>
                </div>
            `;
        });
        
        // Update modal content
        document.getElementById('bulk-confirm-list').innerHTML = classListHtml;
        document.getElementById('bulk-confirm-count').textContent = selectedCheckboxes.length;
        
        // Show modal
        const modal = document.getElementById('bulk-confirm-modal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeBulkConfirm() {
        const modal = document.getElementById('bulk-confirm-modal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    async function confirmBulkBooking() {
        const selected = getSelectedScheduleCheckboxes();
        if (!selected.length) return;

        const button = document.getElementById('confirm-bulk-book-btn');
        const originalHtml = button?.innerHTML || '';
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Booking...';
            button.style.opacity = '0.7';
        }

        try {
            for (const checkbox of selected) {
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('schedule_id', checkbox.dataset.scheduleId);

                const res = await fetch(bookingStoreUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/plain, */*'
                    },
                    body: formData,
                });

                if (!res.ok) {
                    const errData = await res.json().catch(() => null);
                    throw new Error(errData?.message || 'Booking gagal');
                }
            }

            closeBulkConfirm();
            showSuccessPopup(selected.length);
        } catch (error) {
            console.error('Bulk booking failed', error);
            alert(error.message || 'Booking gagal. Silakan coba lagi.');
            if (button) {
                button.disabled = false;
                button.innerHTML = originalHtml;
                button.style.opacity = '1';
            }
        }
    }

    function showSuccessPopup(count) {
        const popup = document.createElement('div');
        popup.style.cssText = 'position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; padding:1rem; animation:modalIn 0.25s ease-out;';
        popup.innerHTML = `
            <div style="background:white; border-radius:20px; max-width:400px; width:100%; padding:2.5rem 2rem; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.2);">
                <div style="width:72px; height:72px; border-radius:50%; background:rgba(26,122,94,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <i class="fas fa-check-circle" style="font-size:2rem; color:#1A7A5E;"></i>
                </div>
                <h3 style="font-size:1.25rem; font-weight:700; color:#1C1C1C; margin:0 0 0.5rem; font-family:Nord,'Poppins',sans-serif;">Booking Berhasil!</h3>
                <p style="font-size:0.9rem; color:#64748b; margin:0 0 1.5rem; line-height:1.5;">${count} class telah berhasil di booking. Silakan cek jadwal kamu di My Classes.</p>
                <button onclick="this.closest('div[style]').remove(); window.location.reload();"
                   style="padding:0.85rem 2.5rem; border-radius:12px; font-weight:700; font-size:0.9rem; border:none; background:linear-gradient(135deg, #EE4E8B, #7A2B4A); color:white; cursor:pointer; box-shadow:0 4px 16px rgba(238,78,139,0.3); font-family:'Poppins',sans-serif;">
                    <i class="fas fa-arrow-right mr-2"></i>Lihat My Classes
                </button>
            </div>
        `;
        document.body.appendChild(popup);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateBulkBookingBar();
        
        // Backup: Event delegation for schedule cards
        document.body.addEventListener('click', function(e) {
            const card = e.target.closest('.schedule-card');
            if (card && !card.classList.contains('is-booked') && !card.classList.contains('opacity-50')) {
                if (!card.hasAttribute('onclick') || card.getAttribute('onclick') === '') {
                    e.preventDefault();
                    toggleCard(card);
                }
            }
        });
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
