<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard | FTM Society</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')

    {{-- FTM Brand Typography --}}
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">

    {{-- FTM Member Portal Theme (unified sidebar + colors) --}}
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ==========================================================
           FTM SOCIETY DASHBOARD — Flat brand palette only
           Power Pink #EE4E8B | Burnt Cherry #7A2B4A | Soft Petals #F4C9DF
           Patina Green #1A7A5E | Springs Ivy #1D5A4B | Grounded #C5D79B
           Layl #1C1C1C | Rising #FCF9F2
           ========================================================== */
        :root {
            --pink:   #EE4E8B;
            --cherry: #7A2B4A;
            --petal:  #F4C9DF;
            --green:  #1A7A5E;
            --ivy:    #1D5A4B;
            --sage:   #C5D79B;
            --layl:   #1C1C1C;
            --rising: #FCF9F2;
        }

        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: var(--layl);
            background: var(--rising);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            letter-spacing: -0.015em;
            color: var(--cherry);
        }

        /* Sidebar */
        .sidebar { transition: transform 0.3s ease-in-out; }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 50;
                transform: translateX(-100%);
            }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(28, 28, 28, 0.55);
                z-index: 40;
            }
            .sidebar-overlay.active { display: block; }
            .main-content { margin-left: 0 !important; }
            .hamburger-btn { display: flex !important; }
        }

        @media (min-width: 769px) {
            .sidebar { position: relative; }
            .sidebar-overlay { display: none !important; }
            .hamburger-btn { display: none !important; }
        }

        @keyframes scale-in {
            0% { opacity: 0; transform: scale(0.92) translateY(8px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-scale-in {
            animation: scale-in 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Sidebar nav item */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.95rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.92rem;
            color: rgba(252, 249, 242, 0.88);
            transition: background 0.18s ease;
            text-decoration: none;
        }
        .nav-item i {
            width: 1.125rem;
            font-size: 0.95rem;
            color: rgba(252, 249, 242, 0.75);
        }
        .nav-item:hover {
            background: rgba(238, 78, 139, 0.18);
            color: var(--rising);
        }
        .nav-item:hover i { color: var(--rising); }

        .nav-item.active {
            background: var(--pink);
            color: var(--rising);
            box-shadow: 0 4px 12px rgba(238, 78, 139, 0.32);
        }
        .nav-item.active i { color: var(--rising); }

        /* Stat cards */
        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--petal);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .stat-card:hover {
            box-shadow: 0 8px 20px rgba(122, 43, 74, 0.08);
            transform: translateY(-2px);
        }
        .stat-card .stat-label {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--cherry);
            margin-bottom: 0.4rem;
        }
        .stat-card .stat-value {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            line-height: 1.1;
            color: var(--layl);
            margin-bottom: 0.3rem;
        }
        .stat-card .stat-meta {
            font-size: 0.78rem;
            color: rgba(28, 28, 28, 0.6);
        }
        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }
        .stat-icon.pink   { background: rgba(238, 78, 139, 0.12); color: var(--pink); }
        .stat-icon.green  { background: rgba(26, 122, 94, 0.12);  color: var(--green); }
        .stat-icon.cherry { background: rgba(122, 43, 74, 0.12);  color: var(--cherry); }

        /* Content card */
        .content-card {
            background: #FFFFFF;
            border: 1px solid var(--petal);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
        }

        /* Section title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--cherry);
            margin-bottom: 1rem;
        }
        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 1.1rem;
            background: var(--pink);
            border-radius: 2px;
        }

        /* Quick action buttons */
        .qa-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.85rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 0.88rem;
            transition: background 0.2s ease, transform 0.18s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }
        .qa-btn:hover { transform: translateY(-1px); }

        .qa-btn.pink {
            background: var(--pink);
            color: var(--rising);
            box-shadow: 0 3px 10px rgba(238, 78, 139, 0.25);
        }
        .qa-btn.pink:hover {
            background: var(--cherry);
            box-shadow: 0 5px 14px rgba(122, 43, 74, 0.3);
        }
        .qa-btn.green {
            background: var(--green);
            color: var(--rising);
            box-shadow: 0 3px 10px rgba(26, 122, 94, 0.22);
        }
        .qa-btn.green:hover {
            background: var(--ivy);
            box-shadow: 0 5px 14px rgba(29, 90, 75, 0.28);
        }
        .qa-btn.sage {
            background: var(--sage);
            color: var(--ivy);
            border: 1px solid rgba(26, 90, 75, 0.18);
        }
        .qa-btn.sage:hover {
            background: var(--green);
            color: var(--rising);
        }

        /* ==========================================================
           Member Card — Rising bg + Burnt Cherry text
           Per desain.md kombinasi: "Burnt Cherry + Rising → elegan & warm"
           Pakai hex langsung + !important agar bullet-proof terhadap override
           ========================================================== */
        .member-card {
            background-color: #FCF9F2 !important;        /* Rising */
            background-image: none !important;
            border-radius: 18px !important;
            padding: 1.6rem 1.35rem !important;
            color: #1C1C1C !important;                    /* Layl text default */
            position: relative;
            overflow: hidden;
            border: 2px solid #EE4E8B;                    /* Power Pink frame */
            box-shadow:
                0 14px 32px rgba(122, 43, 74, 0.18),
                0 4px 10px rgba(28, 28, 28, 0.08);
        }

        /* Power Pink top stripe */
        .member-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background-color: #EE4E8B;
            z-index: 1;
        }
        /* Soft Petals decorative corner — supergraphic */
        .member-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background-color: rgba(244, 201, 223, 0.45);
            pointer-events: none;
            z-index: 0;
        }
        .member-card-pattern {
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: rgba(238, 78, 139, 0.10);
            pointer-events: none;
            inset: auto auto -40px -40px;
        }

        /* Eyebrow "FTM SOCIETY" — Power Pink, kontras kuat di Rising */
        .mc-eyebrow {
            font-family: 'Nord', 'Poppins', sans-serif !important;
            font-weight: 800 !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.4em !important;
            color: #EE4E8B !important;                    /* Power Pink */
            text-transform: uppercase !important;
            margin: 0 !important;
        }
        /* Title "MEMBER CARD" — Burnt Cherry, kuat */
        .mc-title {
            font-family: 'Nord', 'Poppins', sans-serif !important;
            font-weight: 900 !important;
            font-size: 1.7rem !important;
            letter-spacing: 0.04em !important;
            color: #7A2B4A !important;                    /* Burnt Cherry */
            margin-top: 0.4rem !important;
            line-height: 1.05 !important;
            text-transform: uppercase !important;
        }
        .mc-title-accent {
            display: block;
            width: 50px;
            height: 4px;
            margin: 0.7rem auto 0;
            background-color: #EE4E8B;                    /* Power Pink underline */
            border-radius: 999px;
        }

        /* Avatar */
        .mc-avatar {
            width: 4.5rem !important;
            height: 4.5rem !important;
            margin: 0 auto 0.85rem !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: #EE4E8B !important;         /* Power Pink */
            color: #FCF9F2 !important;                    /* Rising */
            font-family: 'Nord', sans-serif !important;
            font-weight: 900 !important;
            font-size: 1.85rem !important;
            border: 4px solid #FCF9F2 !important;
            box-shadow:
                0 0 0 2px #EE4E8B,
                0 8px 20px rgba(238, 78, 139, 0.40) !important;
        }

        /* Member name "DINO" — Burnt Cherry, tegas */
        .mc-name {
            font-family: 'Nord', sans-serif !important;
            font-weight: 900 !important;
            font-size: 1.25rem !important;
            letter-spacing: 0.06em !important;
            color: #7A2B4A !important;                    /* Burnt Cherry */
            margin: 0 !important;
            text-transform: uppercase !important;
        }
        /* Member ID label */
        .mc-meta {
            font-size: 0.85rem !important;
            margin-top: 0.5rem !important;
            color: #1C1C1C !important;                    /* Layl */
            font-weight: 600 !important;
            font-family: 'Poppins', sans-serif !important;
        }
        .mc-meta .mc-id {
            font-family: 'JetBrains Mono', 'SF Mono', monospace !important;
            font-weight: 800 !important;
            color: #FCF9F2 !important;                    /* Rising */
            background-color: #7A2B4A !important;         /* Burnt Cherry chip */
            padding: 3px 10px !important;
            border-radius: 6px !important;
            letter-spacing: 0.12em !important;
            font-size: 0.82rem !important;
            margin-left: 0.35rem;
            box-shadow: 0 2px 6px rgba(122, 43, 74, 0.30);
            display: inline-block;
        }

        /* QR panel — Soft Petals tinted with Pink frame */
        .mc-qr-panel {
            background-color: #FCF9F2 !important;
            border-radius: 12px !important;
            padding: 0.85rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 140px;
            box-shadow:
                0 4px 12px rgba(122, 43, 74, 0.12),
                inset 0 0 0 1px rgba(238, 78, 139, 0.18);
            border: 2px solid #F4C9DF;                    /* Soft Petals */
        }
        .mc-tap-hint {
            font-size: 0.78rem !important;
            margin: 0.85rem 0 0.7rem !important;
            color: #7A2B4A !important;                    /* Burnt Cherry */
            letter-spacing: 0.05em !important;
            font-weight: 600 !important;
            font-family: 'Poppins', sans-serif !important;
        }
        .mc-divider {
            border-top: 1px solid #F4C9DF !important;
            padding-top: 0.85rem !important;
            margin-top: 0.5rem !important;
        }

        /* Status badge */
        .mc-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-family: 'Nord', sans-serif !important;
            font-weight: 800 !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
        }
        .mc-badge.active {
            background-color: #C5D79B !important;          /* Grounded Green */
            color: #1D5A4B !important;                     /* Springs Ivy */
            box-shadow: 0 4px 12px rgba(26, 122, 94, 0.25);
        }
        .mc-badge.inactive {
            background-color: #F4C9DF !important;          /* Soft Petals */
            color: #7A2B4A !important;                     /* Burnt Cherry */
        }

        /* Info banner (How to Use QR) */
        .info-banner {
            background: var(--petal);
            border-left: 4px solid var(--pink);
            border-radius: 0 12px 12px 0;
            padding: 1rem 1.25rem;
            color: var(--cherry);
        }
        .info-banner ol {
            font-size: 0.86rem;
            color: var(--layl);
            line-height: 1.7;
        }

        /* Status badge */
        .badge-active {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            background: rgba(26, 122, 94, 0.12);
            color: var(--green);
            font-weight: 700;
            font-size: 0.8rem;
        }
        .badge-active .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--green);
            animation: pulse-dot 1.6s infinite;
        }
    </style>
