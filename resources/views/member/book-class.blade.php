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

        h1, h2, h3 {
            font-family: 'Nord', 'Arial Narrow', sans-serif;
        }

        /* ============================================
           MASTHEAD (konsisten dgn halaman member lain)
           ============================================ */
        .bc-masthead {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
            padding: .25rem 0 1.05rem;
            position: relative;
            margin-bottom: 1.75rem;
            animation: bcIn .45s ease both;
        }

        .bc-masthead::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(122, 43, 74, .25), rgba(238, 78, 139, .12), transparent 70%);
        }

        .bc-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #7A2B4A;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .bc-eyebrow i { color: #EE4E8B; font-size: .85rem; }

        .bc-masthead h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: #7A2B4A;
            letter-spacing: -0.01em;
            line-height: 1.2;
            margin-top: .35rem;
        }

        .bc-masthead .bc-sub {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 4px;
        }

        .bc-today-chip {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(122, 43, 74, .16);
            background: rgba(255, 255, 255, .85);
            box-shadow: 0 8px 20px rgba(122, 43, 74, .06);
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
        }

        .bc-today-dot {
            width: .6rem;
            height: .6rem;
            border-radius: 999px;
            background: #EE4E8B;
            box-shadow: 0 0 0 4px rgba(238, 78, 139, .15);
        }

        .bc-today-label { color: rgba(28, 28, 28, .45); font-size: .75rem; }
        .bc-today-value { color: #1C1C1C; font-weight: 600; font-size: .78rem; }

        /* ============================================
           SIGNATURE: KARTU PITA MINGGUAN
           ============================================ */
        .bc-week-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(238, 78, 139, .1);
            box-shadow: 0 2px 12px rgba(122, 43, 74, .06);
            padding: 1.25rem 1.35rem 1.35rem;
            margin-bottom: 1.75rem;
            animation: bcIn .5s ease .08s both;
        }

        .bc-week-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .bc-sec-label {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #7A2B4A;
        }

        .bc-week-date {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(28, 28, 28, .5);
            margin-top: 2px;
        }

        .bc-week-nav { display: flex; align-items: center; gap: .5rem; }

        /* --- Pemicu kalender --- */
        .bc-date-trigger {
            display: flex;
            align-items: center;
            gap: .6rem;
            background: transparent;
            border: 0;
            padding: 0;
            cursor: pointer;
            text-align: left;
            border-radius: 10px;
        }

        .bc-date-trigger .bc-sec-label,
        .bc-date-trigger .bc-week-date { display: block; }

        .bc-date-trigger:hover .bc-week-date { color: #EE4E8B; }

        .bc-date-trigger-icon {
            font-size: .7rem;
            color: #EE4E8B;
            flex-shrink: 0;
            transition: transform .2s ease;
        }

        .bc-date-trigger[aria-expanded="true"] .bc-date-trigger-icon { transform: rotate(180deg); }

        /* --- Popup kalender --- */
        .bc-date-popup {
            position: absolute;
            top: calc(100% + .6rem);
            left: 0;
            z-index: 60;
            width: min(288px, calc(100vw - 3rem));
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .12);
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(122, 43, 74, .18);
            padding: 1rem;
            animation: bcIn .18s ease both;
        }

        .bc-cal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .75rem;
        }

        .bc-cal-head button {
            width: 2rem;
            height: 2rem;
            border-radius: .6rem;
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(28, 28, 28, .5);
            cursor: pointer;
            transition: color .2s ease, border-color .2s ease, background .2s ease;
        }

        .bc-cal-head button:hover { color: #EE4E8B; border-color: #EE4E8B; background: #FFF7FB; }

        .bc-cal-month {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: #7A2B4A;
        }

        .bc-cal-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: .35rem;
        }

        .bc-cal-weekdays span {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: rgba(28, 28, 28, .35);
            text-align: center;
        }

        .bc-cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .bc-cal-day {
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: #1C1C1C;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background .15s ease, color .15s ease, border-color .15s ease;
        }

        .bc-cal-day.is-muted { color: rgba(28, 28, 28, .2); pointer-events: none; }

        button.bc-cal-day:hover { background: #FFF7FB; border-color: rgba(238, 78, 139, .35); color: #7A2B4A; }

        .bc-cal-day.is-today { border-color: rgba(238, 78, 139, .4); color: #EE4E8B; font-weight: 700; }

        .bc-cal-day.is-selected {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            color: #fff;
            border-color: transparent;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(238, 78, 139, .3);
        }

        .bc-cal-foot {
            display: flex;
            justify-content: center;
            margin-top: .6rem;
            padding-top: .6rem;
            border-top: 1px solid rgba(238, 78, 139, .08);
        }

        .bc-cal-today-btn {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #EE4E8B;
            background: rgba(238, 78, 139, .08);
            border: 0;
            border-radius: 999px;
            padding: .35rem .9rem;
            cursor: pointer;
            transition: background .2s ease;
        }

        .bc-cal-today-btn:hover { background: rgba(238, 78, 139, .16); }

        .bc-week-nav button {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: .75rem;
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(28, 28, 28, .5);
            cursor: pointer;
            transition: color .2s ease, border-color .2s ease, background .2s ease;
        }

        .bc-week-nav button:hover {
            color: #EE4E8B;
            border-color: #EE4E8B;
            background: #FFF7FB;
        }

        /* Pita tanggal — dapat digeser (drag / swipe) */
        #week-dates {
            display: flex;
            gap: .5rem;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-snap-type: x proximity;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            cursor: grab;
            padding-bottom: .35rem;
            max-width: 100%;
        }

        #week-dates::-webkit-scrollbar { display: none; }

        #week-dates.is-dragging {
            cursor: grabbing;
            scroll-snap-type: none;
            user-select: none;
        }

        /* Pill hari — override utilitas Tailwind pada pill hasil render JS */
        #week-dates button {
            flex: 0 0 62px;
            width: 62px;
            min-width: 62px;
            border-radius: 14px;
            border: 1px solid rgba(238, 78, 139, .12);
            scroll-snap-align: start;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        #week-dates button:not(.bg-primary):hover {
            border-color: rgba(238, 78, 139, .45);
            box-shadow: 0 6px 16px rgba(122, 43, 74, .08);
            transform: translateY(-1px);
        }

        #week-dates button.bg-primary {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%) !important;
            border-color: transparent !important;
            box-shadow: 0 10px 22px rgba(238, 78, 139, .3) !important;
        }

        /* ============================================
           DAFTAR PAKET (PIL) — tanpa dropdown
           ============================================ */
        .package-strip {
            display: flex;
            gap: .5rem;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            cursor: grab;
            padding-bottom: .25rem;
            max-width: 100%;
        }

        .package-strip::-webkit-scrollbar { display: none; }

        .package-strip.is-dragging {
            cursor: grabbing;
            user-select: none;
        }

        .package-pill {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
            min-width: 200px;
            max-width: 240px;
            padding: .55rem .9rem;
            border-radius: 12px;
            background: #FCF9F2;
            border: 1px solid rgba(238, 78, 139, .15);
            text-align: left;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
        }

        .package-pill:hover {
            border-color: rgba(238, 78, 139, .45);
            box-shadow: 0 6px 16px rgba(122, 43, 74, .08);
            transform: translateY(-1px);
        }

        .package-pill.is-active {
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            border-color: transparent;
            box-shadow: 0 10px 22px rgba(238, 78, 139, .28);
        }

        .package-pill-name {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.25;
            color: #1C1C1C;
            white-space: normal;
        }

        .package-pill-credit {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 500;
            color: rgba(28, 28, 28, .45);
        }

        .package-pill.is-active .package-pill-name { color: #fff; }
        .package-pill.is-active .package-pill-credit { color: rgba(255, 255, 255, .75); }

        @keyframes bcIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

        @media (prefers-reduced-motion: reduce) {
            .bc-masthead,
            .bc-week-card,
            #week-dates button { animation: none; transition: none; }
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

        @media (max-width: 640px) {
            .class-name-row {
                flex-wrap: wrap;
            }

            .class-name-line {
                white-space: normal !important;
                overflow-wrap: anywhere;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
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
            #package-strip {
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box;
            }
            .package-pill {
                max-width: 200px;
            }
            .credit-status-card .ml-auto {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Masthead & pita mingguan (mobile) */
            .bc-masthead { align-items: flex-start; flex-direction: column; gap: .85rem; }
            .bc-masthead h1 { font-size: 1.4rem !important; }
            .bc-masthead .bc-sub { font-size: 13px; }
            .bc-week-card { padding: .95rem 1rem 1.1rem; }

            /* Date Picker (SELECT DATE) */
            #week-dates {
                gap: 8px;
                width: 100%;
                box-sizing: border-box;
            }
            #week-dates button {
                flex: 0 0 54px;
                width: 54px;
                min-width: 54px;
                padding: 8px 4px;
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

    <main class="flex-1 p-6 md:p-10 pb-24 md:pb-28 overflow-y-auto bg-cream">
        
        {{-- MASTHEAD --}}
        <header class="bc-masthead mt-14 md:mt-0">
            <div>
                <p class="bc-eyebrow"><i class="fas fa-calendar-plus"></i> Member Area &middot; Booking</p>
                <h1 class="font-nord">Booking Kelas</h1>
                <p class="bc-sub">Pilih tanggal di minggu ini, lalu booking satu atau beberapa slot sekaligus.</p>
            </div>
            <div class="bc-today-chip">
                <span class="bc-today-dot"></span>
                <span class="bc-today-label">Hari ini</span>
                <span class="bc-today-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
            </div>
        </header>

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
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Paket</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold">{{ $selectedPackage->name ?? '-' }}</p>
                    </div>
                </div>
                @endif
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[rgba(26,122,94,0.1)] flex items-center justify-center text-accent">
                        <i class="fas fa-coins text-sm"></i>
                    </div>
                    <div>
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Kredit</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold" id="credit-value-top">{{ $remainingClasses ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[rgba(122,43,74,0.1)] flex items-center justify-center text-secondary">
                        <i class="fas fa-calendar text-sm"></i>
                    </div>
                    <div>
                        <p class="font-poppins text-[11px] text-dark/40 uppercase tracking-wider font-medium">Berlaku Hingga</p>
                        <p class="font-poppins text-[14px] text-dark font-semibold">{{ $activeOrders?->first()?->expired_at ? \Carbon\Carbon::parse($activeOrders->first()->expired_at)->format('d M Y') : ($selectedPackage?->duration_days ? 'Setelah booking pertama' : 'Tak Terbatas') }}</p>
                    </div>
                </div>
                @if($activeOrders)
                <div class="ml-auto md:min-w-[300px] md:max-w-[380px]">
                    <p class="font-poppins text-[10px] text-dark/40 uppercase tracking-wider font-medium mb-1.5">Pilih Paket</p>
                    <div id="package-strip" class="package-strip">
                        @foreach($activeOrders as $ord)
                            @php $isActiveOrder = $ord->id == $selectedOrderId; @endphp
                            <button type="button"
                                onclick="switchPackage({{ $ord->id }})"
                                class="package-pill {{ $isActiveOrder ? 'is-active' : '' }}"
                                aria-pressed="{{ $isActiveOrder ? 'true' : 'false' }}">
                                <span class="package-pill-name">{{ $ord->package?->name ?? 'Package' }}</span>
                                <span class="package-pill-credit">{{ $ord->remaining_classes ?? 0 }} kredit</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- DATE SELECTOR: KARTU PITA MINGGUAN --}}
        <section class="bc-week-card">
            <div class="bc-week-head">
                <div class="relative">
                    <button type="button" id="date-trigger" class="bc-date-trigger" onclick="toggleDatePicker(event)" aria-haspopup="dialog" aria-expanded="false">
                        <span class="min-w-0">
                            <span class="bc-sec-label">Pilih Tanggal</span>
                            <span id="selected-date-label" class="bc-week-date">&mdash; {{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</span>
                        </span>
                        <i class="fas fa-chevron-down bc-date-trigger-icon"></i>
                    </button>

                    {{-- POPUP KALENDER --}}
                    <div id="date-picker-popup" class="bc-date-popup hidden" role="dialog" aria-label="Pilih tanggal">
                        <div class="bc-cal-head">
                            <button type="button" onclick="calendarShiftMonth(-1)" aria-label="Bulan sebelumnya">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <p id="calendar-month-label" class="bc-cal-month">&mdash;</p>
                            <button type="button" onclick="calendarShiftMonth(1)" aria-label="Bulan berikutnya">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                        <div class="bc-cal-weekdays">
                            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                        </div>
                        <div id="calendar-grid" class="bc-cal-grid"></div>
                        <div class="bc-cal-foot">
                            <button type="button" class="bc-cal-today-btn" onclick="calendarGoToday()">Hari Ini</button>
                        </div>
                    </div>
                </div>
                <div class="bc-week-nav">
                    <button type="button" onclick="changeWeek(-1)" aria-label="Minggu sebelumnya">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button type="button" onclick="changeWeek(1)" aria-label="Minggu berikutnya">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            <div id="week-dates"></div>
        </section>

        {{-- FILTER BAR --}}
        @php
            $currentClassType = $filters['class_type'] ?? '';
            $currentInstructor = $filters['instructor'] ?? '';
            $currentTime = $filters['time'] ?? '';
        @endphp
        <div class="flex flex-wrap items-center gap-3 mb-8">
            {{-- CLASS TYPE FILTER --}}
            <div class="relative filter-dropdown flex-1 min-w-[180px]" data-filter="class_type">
                <button onclick="toggleFilterDropdown(this)"
                    class="w-full flex items-center justify-between gap-2 px-4 py-2 rounded-xl font-poppins font-medium text-[12px] shadow-sm hover:shadow-md transition-all {{ $currentClassType ? 'bg-primary text-white' : 'bg-white border border-[rgba(238,78,139,0.15)] text-dark/60 hover:border-primary/30 hover:text-dark' }}">
                    <span>Tipe Kelas: <span class="font-semibold filter-label">{{ $currentClassType ?: 'SEMUA' }}</span></span>
                    <i class="fas fa-chevron-down ml-auto text-[10px] flex-shrink-0"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-full min-w-[200px] bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="class_type">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentClassType ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        Semua Kelas
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
                    Instruktur: <span class="font-semibold filter-label">{{ $currentInstructor ?: 'SEMUA' }}</span>
                    <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-48 bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="instructor">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentInstructor ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        Semua Instruktur
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
                    Waktu: <span class="font-semibold filter-label">{{ $currentTime ? ucfirst($currentTime) : 'SEMUA' }}</span>
                    <i class="fas fa-chevron-down ml-1.5 text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-[rgba(238,78,139,0.1)] py-1.5 z-50 hidden" data-filter-options="time">
                    <button data-value="" class="w-full text-left px-4 py-2 font-poppins text-[12px] hover:bg-[rgba(238,78,139,0.05)] transition-colors {{ !$currentTime ? 'text-primary font-semibold' : 'text-dark/60' }}">
                        Semua Waktu
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
            <div id="no-schedule-message" class="hidden bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-[rgba(238,78,139,0.08)] flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-2xl text-secondary"></i>
                </div>
                <h3 class="font-nord font-bold text-[20px] text-dark mb-1">Tidak Ada Kelas</h3>
                <p class="font-poppins text-dark/45 text-[14px]">Belum ada kelas terjadwal di tanggal ini. Cek tanggal lain di minggu ini.</p>
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
                <span class="bulk-bar-label">Dipilih: <span id="selected-class-count" class="bulk-bar-count">0</span></span>
                <span class="bulk-bar-sep">|</span>
                <span class="bulk-bar-credits"><span id="credit-display">{{ $remainingClasses ?? 0 }}</span> kredit</span>
            </div>
            <button id="book-selected-btn" class="bulk-bar-book-btn" onclick="bookSelectedClasses()">
                <i class="fas fa-calendar-check"></i>
                Booking Sekarang
            </button>
        </div>
    </div>
    {{-- End of floating booking bar --}}

</div>
{{-- End of flex wrapper --}}

{{-- ============================================
     BULK BOOKING CONFIRMATION MODAL
     ============================================ --}}
<div id="bulk-confirm-modal" class="bulk-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="bulk-modal-title">
    <div class="bulk-modal-container" role="document">

        {{-- Header --}}
        <div class="bulk-modal-header">
            <div class="bulk-modal-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 id="bulk-modal-title" class="bulk-modal-title">Konfirmasi Booking</h3>
            <p class="bulk-modal-subtitle">Anda akan booking <span id="bulk-confirm-count">0</span> kelas sekaligus</p>
        </div>

        {{-- Body --}}
        <div class="bulk-modal-body">
            <div class="bulk-modal-section-label">Daftar Kelas</div>
            <div id="bulk-confirm-list" class="bulk-modal-class-list">
                {{-- Populated by JavaScript --}}
            </div>

            {{-- Warning --}}
            <div class="bulk-modal-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Booking ini akan mengurangi kuota kelas Anda sesuai jumlah kelas yang dipilih. Pastikan semua jadwal sudah benar.</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bulk-modal-footer">
            <button onclick="closeBulkConfirm()" class="bulk-btn bulk-btn-secondary">
                <i class="fas fa-times"></i> Batal
            </button>
            <button id="confirm-bulk-book-btn" onclick="confirmBulkBooking()" class="bulk-btn bulk-btn-primary">
                <i class="fas fa-check"></i> Ya, Book Semua
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
            left: 50%;
            transform: translateX(-50%) translateY(16px);
            bottom: 24px;
            z-index: 9999;
            width: calc(100% - 32px);
            max-width: 760px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
        }
        #bulk-booking-bar.visible {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        @media (min-width: 1024px) {
            #bulk-booking-bar {
                left: calc(13.5rem + (100vw - 13.5rem) / 2);
                transform: translateX(-50%) translateY(16px);
                max-width: 720px;
            }
            #bulk-booking-bar.visible {
                transform: translateX(-50%) translateY(0);
            }
        }
        .bulk-bar-inner {
            position: relative;
            background: rgba(28,28,28,0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 20px;
            box-shadow: 0 12px 48px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .bulk-bar-close {
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            font-size: 14px;
            padding: 0.25rem 0.5rem;
            transition: color 0.2s;
            line-height: 1;
            flex-shrink: 0;
        }
        .bulk-bar-close:hover {
            color: rgba(255,255,255,0.7);
        }
        .bulk-bar-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 0;
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
            flex-shrink: 0;
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
                bottom: max(16px, env(safe-area-inset-bottom, 16px));
                width: calc(100% - 16px);
                max-width: 100%;
            }
            .bulk-bar-inner {
                padding: 0.75rem 1rem;
                border-radius: 16px;
                flex-wrap: wrap;
            }
            .bulk-bar-info {
                flex: 0 0 auto;
                justify-content: flex-start;
            }
            .bulk-bar-book-btn {
                flex: 1;
                justify-content: center;
                min-width: 0;
            }
        }

        /* ===== BULK CONFIRM MODAL ===== */
        .bulk-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: bulkFadeIn 0.2s ease;
        }
        .bulk-modal-container {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 560px;
            max-height: 85vh;
            box-shadow: 0 32px 80px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            animation: bulkScaleIn 0.2s ease;
            margin-bottom: 80px;
        }
        .bulk-modal-header {
            background: linear-gradient(135deg, rgba(241,204,227,0.30) 0%, rgba(244,238,230,1) 100%);
            padding: 1.5rem 2rem 1.25rem;
            border-bottom: 1px solid rgba(238,78,139,0.20);
            text-align: center;
            flex-shrink: 0;
        }
        .bulk-modal-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.5rem;
            color: white;
        }
        .bulk-modal-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #7A2B4A;
            margin: 0;
            font-family: 'Nord', 'Poppins', sans-serif;
        }
        .bulk-modal-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            margin-top: 4px;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 2rem;
            -webkit-overflow-scrolling: touch;
        }
        .bulk-modal-section-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-modal-class-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .bulk-modal-class-list > div {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-class-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #EE4E8B, #7A2B4A);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            font-size: 14px;
        }
        .bulk-class-info {
            flex: 1;
            min-width: 0;
        }
        .bulk-class-name {
            font-weight: 600;
            color: #1C1C1C;
            font-size: 0.85rem;
        }
        .bulk-class-meta {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 2px;
        }
        .bulk-modal-warning {
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            color: #92400e;
            display: flex;
            gap: 8px;
            align-items: flex-start;
            font-family: 'Poppins', sans-serif;
        }
        .bulk-modal-warning i {
            margin-top: 2px;
            flex-shrink: 0;
        }
        .bulk-modal-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
            display: flex;
            gap: 0.75rem;
        }
        .bulk-btn {
            flex: 1;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .bulk-btn-secondary {
            border: 2px solid #e2e8f0;
            background: white;
            color: #64748b;
        }
        .bulk-btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .bulk-btn-primary {
            border: none;
            background: linear-gradient(135deg, #7A2B4A, #EE4E8B);
            color: white;
            box-shadow: 0 4px 12px rgba(122,43,74,0.30);
        }
        .bulk-btn-primary:hover {
            background: linear-gradient(135deg, #5A1F3A, #B83863);
            box-shadow: 0 6px 20px rgba(122,43,74,0.40);
        }
        .bulk-btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @keyframes bulkFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes bulkScaleIn {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        @media (max-width: 768px) {
            .bulk-modal-container {
                border-radius: 20px;
                max-height: 90vh;
                margin-bottom: 72px;
            }
            .bulk-modal-header {
                padding: 1.25rem 1.25rem 1rem;
            }
            .bulk-modal-body {
                padding: 1.25rem;
            }
            .bulk-modal-footer {
                padding: 1rem 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .bulk-modal-container {
                border-radius: 16px;
                max-height: 92vh;
                margin-bottom: 64px;
            }
            .bulk-modal-header {
                padding: 1rem 1rem 0.85rem;
            }
            .bulk-modal-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
            .bulk-modal-title {
                font-size: 1rem;
            }
            .bulk-modal-body {
                padding: 1rem;
            }
            .bulk-modal-footer {
                padding: 0.85rem 1rem;
                flex-direction: column;
            }
            .bulk-btn {
                padding: 0.7rem;
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
                item.innerHTML = `
                    <div class="bulk-class-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="bulk-class-info">
                        <div class="bulk-class-name">${name}</div>
                        <div class="bulk-class-meta">${time} &middot; ${instructor}</div>
                    </div>
                `;
                listEl.appendChild(item);
            });
        }

        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            trapFocus(modal);
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

    function trapFocus(element) {
        const focusable = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        function handler(e) {
            if (e.key !== 'Tab') return;
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
        element.addEventListener('keydown', handler);
        if (first) setTimeout(function() { first.focus(); }, 50);
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('bulk-confirm-modal');
            if (modal && modal.style.display === 'flex') closeBulkConfirm();
        }
    });

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

    const WEEK_PILL_DAYS = 21;

    function renderWeekPills() {
        const container = document.getElementById('week-dates');
        if (!container) return;
        const dayAbbr = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        let html = '';
        for (let i = 0; i < WEEK_PILL_DAYS; i++) {
            const date = new Date(currentWeekStart);
            date.setDate(currentWeekStart.getDate() + i);
            const dateStr = formatYMD(date);
            const dayName = dayAbbr[date.getDay()];
            const dayNum = date.getDate();
            const isToday = dateStr === todayStr;
            const isSelected = dateStr === selectedDate;

            html += `
                <button type="button" data-date="${dateStr}" onclick="selectDate('${dateStr}')"
                    class="rounded-xl p-3 text-center transition-all duration-200 ${isSelected ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white border border-[rgba(238,78,139,0.1)] text-dark hover:border-primary/30 hover:shadow-sm'}">
                    <p class="font-poppins text-[10px] font-semibold uppercase tracking-wider ${isSelected ? 'text-white/70' : 'text-dark/40'}">${dayName.slice(0, 3)}</p>
                    <p class="font-nord font-bold text-lg leading-tight mt-0.5">${dayNum}</p>
                    ${isToday ? '<p class="font-poppins text-[9px] mt-0.5 font-semibold today-label ' + (isSelected ? 'text-white/70' : 'text-primary') + '">HARI INI</p>' : ''}
                </button>
            `;
        }
        container.innerHTML = html;

        // Geser otomatis agar tanggal terpilih terlihat di tengah
        const activeBtn = container.querySelector('button[data-date="' + selectedDate + '"]');
        if (activeBtn) {
            const cRect = container.getBoundingClientRect();
            const bRect = activeBtn.getBoundingClientRect();
            const target = container.scrollLeft + (bRect.left - cRect.left) - (container.clientWidth - bRect.width) / 2;
            container.scrollTo({ left: Math.max(0, target), behavior: 'smooth' });
        }
    }

    // Drag-to-scroll generik (mouse); sentuhan pakai scroll native
    function initDragScroll(el) {
        if (!el || el.dataset.dragReady === '1') return;
        el.dataset.dragReady = '1';

        let down = false, moved = false, startX = 0, startScroll = 0, pointerId = null;

        el.addEventListener('pointerdown', function(e) {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            down = true;
            moved = false;
            startX = e.clientX;
            startScroll = el.scrollLeft;
            el.classList.add('is-dragging');
        });

        el.addEventListener('pointermove', function(e) {
            if (!down) return;
            const dx = e.clientX - startX;
            if (!moved && Math.abs(dx) > 6) {
                moved = true;
                pointerId = e.pointerId;
                try { el.setPointerCapture(pointerId); } catch (err) {}
            }
            if (moved) el.scrollLeft = startScroll - dx;
        });

        function endDrag() {
            if (!down) return;
            down = false;
            el.classList.remove('is-dragging');
            if (pointerId !== null) {
                try { el.releasePointerCapture(pointerId); } catch (err) {}
                pointerId = null;
            }
        }

        el.addEventListener('pointerup', endDrag);
        el.addEventListener('pointercancel', endDrag);

        el.addEventListener('click', function(e) {
            if (moved) {
                e.preventDefault();
                e.stopPropagation();
                moved = false;
            }
        }, true);
    }

    initDragScroll(document.getElementById('week-dates'));
    initDragScroll(document.getElementById('package-strip'));

    function renderScheduleCards(schedules) {
        const content = document.getElementById('schedule-content');
        if (!content) return;

        const noScheduleMessage = document.getElementById('no-schedule-message');

        if (schedules.length === 0) {
            if (noScheduleMessage) {
                noScheduleMessage.classList.remove('hidden');
            }
            content.innerHTML = ''; // Clear previous content
            return;
        } else {
            if (noScheduleMessage) {
                noScheduleMessage.classList.add('hidden');
            }
        }

        const d = new Date(selectedDate + 'T12:00:00');
        const dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const heading = dayNames[d.getDay()] + ', ' + d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
        function fmtDisplayDate(dStr) { if (!dStr) return '-'; const p = dStr.split('-'); return parseInt(p[2]) + ' ' + monthNames[parseInt(p[1])-1] + ' ' + p[0]; }

        let cardsHtml = '';
        schedules.forEach(function(s) {
            const isDisabled = s.isBooked || s.isFull;
            const capLabel = s.hasCapacity ? s.bookedCount + '/' + s.capacity + ' terbook · ' + s.remaining + ' sisa' : 'Tanpa Batas';
            const statusHtml = s.isBooked
                ? '<span class="font-poppins text-[11px] font-semibold text-accent bg-[rgba(26,122,94,0.08)] px-3.5 py-1.5 rounded-full flex items-center gap-1.5"><i class="fas fa-check-circle text-[12px]"></i>Terbooking</span>'
                : s.isFull
                ? '<span class="font-poppins text-[11px] font-semibold text-dark/30 bg-[rgba(28,28,28,0.05)] px-3.5 py-1.5 rounded-full flex items-center gap-1.5"><i class="fas fa-ban text-[12px]"></i>Penuh</span>'
                : '<span class="font-poppins text-[11px] font-semibold text-primary bg-[rgba(238,78,139,0.08)] px-3.5 py-1.5 rounded-full schedule-card-accent flex items-center gap-1.5"><i class="fas fa-plus-circle text-[12px]"></i>Pilih</span>';

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
                                <div class="flex items-start justify-between gap-2 class-name-row">
                                    <div class="min-w-0">
                                        <h4 class="font-nord font-bold text-[17px] text-dark leading-tight class-name-line">${s.className}</h4>
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
                        <span id="schedule-count" class="font-poppins text-[12px] text-dark/35 bg-white px-3 py-1 rounded-full border border-[rgba(238,78,139,0.08)]">${schedules.length} kelas</span>
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

    // ============================================
    // KALENDER POPUP (PILIH TANGGAL)
    // ============================================
    let calendarView = null;
    const fullMonthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    function openDatePicker() {
        const popup = document.getElementById('date-picker-popup');
        const trigger = document.getElementById('date-trigger');
        if (!popup) return;
        const sel = new Date(selectedDate + 'T12:00:00');
        calendarView = new Date(sel.getFullYear(), sel.getMonth(), 1);
        renderCalendar();
        popup.classList.remove('hidden');
        if (trigger) trigger.setAttribute('aria-expanded', 'true');
    }

    function closeDatePicker() {
        const popup = document.getElementById('date-picker-popup');
        const trigger = document.getElementById('date-trigger');
        if (popup) popup.classList.add('hidden');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }

    function toggleDatePicker(e) {
        if (e) e.stopPropagation();
        const popup = document.getElementById('date-picker-popup');
        if (!popup) return;
        if (popup.classList.contains('hidden')) { openDatePicker(); } else { closeDatePicker(); }
    }

    function calendarShiftMonth(delta) {
        if (!calendarView) {
            const sel = new Date(selectedDate + 'T12:00:00');
            calendarView = new Date(sel.getFullYear(), sel.getMonth(), 1);
        }
        calendarView = new Date(calendarView.getFullYear(), calendarView.getMonth() + delta, 1);
        renderCalendar();
    }

    function calendarGoToday() {
        selectDate(todayStr);
        closeDatePicker();
    }

    function renderCalendar() {
        const grid = document.getElementById('calendar-grid');
        const label = document.getElementById('calendar-month-label');
        if (!grid || !calendarView) return;

        if (label) label.textContent = fullMonthNames[calendarView.getMonth()] + ' ' + calendarView.getFullYear();

        const year = calendarView.getFullYear();
        const month = calendarView.getMonth();
        const startOffset = (new Date(year, month, 1).getDay() + 6) % 7; // Senin = 0
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthDays = new Date(year, month, 0).getDate();

        let html = '';
        for (let i = startOffset - 1; i >= 0; i--) {
            html += '<span class="bc-cal-day is-muted">' + (prevMonthDays - i) + '</span>';
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = formatYMD(new Date(year, month, d));
            let cls = 'bc-cal-day';
            if (dateStr === selectedDate) cls += ' is-selected';
            else if (dateStr === todayStr) cls += ' is-today';
            html += '<button type="button" class="' + cls + '" onclick="pickCalendarDate(\'' + dateStr + '\')">' + d + '</button>';
        }
        const trailing = (7 - ((startOffset + daysInMonth) % 7)) % 7;
        for (let i = 1; i <= trailing; i++) {
            html += '<span class="bc-cal-day is-muted">' + i + '</span>';
        }
        grid.innerHTML = html;
    }

    function pickCalendarDate(dateStr) {
        selectedDate = dateStr;
        currentWeekStart = getMonday(new Date(dateStr + 'T12:00:00'));
        renderAll();
        closeDatePicker();
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#date-picker-popup') && !e.target.closest('#date-trigger')) {
            closeDatePicker();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDatePicker();
    });

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
