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

            /* Horizontal Overflow & Layout Adjustments */
            body {
                overflow-x: hidden !important;
            }
            .flex.h-screen {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            main.flex-1 {
                max-width: 100vw !important;
                overflow-x: hidden !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Card Package Info & Credit Status Bar */
            .credit-status-card {
                padding: 1rem !important;
                box-sizing: border-box;
                width: 100% !important;
            }
            .credit-status-card .flex-wrap {
                gap: 0.75rem !important;
            }
            .credit-status-card .flex.items-center.gap-3 {
                width: 100% !important;
                min-width: 0;
            }
            .credit-status-card .flex.items-center.gap-3 > div:last-child {
                min-width: 0;
                flex: 1;
            }
            .credit-status-card p {
                white-space: normal !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            .credit-status-card p.font-semibold {
                font-size: 13px !important;
                line-height: 1.3 !important;
            }
            #package-select {
                max-width: 100% !important;
                width: 100% !important;
                font-size: 12px !important;
                box-sizing: border-box;
            }
            .credit-status-card .ml-auto {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Date Picker (SELECT DATE) */
            .date-selector-container > .flex.items-center.justify-between {
                padding-right: 12px !important;
            }
            #week-dates {
                display: flex !important;
                grid-template-columns: none !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory !important;
                gap: 8px !important;
                padding-bottom: 6px !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none; /* Hide scrollbar for Firefox */
                width: 100% !important;
                box-sizing: border-box;
            }
            #week-dates::-webkit-scrollbar {
                display: none; /* Hide scrollbar for Chrome/Safari */
            }
            #week-dates button {
                flex: 0 0 54px !important;
                width: 54px !important;
                min-width: 54px !important;
                padding: 8px 4px !important;
                scroll-snap-align: start !important;
                box-sizing: border-box;
            }
            #week-dates button p {
                font-size: 10px !important;
            }
            #week-dates button p.font-nord {
                font-size: 16px !important;
            }
            #week-dates button .today-label {
                font-size: 9px !important;
                white-space: nowrap !important;
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
        <div class="credit-status-card bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-4 md:p-5 mb-8">
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
        <div class="date-selector-container mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-nord font-semibold text-[17px] text-dark">SELECT DATE <span id="selected-date-label" class="font-poppins font-normal text-dark/40 text-[15px]">&mdash; {{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</span></h2>
                <div class="flex items-center gap-2">
                    <button onclick="changeWeek(-1)" class="w-8 h-8 rounded-lg bg-white border border-[rgba(238,78,139,0.15)] flex items-center justify-center text-dark/50 hover:text-primary hover:border-primary transition-all">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button onclick="changeWeek(1)" class="w-8 h-8 rounded-lg bg-white border border-[rgba(238,78,139,0.15)] flex items-center justify-center text-dark/50 hover:text-primary hover:border-primary transition-all">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            <div id="week-dates" class="grid grid-cols-7 gap-2"></div>
        </div>

        {{-- FILTER BAR --}}
        @php
            $currentClassType = $filters['class_type'] ?? '';
            $currentInstructor = $filters['instructor'] ?? '';
            $currentTime = $filters['time'] ?? '';
        @endphp
        <div class="flex flex-wrap items-center gap-3 mb-8">
            {{-- CLASS TYPE FILTER --}}
            <div class="relative filter-dropdown" data-filter="class_type">
                <button onclick="toggleFilterDropdown(this)"
                    class="px-4 py-2 rounded-xl font-poppins font-medium text-[12px] shadow-sm hover:shadow-md transition-all {{ $currentClassType ? 'bg-primary text-white' : 'bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 hover:border-primary/30 hover:text-dark' }}">
                    Class Type: <span class="font-semibold filter-label">{{ $currentClassType ?: 'ALL' }}</span>
                    <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-48 bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="class_type">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentClassType ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        All Classes
                    </button>
                    @foreach($filterOptions['class_types'] as $type)
                        <button data-value="{{ $type }}"
                            class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ $currentClassType === $type ? 'text-primary font-semibold' : 'text-dark/60' }}">
                            {{ $type }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- INSTRUCTOR FILTER --}}
            <div class="relative filter-dropdown" data-filter="instructor">
                <button onclick="toggleFilterDropdown(this)"
                    class="px-4 py-2 rounded-xl font-poppins font-medium text-[12px] shadow-sm hover:shadow-md transition-all {{ $currentInstructor ? 'bg-primary text-white' : 'bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 hover:border-primary/30 hover:text-dark' }}">
                    Instructor: <span class="font-semibold filter-label">{{ $currentInstructor ?: 'ALL' }}</span>
                    <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-48 bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="instructor">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentInstructor ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        All Instructors
                    </button>
                    @foreach($filterOptions['instructors'] as $instructor)
                        <button data-value="{{ $instructor }}"
                            class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ $currentInstructor === $instructor ? 'text-primary font-semibold' : 'text-dark/60' }}">
                            {{ $instructor }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- TIME FILTER --}}
            <div class="relative filter-dropdown" data-filter="time">
                <button onclick="toggleFilterDropdown(this)"
                    class="px-4 py-2 rounded-xl font-poppins font-medium text-[12px] shadow-sm hover:shadow-md transition-all {{ $currentTime ? 'bg-primary text-white' : 'bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 hover:border-primary/30 hover:text-dark' }}">
                    Time: <span class="font-semibold filter-label">{{ $currentTime ? ucfirst($currentTime) : 'ALL' }}</span>
                    <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="time">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentTime ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        All Times
                    </button>
                    @foreach($filterOptions['time_slots'] as $key => $label)
                        <button data-value="{{ $key }}"
                            class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ $currentTime === $key ? 'text-primary font-semibold' : 'text-dark/60' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button onclick="resetAllFilters()" class="ml-auto font-poppins text-[12px] text-primary hover:text-secondary font-medium transition-colors">RESET</button>
        </div>

        {{-- SCHEDULE GRID -- JS RENDERED --}}
        <div id="schedule-grid">
            <div id="schedule-loading" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-4 animate-pulse"><div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div><div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div><div class="h-3 bg-gray-200 rounded w-2/3"></div></div>
                    <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-4 animate-pulse"><div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div><div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div><div class="h-3 bg-gray-200 rounded w-2/3"></div></div>
                    <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-4 animate-pulse"><div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div><div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div><div class="h-3 bg-gray-200 rounded w-2/3"></div></div>
                </div>
            </div>
            <div id="schedule-content" class="transition-opacity duration-300 opacity-100">
                {{-- JS writes filtered schedule cards here --}}
            </div>
        </div>

        {{-- PROMO BANNER --}}
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

    function bookSelectedClasses() {
        const selected = getSelectedScheduleCheckboxes();
        if (selected.length === 0) return;

        const modal = document.getElementById('bulk-confirm-modal');
        const countEl = document.getElementById('bulk-confirm-count');
        const listEl = document.getElementById('bulk-confirm-list');

        if (countEl) countEl.textContent = selected.length;

        if (listEl) {
            listEl.innerHTML = '';
            selected.forEach(function(card) {
                const name = card.dataset.className || 'Class';
                const time = card.dataset.time || '';
                const instructor = card.dataset.instructor || 'Instructor';
                const item = document.createElement('div');
                item.style.cssText = 'display:flex; align-items:center; gap:0.75rem; padding:0.65rem 0.85rem; background:#f8fafc; border-radius:10px; font-size:0.85rem;';
                item.innerHTML = `
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#EE4E8B,#7A2B4A);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                        <i class="fas fa-calendar-alt" style="font-size:14px;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;color:#1C1C1C;font-size:0.85rem;">${name}</div>
                        <div style="font-size:0.75rem;color:#94a3b8;margin-top:2px;">${time} &middot; ${instructor}</div>
                    </div>
                `;
                listEl.appendChild(item);
            });
        }

        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function confirmBulkBooking() {
        const selected = getSelectedScheduleCheckboxes();
        if (selected.length === 0) return;

        const btn = document.getElementById('confirm-bulk-book-btn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Processing...'; }

        let completed = 0;
        let errors = [];

        selected.forEach(function(card) {
            const scheduleId = card.dataset.scheduleId;
            if (!scheduleId) { completed++; return; }

            fetch(bookingStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ schedule_id: scheduleId })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data.success) {
                    errors.push(data.message || 'Gagal booking');
                }
            })
            .catch(function() {
                errors.push('Network error');
            })
            .finally(function() {
                completed++;
                if (completed === selected.length) {
                    finishBulkBooking(errors);
                }
            });
        });
    }

    function finishBulkBooking(errors) {
        const modal = document.getElementById('bulk-confirm-modal');
        const btn = document.getElementById('confirm-bulk-book-btn');

        if (modal) { modal.style.display = 'none'; document.body.style.overflow = ''; }
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-check" style="margin-right:6px;"></i>Ya, Book Semua'; }

        if (errors.length === 0) {
            showBookingToast('success', 'Semua kelas berhasil dibooking!');
            clearAllSelections();
            setTimeout(function() { window.location.reload(); }, 1500);
        } else {
            const msg = errors.length + ' dari ' + (getSelectedScheduleCheckboxes().length + errors.length) + ' booking gagal: ' + errors.join(', ');
            showBookingToast('error', msg);
            setTimeout(function() { window.location.reload(); }, 3000);
        }
    }

    function showBookingToast(type, message) {
        const existing = document.querySelector('.booking-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'booking-toast';
        const isSuccess = type === 'success';
        toast.style.cssText = 'position:fixed;top:24px;left:50%;transform:translateX(-50%);z-index:99999;padding:1rem 1.75rem;border-radius:14px;font-family:Poppins,sans-serif;font-size:0.875rem;font-weight:500;box-shadow:0 12px 48px rgba(0,0,0,0.18);display:flex;align-items:center;gap:10px;animation:modalIn 0.3s ease-out;max-width:90vw;' +
            (isSuccess ? 'background:#1A7A5E;color:white;' : 'background:#DC2626;color:white;');
        toast.innerHTML = '<i class="fas ' + (isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 4000);
        toast.addEventListener('click', function() { toast.remove(); });
    }

    function closeBulkConfirm() {
        const modal = document.getElementById('bulk-confirm-modal');
        if (modal) { modal.style.display = 'none'; document.body.style.overflow = ''; }
    }

    function switchPackage(orderId) {
        window.location.href = '{{ route("member.book") }}?order_id=' + orderId;
    }

    // ============================================
    // CLIENT-SIDE FILTERING & STATE
    // ============================================
    const allSchedules = @json($schedulesJson);
    const dayOrder = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const todayStr = getTodayStr();

    let selectedDate = '{{ $selectedDate }}';
    let currentWeekStart = getMonday(new Date(selectedDate));
    let activeFilters = {
        class_type: '',
        instructor: '',
        time: ''
    };

    function getMonday(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        d.setDate(diff);
        d.setHours(0, 0, 0, 0);
        return d;
    }

    function formatYMD(date) {
        return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
    }

    function getScheduleDate(schedule, weekStart) {
        if (schedule.scheduleDate) {
            return schedule.scheduleDate;
        }
        const date = new Date(weekStart);
        date.setDate(weekStart.getDate() + schedule.dayIdx);
        return formatYMD(date);
    }

    function formatTime12(timeStr) {
        if (!timeStr) return '--:--';
        const parts = timeStr.split(':');
        const h = parts[0].padStart(2, '0');
        const m = parts[1];
        return h + ':' + m;
    }

    function renderWeekPills() {
        const container = document.getElementById('week-dates');
        if (!container) return;
        const dayAbbr = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        let html = '';
        for (let i = 0; i < 7; i++) {
            const date = new Date(currentWeekStart);
            date.setDate(currentWeekStart.getDate() + i);
            const dateStr = formatYMD(date);
            const dayName = dayAbbr[date.getDay()];
            const dayNum = date.getDate();
            const isToday = dateStr === todayStr;
            const isSelected = dateStr === selectedDate;

            html += `
                <button onclick="selectDate('${dateStr}')"
                    class="rounded-xl p-3 text-center transition-all duration-200 ${isSelected ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white border border-[rgba(238,78,139,0.1)] text-dark hover:border-primary/30 hover:shadow-sm'}">
                    <p class="font-poppins text-[10px] font-semibold uppercase tracking-wider ${isSelected ? 'text-white/70' : 'text-dark/40'}">${dayName.slice(0, 3)}</p>
                    <p class="font-nord font-bold text-lg leading-tight mt-0.5">${dayNum}</p>
                    ${isToday ? '<p class="font-poppins text-[9px] mt-0.5 font-semibold today-label ' + (isSelected ? 'text-white/70' : 'text-primary') + '">TODAY</p>' : ''}
                </button>
            `;
        }
        container.innerHTML = html;
    }

    function renderScheduleCards(schedules) {
        const content = document.getElementById('schedule-content');
        if (!content) return;

        if (schedules.length === 0) {
            content.innerHTML = `
                <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-[rgba(238,78,139,0.08)] flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl text-secondary"></i>
                    </div>
                    <h3 class="font-nord font-bold text-[20px] text-dark mb-1">Belum Ada Kelas</h3>
                    <p class="font-poppins text-dark/45 text-[14px]">Belum ada kelas terjadwal di tanggal ini</p>
                </div>
            `;
            return;
        }

        const d = new Date(selectedDate + 'T12:00:00');
        const dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const heading = dayNames[d.getDay()] + ', ' + d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
        function fmtDisplayDate(dStr) { if (!dStr) return '-'; const p = dStr.split('-'); return parseInt(p[2]) + ' ' + monthNames[parseInt(p[1])-1] + ' ' + p[0]; }

        let cardsHtml = '';
        schedules.forEach(function(s) {
            const isDisabled = s.isBooked || s.isFull;
            const capLabel = s.hasCapacity ? s.bookedCount + '/' + s.capacity : '-/-';
            const statusHtml = s.isBooked
                ? '<span class="font-poppins text-[11px] font-semibold text-accent bg-[rgba(26,122,94,0.08)] px-3.5 py-1.5 rounded-full flex items-center gap-1.5"><i class="fas fa-check-circle text-[12px]"></i>Booked</span>'
                : s.isFull
                ? '<span class="font-poppins text-[11px] font-semibold text-dark/30 bg-[rgba(28,28,28,0.05)] px-3.5 py-1.5 rounded-full flex items-center gap-1.5"><i class="fas fa-ban text-[12px]"></i>Full</span>'
                : '<span class="font-poppins text-[11px] font-semibold text-primary bg-[rgba(238,78,139,0.08)] px-3.5 py-1.5 rounded-full schedule-card-accent flex items-center gap-1.5"><i class="fas fa-plus-circle text-[12px]"></i>Select</span>';

            cardsHtml += `
                <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 schedule-card ${s.isBooked ? 'is-booked opacity-60' : ''} ${s.isFull ? 'opacity-50' : ''} ${!isDisabled ? 'cursor-pointer' : 'cursor-default'}"
                      data-schedule-id="${s.id}"
                      data-class-name="${s.className}"
                      data-day="${s.day}"
                      data-date="${getScheduleDate(s, currentWeekStart)}"
                      data-time="${s.classTime || ''}"
                      data-instructor="${s.instructor || ''}"
                      onclick="${isDisabled ? '' : "toggleCard(this)"}">
                    <div class="p-4">
                        <div class="flex items-start gap-3.5 mb-3.5">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md flex-shrink-0 overflow-hidden" style="background: ${s.iconGradient};">
                                <i class="fas ${s.classIcon} text-base"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <h4 class="font-nord font-bold text-[17px] text-dark leading-tight truncate">${s.className}</h4>
                                        ${s.level ? '<span class="font-poppins text-[11px] text-dark/35 font-medium">' + s.level + '</span>' : ''}
                                    </div>
                                    <span class="font-poppins text-[10px] font-semibold uppercase tracking-wider whitespace-nowrap ${s.isFull ? 'text-secondary' : 'text-accent'} bg-[rgba(26,122,94,0.06)] px-2.5 py-1 rounded-full border border-[rgba(26,122,94,0.1)] flex-shrink-0">${capLabel}</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2.5 mb-3.5">
                            <div class="flex items-center gap-2.5 font-poppins text-[13px] text-dark/55">
                                <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 overflow-hidden text-[#E91E63]" style="background: #FCE4EC;">
                                    <i class="fas fa-calendar-alt text-[11px]"></i>
                                </div>
                                <span>${fmtDisplayDate(getScheduleDate(s, currentWeekStart))}</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-poppins text-[13px] text-dark/55">
                                <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 overflow-hidden text-[#E91E63]" style="background: #FCE4EC;">
                                    <i class="fas fa-clock text-[11px]"></i>
                                </div>
                                <span>${formatTime12(s.classTime)}</span>
                            </div>
                            <div class="flex items-center gap-2.5 font-poppins text-[13px] text-dark/55">
                                <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 overflow-hidden text-[#E91E63]" style="background: #FCE4EC;">
                                    <i class="fas fa-user text-[11px]"></i>
                                </div>
                                <span>${s.instructor || 'Instructor'}</span>
                            </div>
                        </div>
                        <div class="border-t border-[rgba(238,78,139,0.06)] pt-3 flex items-center justify-between">${statusHtml}</div>
                    </div>
                </div>
            `;
        });

        content.innerHTML = `
            <div class="mb-6 day-section">
                <div class="flex items-center gap-3 mb-0 py-3 px-4 rounded-xl">
                    <div class="flex-1 flex items-center gap-3">
                        <h3 id="schedule-heading" class="font-nord font-bold text-[18px] text-dark">${heading}</h3>
                        <span id="schedule-count" class="font-poppins text-[12px] text-dark/35 bg-white px-3 py-1 rounded-full border border-[rgba(238,78,139,0.08)]">${schedules.length} classes</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4">
                    ${cardsHtml}
                </div>
            </div>
        `;
    }

    function applyFilters() {
        const loading = document.getElementById('schedule-loading');
        const content = document.getElementById('schedule-content');
        if (loading) loading.classList.remove('hidden');
        if (content) content.style.opacity = '0';

        setTimeout(function() {
            let filtered = allSchedules.filter(function(s) {
                const sDate = getScheduleDate(s, currentWeekStart);
                return sDate === selectedDate;
            });

            if (activeFilters.class_type) {
                filtered = filtered.filter(function(s) {
                    return s.classModelName === activeFilters.class_type;
                });
            }
            if (activeFilters.instructor) {
                filtered = filtered.filter(function(s) {
                    return s.instructor === activeFilters.instructor;
                });
            }
            if (activeFilters.time) {
                filtered = filtered.filter(function(s) {
                    const t = s.classTime || '';
                    switch (activeFilters.time) {
                        case 'morning': return t >= '00:00' && t < '12:00';
                        case 'afternoon': return t >= '12:00' && t < '17:00';
                        case 'evening': return t >= '17:00' && t <= '23:59';
                        default: return true;
                    }
                });
            }

            filtered.sort(function(a, b) {
                return (a.classTime || '').localeCompare(b.classTime || '');
            });

            const label = document.getElementById('selected-date-label');
            if (label) {
                const d = new Date(selectedDate + 'T12:00:00');
                const dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                label.textContent = '\u2014 ' + dayNames[d.getDay()] + ', ' + d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
            }

            if (loading) loading.classList.add('hidden');
            renderScheduleCards(filtered);
            if (content) content.style.opacity = '1';
            updateBulkBookingBar();
        }, 200);
    }

    function renderAll() {
        renderWeekPills();
        applyFilters();
    }

    function changeWeek(direction) {
        const jsDay = new Date(selectedDate + 'T12:00:00').getDay();
        const dayIdx = jsDay === 0 ? 6 : jsDay - 1;
        currentWeekStart.setDate(currentWeekStart.getDate() + direction * 7);
        const newDate = new Date(currentWeekStart);
        newDate.setDate(currentWeekStart.getDate() + dayIdx);
        selectedDate = formatYMD(newDate);
        renderAll();
    }

    function getTodayStr() {
        const d = new Date();
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;
        renderAll();
    }

    function applyFilter(key, value) {
        activeFilters[key] = value;
        applyFilters();
        updateFilterButtonUI(key, value);
    }

    function resetAllFilters() {
        selectedDate = todayStr;
        currentWeekStart = getMonday(new Date(selectedDate));
        activeFilters = { class_type: '', instructor: '', time: '' };
        renderAll();
        resetFilterButtonUI();
    }

    function updateFilterButtonUI(key, value) {
        const dropdown = document.querySelector('.filter-dropdown[data-filter="' + key + '"]');
        if (!dropdown) return;
        const button = dropdown.querySelector('button');
        const label = dropdown.querySelector('.filter-label');
        if (button) {
            if (value) {
                button.classList.remove('bg-white', 'border', 'border-[rgba(238,78,139,0.15)]', 'text-dark/60', 'hover:border-primary/30', 'hover:text-dark');
                button.classList.add('bg-primary', 'text-white');
            } else {
                button.classList.remove('bg-primary', 'text-white');
                button.classList.add('bg-white', 'border', 'border-[rgba(238,78,139,0.15)]', 'text-dark/60', 'hover:border-primary/30', 'hover:text-dark');
            }
        }
        if (label) {
            if (key === 'time') {
                label.textContent = value ? value.charAt(0).toUpperCase() + value.slice(1) : 'ALL';
            } else {
                label.textContent = value ? value : 'ALL';
            }
        }
        // Update dropdown option highlights
        dropdown.querySelectorAll('[data-filter-options] button').forEach(function(opt) {
            const optVal = opt.dataset.value || '';
            if (optVal === value) {
                opt.classList.add('text-primary', 'font-semibold');
                opt.classList.remove('text-dark/60');
            } else {
                opt.classList.remove('text-primary', 'font-semibold');
                opt.classList.add('text-dark/60');
            }
        });
    }

    function resetFilterButtonUI() {
        document.querySelectorAll('.filter-dropdown').forEach(function(dropdown) {
            const key = dropdown.dataset.filter;
            const button = dropdown.querySelector('button');
            const label = dropdown.querySelector('.filter-label');
            if (button) {
                button.classList.remove('bg-primary', 'text-white');
                button.classList.add('bg-white', 'border', 'border-[rgba(238,78,139,0.15)]', 'text-dark/60', 'hover:border-primary/30', 'hover:text-dark');
            }
            if (label) label.textContent = key === 'time' ? 'ALL' : 'ALL';
            dropdown.querySelectorAll('[data-filter-options] button').forEach(function(opt) {
                if (!opt.dataset.value) {
                    opt.classList.add('text-primary', 'font-semibold');
                    opt.classList.remove('text-dark/60');
                } else {
                    opt.classList.remove('text-primary', 'font-semibold');
                    opt.classList.add('text-dark/60');
                }
            });
        });
    }

    function toggleFilterDropdown(button) {
        const dropdown = button.nextElementSibling;
        const isHidden = dropdown.classList.contains('hidden');
        document.querySelectorAll('.filter-dropdown > div:not(.hidden)').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
        });
        dropdown.classList.toggle('hidden', !isHidden);
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-dropdown')) {
            document.querySelectorAll('.filter-dropdown > div').forEach(d => {
                d.classList.add('hidden');
            });
        }
    });

    // Filter dropdown option clicks via event delegation
    document.addEventListener('click', function(e) {
        const option = e.target.closest('[data-filter-options] button[data-value]');
        if (option) {
            const parent = option.closest('[data-filter-options]');
            const filterKey = parent.closest('.filter-dropdown').dataset.filter;
            const value = option.dataset.value;
            applyFilter(filterKey, value);
            // Close dropdown
            const dropdown = parent.closest('.filter-dropdown > div');
            if (dropdown) dropdown.classList.add('hidden');
        }
    });

    // Close confirmation modal on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('bulk-confirm-modal');
        if (modal && modal.style.display === 'flex' && e.target === modal) {
            closeBulkConfirm();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        updateBulkBookingBar();
        renderAll();

        // Backup: Event delegation for schedule cards (for JS-rendered cards)
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