</head>

<body class="h-screen overflow-hidden">

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

<div class="flex h-screen">

    @include('partials.member-sidebar')

    {{-- ===========================================================
         MAIN CONTENT
         =========================================================== --}}
    <main class="main-content flex-1 p-4 md:p-8 overflow-y-auto" style="background: var(--rising);">

        {{-- Mobile Hamburger --}}
        <button id="hamburger-btn" class="hamburger-btn hidden fixed top-4 left-4 z-30 w-10 h-10 rounded-lg items-center justify-center"
                style="background: var(--pink); color: var(--rising); box-shadow: 0 4px 12px rgba(238, 78, 139, 0.3);"
                onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Header --}}
        <div class="mb-7 mt-12 md:mt-0">
            <h1 class="text-2xl md:text-3xl">
                Member Dashboard
            </h1>
            <p class="text-sm mt-1.5" style="color: rgba(28, 28, 28, 0.65);">
                Welcome back, <span style="color: var(--cherry); font-weight: 700;">{{ $customer->name }}</span>
            </p>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-7">
            {{-- Credit --}}
            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="stat-label">Credit</p>
                    <div class="stat-icon pink"><i class="fas fa-calendar-check"></i></div>
                </div>
                <p class="stat-value">{{ $remainingClasses }}</p>
                <p class="stat-meta">For booking</p>
            </div>

            {{-- Remaining Quota --}}
            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="stat-label" style="color: var(--ivy);">Remaining Quota</p>
                    <div class="stat-icon green"><i class="fas fa-ticket-alt"></i></div>
                </div>
                <p class="stat-value">{{ $remainingQuota }}</p>
                <p class="stat-meta">For check-in/out</p>
            </div>

            {{-- Status --}}
            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="stat-label">Status</p>
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                </div>
                <p class="stat-value flex items-center gap-2" style="color: var(--green); font-size: 1.5rem;">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background: var(--green); animation: pulse-dot 1.6s infinite;"></span>
                    Active
                </p>
                <p class="stat-meta">Membership terverifikasi</p>
            </div>
        </div>

        {{-- QR CARD + QUICK ACTIONS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5 mb-7">

            {{-- Member QR Card --}}
            <div class="member-card"
                 style="min-height: 360px; background-color: #FCF9F2 !important; background-image: none !important; color: #1C1C1C !important;">
                <div class="member-card-pattern"></div>

                <div class="relative z-10 flex flex-col h-full text-center">
                    <div class="mb-4">
                        <div class="mc-eyebrow">FTM SOCIETY</div>
                        <div class="mc-title">MEMBER CARD</div>
                        <span class="mc-title-accent"></span>
                    </div>

                    {{-- Avatar + Info --}}
                    <div class="flex-1 flex flex-col justify-center mb-4">
                        <div class="mc-avatar">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <h3 class="mc-name">
                            {{ $customer->name }}
                        </h3>
                        <p class="mc-meta">
                            Member ID: <span class="mc-id">{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </p>
                    </div>

                    {{-- QR Code --}}
                    <div class="mc-qr-panel">
                        @if($customer->qr_token && $customer->qr_active)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode($customer->getQRData()) }}&bgcolor=fcf9f2&color=1c1c1c"
                                 alt="QR Code"
                                 onclick="openQRPreview()"
                                 style="cursor: pointer; max-width: 110px; max-height: 110px;">
                        @else
                            <div class="text-center" style="color: rgba(28, 28, 28, 0.45);">
                                <i class="fas fa-qrcode text-3xl mb-1" style="color: #7A2B4A;"></i>
                                <p class="text-xs">No QR Code</p>
                            </div>
                        @endif
                    </div>

                    @if($customer->qr_token && $customer->qr_active)
                        <p class="mc-tap-hint">
                            <i class="fas fa-expand-alt mr-1"></i>Tap QR to enlarge
                        </p>

                        <div class="mc-divider text-center">
                            <span class="mc-badge active">
                                <i class="fas fa-check-circle"></i> QR Active
                            </span>
                        </div>
                    @else
                        <div class="mc-divider text-center" style="margin-top: 1rem;">
                            <span class="mc-badge inactive">
                                <i class="fas fa-times-circle"></i> QR Inactive
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions + Info --}}
            <div class="lg:col-span-2 flex flex-col gap-4 md:gap-5">

                {{-- Quick Actions --}}
                <div class="content-card">
                    <h3 class="section-title">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <a href="{{ route('member.account') }}" class="qa-btn pink">
                            <i class="fas fa-qrcode"></i> My QR Card
                        </a>
                        <a href="{{ route('member.attendance') }}" class="qa-btn green">
                            <i class="fas fa-history"></i> History
                        </a>
                        <a href="{{ route('member.book') }}" class="qa-btn sage">
                            <i class="fas fa-calendar-plus"></i> Book Now
                        </a>
                    </div>
                </div>

                {{-- How to Use QR Card --}}
                <div class="info-banner">
                    <h4 class="font-bold mb-2 flex items-center gap-2" style="color: var(--cherry); font-family: 'Nord', sans-serif; font-size: 0.95rem;">
                        <i class="fas fa-info-circle" style="color: var(--pink);"></i>
                        How to Use Your QR Card
                    </h4>
                    <ol class="list-decimal list-inside space-y-0.5">
                        <li>Show this QR code to the staff or trainer</li>
                        <li>Staff will scan your QR code at the scanner</li>
                        <li>Your attendance is recorded automatically</li>
                        <li>Your quota visit is deducted by 1</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- ATTENDANCE HISTORY --}}
        <div class="content-card">
            <h3 class="section-title">
                <i class="fas fa-history" style="color: var(--green);"></i>
                Recent Attendance
            </h3>

            <div class="divide-y" style="--tw-divide-opacity: 1; divide-color: rgba(244, 201, 223, 0.5);">
                @forelse($attendances->take(5) as $a)
                    <div class="py-3 flex justify-between items-center px-2 rounded transition"
                         onmouseover="this.style.background='rgba(244, 201, 223, 0.18)'"
                         onmouseout="this.style.background='transparent'">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background: var(--green); color: var(--rising);">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm truncate" style="color: var(--layl);">
                                    {{ $a->program ?? 'General' }}
                                </p>
                                <p class="text-xs" style="color: var(--green);">
                                    <i class="fas fa-circle text-[6px] mr-1"></i>{{ $a->check_in_type ?? 'system' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="font-bold text-sm whitespace-nowrap" style="color: var(--cherry);">
                                {{ $a->getFormattedDuration() ?? '-' }}
                            </p>
                            <p class="text-xs whitespace-nowrap" style="color: rgba(28, 28, 28, 0.55);">
                                {{ $a->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3"
                             style="background: var(--petal);">
                            <i class="fas fa-clipboard-list text-3xl" style="color: var(--cherry);"></i>
                        </div>
                        <p class="font-semibold" style="color: var(--layl);">No attendance yet.</p>
                        <p class="text-sm mt-1" style="color: rgba(28, 28, 28, 0.55);">
                            Scan your QR code to record attendance
                        </p>
                        <a href="{{ route('member.qr.scanner') }}" class="qa-btn green inline-flex mt-4">
                            <i class="fas fa-qrcode"></i> Start Scanning
                        </a>
                    </div>
                @endforelse
            </div>

            @if($attendances->count() > 5)
                <div class="text-center mt-4 pt-4" style="border-top: 1px solid rgba(244, 201, 223, 0.5);">
                    <a href="{{ route('member.attendance') }}"
                       class="font-bold text-sm inline-flex items-center gap-1"
                       style="color: var(--pink);"
                       onmouseover="this.style.color='var(--cherry)'"
                       onmouseout="this.style.color='var(--pink)'">
                        View All Attendance Records
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @endif
        </div>

    </main>
</div>

{{-- ===========================================================
     QR PREVIEW MODAL
     =========================================================== --}}
@if($customer->qr_token && $customer->qr_active)
<div id="qr-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background: rgba(28, 28, 28, 0.85); backdrop-filter: blur(10px);">
    <div class="absolute inset-0" onclick="closeQRPreview()"></div>

    <div class="relative z-10 w-full max-w-md mx-4 animate-scale-in">
        <button onclick="closeQRPreview()"
                class="absolute -top-3 -right-3 w-10 h-10 rounded-full flex items-center justify-center text-lg z-20"
                style="background: var(--pink); color: var(--rising); border: 2px solid var(--rising); box-shadow: 0 4px 12px rgba(238, 78, 139, 0.4);">
            <i class="fas fa-times"></i>
        </button>

        <div class="rounded-2xl p-7 shadow-2xl relative overflow-hidden"
             style="background-color: #1C1C1C !important; background-image: none !important; border: 1px solid rgba(238, 78, 139, 0.35); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55);">

            {{-- Top accent stripe --}}
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background-color: #EE4E8B; z-index: 2;"></div>

            {{-- Decorative pink glow --}}
            <div style="position: absolute; top: -80px; right: -80px; width: 240px; height: 240px; border-radius: 50%; background-color: rgba(238, 78, 139, 0.20); pointer-events: none; z-index: 0;"></div>
            <div style="position: absolute; bottom: -60px; left: -60px; width: 180px; height: 180px; border-radius: 50%; background-color: rgba(244, 201, 223, 0.08); pointer-events: none; z-index: 0;"></div>

            <div class="relative z-10">
                <div class="text-center mb-5">
                    <p style="font-family: 'Nord', sans-serif; font-weight: 800; font-size: 0.72rem; letter-spacing: 0.4em; color: #EE4E8B; text-transform: uppercase; margin: 0;">
                        FTM Society
                    </p>
                    <h2 style="font-family: 'Nord', sans-serif; font-weight: 900; font-size: 1.4rem; letter-spacing: 0.04em; color: #FCF9F2; margin: 0.4rem 0 0;">
                        MEMBER CARD
                    </h2>
                    <span style="display: block; width: 44px; height: 3px; margin: 0.7rem auto 0; background-color: #EE4E8B; border-radius: 999px;"></span>
                </div>

                <div class="flex justify-center mb-5">
                    <div class="rounded-xl p-4"
                         style="background-color: #FCF9F2; border: 3px solid #EE4E8B; box-shadow: 0 14px 32px rgba(0, 0, 0, 0.45);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($customer->getQRData()) }}&bgcolor=fcf9f2&color=1c1c1c"
                             alt="QR Code"
                             style="width: 240px; height: 240px;" class="block">
                    </div>
                </div>

                <div class="text-center mb-4">
                    <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-2.5"
                         style="background-color: #EE4E8B; color: #FCF9F2; font-family: 'Nord', sans-serif; font-weight: 900; font-size: 1.5rem; border: 3px solid #FCF9F2; box-shadow: 0 6px 18px rgba(238, 78, 139, 0.45);">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <h3 style="font-family: 'Nord', sans-serif; font-weight: 800; font-size: 1.1rem; letter-spacing: 0.05em; color: #FCF9F2; text-transform: uppercase; margin: 0; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);">
                        {{ $customer->name }}
                    </h3>
                    <p class="text-sm mt-1.5" style="color: rgba(252, 249, 242, 0.7); font-weight: 500;">
                        Member ID:
                        <span style="font-family: 'JetBrains Mono', 'SF Mono', monospace; font-weight: 800; color: #FCF9F2; background-color: #EE4E8B; padding: 3px 10px; border-radius: 6px; letter-spacing: 0.1em; font-size: 0.78rem; margin-left: 0.25rem; box-shadow: 0 2px 6px rgba(238, 78, 139, 0.35);">
                            #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </p>
                </div>

                <div class="text-center mb-3">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full"
                          style="background-color: #C5D79B; color: #1D5A4B; font-family: 'Nord', sans-serif; font-weight: 800; font-size: 0.72rem; letter-spacing: 0.08em; text-transform: uppercase; box-shadow: 0 4px 12px rgba(26, 122, 94, 0.3);">
                        <span class="w-2 h-2 rounded-full" style="background-color: #1A7A5E; animation: pulse-dot 1.6s infinite;"></span>
                        QR Active — Ready to Scan
                    </span>
                </div>

                <div class="pt-3" style="border-top: 1px solid rgba(238, 78, 139, 0.25);">
                    <p class="text-center text-xs" style="color: rgba(244, 201, 223, 0.75); font-weight: 500;">
                        <i class="fas fa-info-circle mr-1" style="color: #EE4E8B;"></i>
                        Show this code to staff for check-in
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openQRPreview() {
        const modal = document.getElementById('qr-preview-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeQRPreview() {
        const modal = document.getElementById('qr-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQRPreview();
    });
</script>
@endif

{{-- ===========================================================
     LOGOUT MODAL — disediakan oleh partial member-sidebar.blade.php
     (window.showLogoutModal & window.closeLogoutModal sudah diexpose
      sebagai alias untuk backward compatibility)
     =========================================================== --}}

{{-- ===========================================================
     SIDEBAR TOGGLE (Mobile)
     =========================================================== --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburger = document.getElementById('hamburger-btn');

        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');

        const icon = hamburger.querySelector('i');
        if (sidebar.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            if (window.innerWidth <= 768) document.body.style.overflow = 'hidden';
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar.classList.contains('active')) toggleSidebar();
                }
            });
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>
