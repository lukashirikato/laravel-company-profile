<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard | FTM Society</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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

        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; box-sizing: border-box; }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 500;
            color: var(--layl);
            background: var(--rising);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            letter-spacing: -0.015em;
        }

        @keyframes scale-in {
            0% { opacity: 0; transform: scale(0.92) translateY(8px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-scale-in { animation: scale-in 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        @keyframes ringProgress {
            0% { stroke-dashoffset: 283; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        .sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; top: 0; left: 0; bottom: 0; z-index: 50; transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(28, 28, 28, 0.55); z-index: 40; }
            .sidebar-overlay.active { display: block; }
            .main-content { margin-left: 0 !important; }
            .hamburger-btn { display: flex !important; }
        }
        @media (min-width: 769px) {
            .sidebar { position: relative; }
            .sidebar-overlay { display: none !important; }
            .hamburger-btn { display: none !important; }
        }

        .nav-item {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0.95rem; border-radius: 8px;
            font-weight: 600; font-size: 0.92rem; color: rgba(252, 249, 242, 0.88); transition: background 0.18s ease; text-decoration: none;
        }
        .nav-item i { width: 1.125rem; font-size: 0.95rem; color: rgba(252, 249, 242, 0.75); }
        .nav-item:hover { background: rgba(238, 78, 139, 0.18); color: var(--rising); }
        .nav-item:hover i { color: var(--rising); }
        .nav-item.active { background: var(--pink); color: var(--rising); box-shadow: 0 4px 12px rgba(238, 78, 139, 0.32); }
        .nav-item.active i { color: var(--rising); }

        .stat-icon { width: 2.5rem; height: 2.5rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; }
        .stat-icon.pink   { background: rgba(238, 78, 139, 0.12); color: var(--pink); }
        .stat-icon.green  { background: rgba(26, 122, 94, 0.12); color: var(--green); }
        .stat-icon.cherry { background: rgba(122, 43, 74, 0.12); color: var(--cherry); }
        .stat-icon.soft   { background: rgba(244, 201, 223, 0.3); color: var(--cherry); }

        .badge-active {
            display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.2rem 0.7rem; border-radius: 999px;
            background: rgba(26, 122, 94, 0.12); color: var(--green); font-weight: 700; font-size: 0.8rem;
        }
        .badge-active .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--green); animation: pulse-dot 1.6s infinite; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#FCF9F2]">

<div class="flex h-screen">

    @include('partials.member-sidebar')

    {{-- ===========================================================
         MAIN CONTENT
         =========================================================== --}}
    <main class="main-content flex-1 overflow-y-auto bg-[#FCF9F2]">

        {{-- Mobile Hamburger --}}
        <button id="hamburger-btn" class="hamburger-btn hidden fixed top-4 left-4 z-30 w-10 h-10 rounded-lg items-center justify-center"
                style="background: var(--pink); color: var(--rising); box-shadow: 0 4px 12px rgba(238, 78, 139, 0.3);"
                onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        {{-- ===========================================================
             TOP HEADER — Greeting + Notif & Avatar
             =========================================================== --}}
        <div class="px-6 md:px-10 pt-6 md:pt-10 pb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-nord text-2xl md:text-[28px] font-bold text-[#7A2B4A]">
                        Assalamu'alaikum, {{ $customer->name }}
                    </h1>
                    <p class="text-sm text-[#1C1C1C]/55 mt-1 leading-relaxed max-w-lg">
                        "Setiap langkah kecil membawamu lebih dekat ke versi terbaik dirimu."<br>
                        <span class="text-xs text-[#1C1C1C]/40">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">

                    <a href="{{ route('member.account') }}" class="block group">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-nord font-bold text-base shadow-lg border-2 border-white/80 transition-transform duration-200 group-hover:scale-105 group-hover:shadow-xl"
                             style="background: linear-gradient(135deg, #EE4E8B, #7A2B4A); box-shadow: 0 4px 12px rgba(238,78,139,0.3), 0 0 0 4px rgba(238,78,139,0.12);">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 md:px-10 pb-8 space-y-6">

            {{-- ===========================================================
                 HERO SECTION — 2 Columns: Spotlight + Membership Card
                 =========================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                {{-- LEFT: Member Spotlight --}}
                <div class="lg:col-span-7 relative rounded-2xl overflow-hidden min-h-[320px] md:min-h-[360px] animate-fade-up delay-1"
                     style="background: linear-gradient(135deg, #7A2B4A 0%, #1C1C1C 100%);">
                    <div class="absolute inset-0 opacity-30"
                         style="background-image: url('https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=800&q=80'); background-size: cover; background-position: center;"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1C1C1C]/90 via-[#1C1C1C]/40 to-transparent"></div>

                    <div class="relative z-10 p-6 md:p-8 flex flex-col justify-end h-full">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm text-white/90 text-[10px] font-bold uppercase tracking-[0.2em] mb-3 w-fit border border-white/20">
                            <i class="fas fa-star text-[10px]"></i> MEMBER SPOTLIGHT
                        </span>
                        <h2 class="font-nord text-white text-2xl md:text-3xl font-black leading-tight">
                            Your Fitness Journey<br>
                            <span class="font-instrument italic font-normal text-transparent bg-clip-text bg-gradient-to-r from-[#F4C9DF] to-[#EE4E8B]">Starts Here</span>
                        </h2>
                        <p class="text-white/70 text-sm mt-2 max-w-md">
                            Stay consistent, track your progress, and achieve your goals with FTM Society.
                        </p>
                    </div>
                </div>

                {{-- RIGHT: Membership Card --}}
                <div class="lg:col-span-5 rounded-2xl overflow-hidden relative animate-fade-up delay-2"
                     style="background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%); min-height: 320px;">
                    <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-white/5 -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full bg-white/5 -ml-12 -mb-12"></div>

                    <div class="relative z-10 p-6 md:p-8 flex flex-col h-full justify-between text-white">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/70">EXCLUSIVE MEMBER</span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C5D79B]/30 text-white text-[10px] font-bold uppercase tracking-[0.12em] border border-[#C5D79B]/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#C5D79B] animate-pulse"></span>
                                    ACTIVE
                                </span>
                            </div>
                            <h3 class="font-nord text-lg md:text-xl font-black tracking-tight">EXCLUSIVE ELITE</h3>
                        </div>

                        <div class="space-y-3 mt-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-white/60">Remaining Sessions</p>
                                <p class="font-nord text-4xl md:text-5xl font-black leading-none mt-1">{{ $remainingQuota }}</p>
                            </div>
                            <div class="w-full h-2 rounded-full bg-white/20 overflow-hidden">
                                <div class="h-full rounded-full bg-white/80 transition-all duration-700"
                                     style="width: {{ min(($remainingQuota / 12) * 100, 100) }}%"></div>
                            </div>
                            <p class="text-[11px] text-white/60">Expired: {{ \Carbon\Carbon::now()->addDays(30)->isoFormat('D MMMM YYYY') }}</p>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-3 border-t border-white/15">
                            <a href="{{ route('member.packages.index') }}"
                               class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-white text-[#7A2B4A] font-nord font-bold text-xs hover:bg-[#FCF9F2] transition-all shadow-lg hover:shadow-xl">
                                RENEW PACKAGE <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 QUICK ACTION CARDS — 4 Columns
                 =========================================================== --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-up delay-3">
                @php
                    $quickActions = [
                        ['icon' => 'fa-calendar-plus', 'color' => 'pink', 'label' => 'Book Class', 'sub' => 'Schedule a class', 'route' => 'member.book'],
                        ['icon' => 'fa-clock', 'color' => 'cherry', 'label' => 'Schedule', 'sub' => 'View timetable', 'route' => 'member.my-classes'],
                        ['icon' => 'fa-qrcode', 'color' => 'green', 'label' => 'Attendance', 'sub' => 'Check your logs', 'route' => 'member.attendance'],
                        ['icon' => 'fa-receipt', 'color' => 'soft', 'label' => 'Payment', 'sub' => 'Billing & history', 'route' => 'member.transactions'],
                    ];
                @endphp
                @foreach($quickActions as $qa)
                <a href="{{ route($qa['route']) }}"
                   class="bg-white rounded-xl p-5 border border-[#F4C9DF]/40 hover:border-[#EE4E8B]/30 hover:shadow-lg transition-all duration-200 group">
                    <div class="stat-icon {{ $qa['color'] }} mb-3 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas {{ $qa['icon'] }}"></i>
                    </div>
                    <h4 class="font-nord font-bold text-sm text-[#1C1C1C] group-hover:text-[#EE4E8B] transition-colors">{{ $qa['label'] }}</h4>
                    <p class="text-xs text-[#1C1C1C]/50 mt-0.5">{{ $qa['sub'] }}</p>
                </a>
                @endforeach
            </div>

            {{-- ===========================================================
                 BOTTOM SECTION — 2 Columns: Next Class + Activity Stats
                 =========================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 animate-fade-up delay-4">

                {{-- LEFT: Your Next Class --}}
                <div class="lg:col-span-7 bg-white rounded-2xl border border-[#F4C9DF]/40 p-5 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-nord font-bold text-sm text-[#7A2B4A] flex items-center gap-2">
                            <i class="fas fa-dumbbell text-[#EE4E8B]"></i> Your Next Class
                        </h3>
                        <a href="{{ route('member.my-classes') }}" class="text-xs font-semibold text-[#EE4E8B] hover:text-[#7A2B4A] transition-colors flex items-center gap-1">
                            View All <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    @php
                        $classModel = $nextClass?->schedule;
                        $className = $classModel?->className ?? 'CLASS';
                        $classTime = $classModel?->class_time ? \Carbon\Carbon::parse($classModel->class_time)->format('H:i') : '--:--';
                        $instructor = $classModel?->instructor ?? 'Instructor';
                        $packageName = $nextClass?->order?->package?->name ?? 'REGULAR';
                        $scheduleDate = $classModel?->schedule_date ? \Carbon\Carbon::parse($classModel->schedule_date)->isoFormat('dddd, D MMM') : ($classModel?->day ?? '');
                    @endphp

                    @if($nextClass)
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-[160px] h-[120px] rounded-xl overflow-hidden flex-shrink-0 relative"
                             style="background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%);">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-dumbbell text-4xl text-white/30"></i>
                            </div>
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider border border-white/20">
                                {{ $className }}
                            </span>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div>
                                <span class="inline-block px-2.5 py-1 rounded-md bg-[#EE4E8B]/10 text-[#EE4E8B] text-[10px] font-bold uppercase tracking-wider">
                                    {{ strtoupper($packageName) }}
                                </span>
                            </div>
                            <h4 class="font-nord font-bold text-sm text-[#1C1C1C]">
                                {{ $className }}
                            </h4>
                            <div class="flex items-center gap-4 text-xs text-[#1C1C1C]/60">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar text-[#EE4E8B] text-[10px]"></i>
                                    {{ $scheduleDate }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-clock text-[#EE4E8B] text-[10px]"></i>
                                    {{ $classTime }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-user text-[#EE4E8B] text-[10px]"></i>
                                    {{ $instructor }}
                                </span>
                            </div>
                            <a href="{{ route('member.my-classes') }}"
                               class="inline-flex w-full items-center justify-center gap-2 mt-2 px-4 py-2.5 rounded-xl bg-[#FCF9F2] border border-[#F4C9DF]/50 text-[#7A2B4A] font-nord font-bold text-xs hover:bg-[#EE4E8B] hover:text-white hover:border-[#EE4E8B] transition-all">
                                View Details & Location <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 mx-auto rounded-full bg-[#F4C9DF]/40 flex items-center justify-center mb-3">
                            <i class="fas fa-calendar-day text-xl text-[#7A2B4A]/50"></i>
                        </div>
                        <p class="font-semibold text-sm text-[#1C1C1C]">No upcoming class</p>
                        <p class="text-xs text-[#1C1C1C]/50 mt-1">Book your first class now!</p>
                        <a href="{{ route('member.book') }}" class="inline-flex items-center gap-1.5 mt-3 px-5 py-2 rounded-full bg-[#EE4E8B] text-white font-nord font-bold text-xs hover:bg-[#7A2B4A] transition-all">
                            Book Now <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- RIGHT: Activity Stats --}}
                <div class="lg:col-span-5 bg-white rounded-2xl border border-[#F4C9DF]/40 p-5 md:p-6 flex flex-col">
                    <h3 class="font-nord font-bold text-sm text-[#7A2B4A] flex items-center gap-2 mb-5">
                        <i class="fas fa-chart-line text-[#EE4E8B]"></i> Activity Stats
                    </h3>

                    @php
                        $streakDays = min($attendances->count(), 30);
                        $streakDisplay = min($streakDays, 99);
                    @endphp

                    <div class="flex flex-col items-center justify-center flex-1">
                        {{-- Circular Progress Ring --}}
                        <div class="relative w-[160px] h-[160px] mb-4">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#F4C9DF" stroke-width="6"/>
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#EE4E8B" stroke-width="6"
                                        stroke-linecap="round"
                                        stroke-dasharray="283"
                                        stroke-dashoffset="{{ 283 - (283 * min($streakDays / 30, 1)) }}"
                                        style="transition: stroke-dashoffset 1.5s ease;"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-nord text-4xl font-black text-[#1C1C1C]">{{ str_pad($streakDisplay, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] font-semibold text-[#1C1C1C]/50 uppercase tracking-wider mt-0.5">DAY STREAK</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 w-full mt-2 pt-4 border-t border-[#F4C9DF]/40">
                            <div class="text-center">
                                <p class="font-nord font-black text-lg text-[#1C1C1C]">{{ $remainingClasses }}</p>
                                <p class="text-[10px] text-[#1C1C1C]/50 font-semibold uppercase tracking-wider">Booking</p>
                            </div>
                            <div class="text-center">
                                <p class="font-nord font-black text-lg text-[#1C1C1C]">{{ $remainingQuota }}</p>
                                <p class="text-[10px] text-[#1C1C1C]/50 font-semibold uppercase tracking-wider">Quota</p>
                            </div>
                            <div class="text-center">
                                <p class="font-nord font-black text-lg text-[#1C1C1C]">{{ $attendances->count() }}</p>
                                <p class="text-[10px] text-[#1C1C1C]/50 font-semibold uppercase tracking-wider">Visits</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Spacer for bottom padding --}}
        <div class="h-8"></div>

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

            <div style="position: absolute; top: 0; left: 0; right: 0; height: 6px; background-color: #EE4E8B; z-index: 2;"></div>

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
                        QR Active &mdash; Ready to Scan
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
     SIDEBAR TOGGLE (Mobile)
     =========================================================== --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');
        if (!sidebar) return;

        const willOpen = !sidebar.classList.contains('active') && !sidebar.classList.contains('open');
        sidebar.classList.toggle('active');
        sidebar.classList.toggle('open');

        if (willOpen) {
            document.body.classList.add('sidebar-open');
            if (hamburger) hamburger.style.display = 'none';
            if (window.innerWidth <= 768) document.body.style.overflow = 'hidden';
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
        } else {
            document.body.classList.remove('sidebar-open');
            if (hamburger) { hamburger.style.display = ''; const icon = hamburger.querySelector('i'); if (icon) icon.classList.add('fa-bars'); }
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
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
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>
