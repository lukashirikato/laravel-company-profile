@extends('layouts.app')

@section('content')
@php
    $totalAttendances = $totalAttendances ?? 0;
    $monthAttendances = $monthAttendances ?? 0;
    $weekAttendances = $weekAttendances ?? 0;
    $totalDurationLabel = $totalDurationLabel ?? '0 menit';
    $averageDurationLabel = $averageDurationLabel ?? '-';
    $lastAttendanceLabel = $lastAttendanceLabel ?? 'Belum ada aktivitas';
    $activeAttendance = $activeAttendance ?? null;
    $member = auth('customer')->user();
    $memberQrData = $member?->qr_token && $member?->qr_active ? $member->getQRData() : null;
@endphp

<style>
    /* =====================================================
       ATTENDANCE PAGE — GLOBAL TYPOGRAPHY RULES (desain.md)
       H1  : 30px / weight 700   → desktop: 34px
       H2  : 24px / weight 600   → desktop: 28px
       H3  : 20px / weight 600   → desktop: 24px
       Body: 16px / weight 400
       Desc: 15px / weight 400
       Btn : 14px / weight 500
       Label (form): 14px / weight 500
       Input: 16px / weight 400
       Table text: 14px / weight 400
       Table header: 14px / weight 600
       Caption: 12px / weight 400
       line-height: 1.5–1.6
    ===================================================== */

    /* Base line-height for readability */
    .attendance-page { line-height: 1.55; }

    /* H1 — Judul utama halaman */
    .att-h1 {
        font-size: 30px;
        font-weight: 700;
        line-height: 1.15;
    }
    @media (min-width: 768px) {
        .att-h1 { font-size: 34px; }
    }

    /* H2 — Judul section */
    .att-h2 {
        font-size: 24px;
        font-weight: 600;
        line-height: 1.25;
    }
    @media (min-width: 768px) {
        .att-h2 { font-size: 28px; }
    }

    /* H3 — Judul card / sub-section */
    .att-h3 {
        font-size: 20px;
        font-weight: 600;
        line-height: 1.3;
    }
    @media (min-width: 768px) {
        .att-h3 { font-size: 24px; }
    }

    /* Body utama */
    .att-body {
        font-size: 16px;
        font-weight: 400;
        line-height: 1.55;
    }

    /* Deskripsi / teks sekunder */
    .att-desc {
        font-size: 15px;
        font-weight: 400;
        line-height: 1.55;
    }

    /* Tombol */
    .att-btn {
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
    }

    /* Label (chip, badge) */
    .att-label {
        font-size: 14px;
        font-weight: 500;
    }

    /* Caption / info kecil — minimum 12px */
    .att-caption {
        font-size: 12px;
        font-weight: 400;
        line-height: 1.5;
        letter-spacing: 0.06em;
    }

    /* Caption uppercase untuk label kecil */
    .att-caption-upper {
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    /* Angka metrik besar pada stat cards */
    .att-metric-num {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.1;
    }
    @media (min-width: 768px) {
        .att-metric-num { font-size: 36px; }
    }

    /* Angka kecil di hero quick-stats */
    .att-quick-num {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.1;
    }

    /* Input teks */
    .att-input {
        font-size: 16px;
        font-weight: 400;
    }

    /* Table header */
    .att-th {
        font-size: 14px;
        font-weight: 600;
    }

    /* Table body text */
    .att-td {
        font-size: 14px;
        font-weight: 400;
    }

    /* Chip value (check-in, check-out, durasi, metode) */
    .att-chip-val {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.4;
    }

    /* =====================================================
       LAYOUT & VISUAL STYLES (unchanged from original)
    ===================================================== */
    .attendance-page { --att-plum:#762645; --att-rose:#e94683; background: radial-gradient(circle at 9% 6%, rgba(233,70,131,.18), transparent 18rem), radial-gradient(circle at 96% 1%, rgba(220,233,189,.58), transparent 22rem), linear-gradient(180deg, #fffaf7 0%, #fff6ef 46%, #ffffff 100%); }
    .attendance-shell { width:100%; max-width: 1180px; margin: 0 auto; }
    .attendance-hero { background: linear-gradient(135deg, rgba(118,38,69,.98) 0%, rgba(160,48,92,.98) 56%, rgba(233,70,131,.96) 100%); box-shadow: 0 24px 64px rgba(118,38,69,.22); }
    .attendance-hero:after { content:''; position:absolute; right:-5rem; bottom:-8rem; width:20rem; height:20rem; border-radius:999px; background:rgba(255,255,255,.12); }
    .attendance-panel { border:1px solid rgba(118,38,69,.11); background:rgba(255,255,255,.86); box-shadow:0 18px 58px rgba(118,38,69,.09); backdrop-filter:blur(18px); }
    .attendance-metric { background:linear-gradient(180deg, rgba(255,255,255,.96), rgba(255,248,242,.9)); border:1px solid rgba(118,38,69,.10); }
    .attendance-quick { border:1px solid rgba(255,255,255,.24); background:rgba(255,255,255,.13); }
    .attendance-chip { border:1px solid rgba(118,38,69,.10); background:rgba(255,255,255,.74); }
    .attendance-row { transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
    .attendance-row:hover { transform: translateY(-2px); border-color: rgba(233,70,131,.30); box-shadow: 0 18px 44px rgba(118,38,69,.09); }
    .qr-attendance-modal { position:fixed; inset:0; z-index:80; display:none; align-items:center; justify-content:center; padding:1rem; background:rgba(25,21,23,.72); backdrop-filter:blur(14px); }
    .qr-attendance-modal.open { display:flex; }
    .qr-attendance-card { width:min(92vw,25rem); background:#fffaf2; border-radius:2rem; padding:1.25rem; box-shadow:0 28px 90px rgba(25,21,23,.35); }
    .qr-attendance-box { position:relative; background:#fff; border:1px solid rgba(118,38,69,.12); border-radius:1.5rem; padding:1rem; overflow:hidden; }
    .qr-attendance-box:before { content:''; position:absolute; inset:1rem; border-radius:1.15rem; background:linear-gradient(135deg, rgba(255,229,240,.65), rgba(255,248,238,.78)); }
    .qr-attendance-placeholder { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(233,70,131,.35); }
    .qr-attendance-placeholder svg { width:5rem; height:5rem; }
    .qr-attendance-box img { position:relative; z-index:1; width:100%; max-width:260px; height:auto; margin:0 auto; display:block; border-radius:1rem; background:#fff; }
    .attendance-empty-icon { background:linear-gradient(135deg, #fff8ee 0%, #ffe5f0 100%); box-shadow:0 18px 42px rgba(233,70,131,.16); }
    .attendance-empty-icon i { display:block; color:#e94683; }
    .qr-close-button { border:1px solid rgba(118,38,69,.12); box-shadow:0 10px 24px rgba(118,38,69,.10); }
    .qr-close-button:hover { background:#762645; color:#fff; transform:translateY(-1px); }
    .qr-close-icon { color:currentColor; font-size:1.85rem; font-weight:900; line-height:1; }
    .attendance-menu-lines { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; }
    .attendance-menu-lines span { width:18px; height:3px; border-radius:999px; background:#fff; box-shadow:0 0 0 1px rgba(255,255,255,.08); }
    .attendance-timeline:before { content:''; position:absolute; left:1.15rem; top:3rem; bottom:-1.25rem; width:1px; background:linear-gradient(180deg, rgba(233,70,131,.36), rgba(233,70,131,0)); }
    .attendance-row:last-child .attendance-timeline:before { display:none; }
    @keyframes attendanceRise { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    .attendance-animate { animation: attendanceRise .42s ease both; }

    @media (max-width: 768px) {
        .hamburger-btn { display:flex !important; }
        .attendance-page main { width:100%; }
        .attendance-hero { border-radius:1.65rem; }
        .attendance-hero:after { width:12rem; height:12rem; right:-4rem; bottom:-5rem; }
        .attendance-mobile-tight { padding-top:4.25rem; }
        .attendance-timeline:before { left:1rem; }
    }

    /* Desktop layout */
    @media (min-width: 1024px) {
        .attendance-page { align-items:stretch; }
        .attendance-page .ftm-sidebar {
            position:fixed;
            inset:0 auto 0 0;
            z-index:30;
        }
        .attendance-page .main-content {
            width:calc(100% - 13.5rem);
            margin-left:13.5rem;
            overflow:visible;
        }
        .attendance-shell {
            padding-left:2.25rem !important;
            padding-right:2.25rem !important;
        }
        .attendance-hero {
            position:relative;
            top:auto;
            z-index:1;
        }
    }

    @media (min-width: 1280px) {
        .attendance-shell { max-width: 1240px; }
    }

    /* Standarisasi spacing antar section utama */
    .attendance-shell > section {
        margin-top: 2.5rem;
    }
    .attendance-shell > section:first-child {
        margin-top: 0;
    }
</style>

<div class="attendance-page">
    @include('partials.member-sidebar')

    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()" aria-label="Open menu">
        <span class="attendance-menu-lines" aria-hidden="true"><span></span><span></span><span></span></span>
    </button>

<main class="main-content">
        <div class="attendance-shell mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-10">

            {{-- ================================================
                 HERO SECTION
            ================================================ --}}
            <section class="attendance-hero attendance-mobile-tight relative overflow-hidden p-5 sm:p-8 lg:p-10 text-white attendance-animate">
                <div class="relative z-10 grid gap-5 lg:grid-cols-[1fr_21rem] lg:items-end">
                    <div>
                        {{-- Badge label --}}
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/14 px-3 py-1.5 ring-1 ring-white/20" style="font-size:12px; font-weight:600; letter-spacing:0.14em; text-transform:uppercase; color:rgba(255,255,255,0.88);">
                            <i class="ri-calendar-check-line"></i> Attendance Center
                        </div>

                        {{-- H1 — Judul utama halaman: 30px mobile, 34px desktop, weight 700 --}}
                        <h1 class="att-h1 mt-3 max-w-2xl !text-white">Pusat kehadiran latihan member.</h1>

                        {{-- Deskripsi: 15px, weight 400 --}}
                        <p class="att-desc mt-2 max-w-xl text-white/78">Pantau check-in, check-out, durasi, dan riwayat scan QR setiap sesi latihan Anda.</p>

                        {{-- Quick stats grid --}}
                        <div class="mt-5 grid grid-cols-3 gap-2 sm:max-w-xl sm:gap-3">
                            <div class="attendance-quick rounded-2xl p-3">
                                <p class="att-caption-upper text-white/58">Total</p>
                                <p class="att-quick-num mt-1 text-white">{{ $totalAttendances }}</p>
                            </div>
                            <div class="attendance-quick rounded-2xl p-3">
                                <p class="att-caption-upper text-white/58">Bulan</p>
                                <p class="att-quick-num mt-1 text-white">{{ $monthAttendances }}</p>
                            </div>
                            <div class="attendance-quick rounded-2xl p-3">
                                <p class="att-caption-upper text-white/58">Minggu</p>
                                <p class="att-quick-num mt-1 text-white">{{ $weekAttendances }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right card: aktivitas terakhir --}}
                    <div class="rounded-[1.35rem] bg-white p-3 text-dark shadow-2xl shadow-black/10 sm:p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-light-pink text-secondary">
                                <i class="ri-pulse-line text-xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="att-caption-upper text-dark/45">Aktivitas terakhir</p>
                                {{-- H3 style untuk nama aktivitas --}}
                                <p class="att-h3 mt-1 truncate text-dark">{{ $lastAttendanceLabel }}</p>
                                <p class="att-caption mt-1 text-dark/50">Rata-rata durasi {{ $averageDurationLabel }}</p>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            {{-- Tombol: 14px, weight 500 --}}
                            <button type="button" onclick="openAttendanceQR()" class="att-btn inline-flex items-center justify-center gap-2 rounded-2xl bg-secondary px-3 py-3 text-white transition hover:bg-dark"><i class="ri-qr-scan-2-line"></i> Scan QR</button>
                            <a href="{{ route('member.dashboard') }}" class="att-btn inline-flex items-center justify-center gap-2 rounded-2xl bg-cream px-3 py-3 text-secondary transition hover:bg-light-pink"><i class="ri-home-5-line"></i> Dashboard</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================================================
                 ACTIVE ATTENDANCE BANNER
            ================================================ --}}
            @if($activeAttendance)
                @php $activeProgram = $activeAttendance->program ?? $activeAttendance->schedule?->classModel?->name ?? 'General Fitness'; @endphp
                <section class="rounded-[1.5rem] bg-[#176a54] p-4 sm:p-5 text-white shadow-xl shadow-green-900/10 attendance-animate">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                                <span class="relative flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-70"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-white"></span>
                                </span>
                            </div>
                            <div>
                                <p class="att-caption-upper text-white/60">Sedang check-in</p>
                                {{-- H2 untuk nama program aktif --}}
                                <h2 class="att-h2 !text-white">{{ $activeProgram }}</h2>
                                <p class="att-desc mt-1 text-white/75">Masuk {{ $activeAttendance->check_in_at?->format('H:i') ?? '-' }}. Scan QR untuk check-out.</p>
                            </div>
                        </div>
                        {{-- Tombol: 14px, weight 500 --}}
                        <button type="button" onclick="openAttendanceQR()" class="att-btn inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-accent transition hover:bg-cream">Check-out <i class="ri-arrow-right-line"></i></button>
                    </div>
                </section>
            @endif

            {{-- ================================================
                 METRIC CARDS
            ================================================ --}}
            <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.04s">
                    <div class="flex items-center justify-between gap-3">
                        <p class="att-caption-upper text-dark/45">Total Visit</p>
                        <i class="ri-footprint-line text-secondary/70"></i>
                    </div>
                    <p class="att-metric-num mt-2 text-dark">{{ $totalAttendances }}</p>
                    <p class="att-caption mt-1 text-dark/55">Semua sesi tercatat</p>
                </div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.08s">
                    <div class="flex items-center justify-between gap-3">
                        <p class="att-caption-upper text-dark/45">Bulan Ini</p>
                        <i class="ri-calendar-event-line text-secondary/70"></i>
                    </div>
                    <p class="att-metric-num mt-2 text-secondary">{{ $monthAttendances }}</p>
                    <p class="att-caption mt-1 text-dark/55">Konsistensi bulanan</p>
                </div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.12s">
                    <div class="flex items-center justify-between gap-3">
                        <p class="att-caption-upper text-dark/45">Minggu Ini</p>
                        <i class="ri-fire-line text-secondary/70"></i>
                    </div>
                    <p class="att-metric-num mt-2 text-accent">{{ $weekAttendances }}</p>
                    <p class="att-caption mt-1 text-dark/55">Progress mingguan</p>
                </div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.16s">
                    <div class="flex items-center justify-between gap-3">
                        <p class="att-caption-upper text-dark/45">Durasi</p>
                        <i class="ri-timer-flash-line text-secondary/70"></i>
                    </div>
                    <p class="att-metric-num mt-2 text-dark">{{ $totalDurationLabel }}</p>
                    <p class="att-caption mt-1 text-dark/55">Rata-rata {{ $averageDurationLabel }}</p>
                </div>
            </section>

            {{-- ================================================
                 ATTENDANCE LOG PANEL
            ================================================ --}}
            <section class="attendance-panel rounded-[1.75rem] overflow-hidden attendance-animate" style="animation-delay:.2s">

                {{-- Panel header --}}
                <div class="border-b border-light-pink/60 bg-white/76 p-4 sm:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            {{-- Caption label --}}
                            <p class="att-caption-upper text-secondary/70">Attendance Log</p>
                            {{-- H2 — Judul section --}}
                            <h2 class="att-h2 mt-1 text-dark">Riwayat Attendance</h2>
                            {{-- Deskripsi: 15px, weight 400 --}}
                            <p class="att-desc mt-1 text-dark/55">Filter kelas, tanggal, status, paket, atau metode scan.</p>
                        </div>
                        {{-- Search input: 16px, weight 400 --}}
                        <div class="relative w-full lg:w-96">
                            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-dark/35"></i>
                            <input
                                id="attendanceSearch"
                                type="search"
                                placeholder="Cari attendance..."
                                class="att-input w-full rounded-2xl border border-light-pink/80 bg-white py-3.5 pl-11 pr-4 text-dark placeholder:text-dark/35 focus:border-secondary focus:outline-none focus:ring-4 focus:ring-light-pink/40"
                                style="font-size:16px; font-weight:400;"
                            >
                        </div>
                    </div>
                </div>

                @if($attendances->count())
                    <div class="p-3 sm:p-6 space-y-3 sm:space-y-4">
                        @foreach($attendances as $attendance)
                            @php
                                $isActive = is_null($attendance->check_out_at);
                                $program = $attendance->program ?? $attendance->schedule?->classModel?->name ?? 'General Fitness';
                                $date = $attendance->check_in_at ?? $attendance->created_at;
                                $status = $isActive ? 'Aktif' : ucfirst($attendance->attendance_status ?? 'Selesai');
                                $duration = $isActive ? 'Berjalan' : $attendance->getShortDuration();
                                $method = strtoupper($attendance->check_in_type ?? 'SYS');
                                $package = $attendance->order?->package?->name ?? 'Paket tidak tersedia';
                            @endphp
                            <article
                                class="attendance-row rounded-[1.35rem] border border-light-pink/60 bg-white p-4 sm:p-5"
                                data-search="{{ strtolower($program.' '.$status.' '.$method.' '.$package.' '.$date?->format('d M Y l')) }}"
                            >
                                <div class="flex gap-3 sm:gap-4">
                                    {{-- Status icon --}}
                                    <div class="attendance-timeline relative shrink-0">
                                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl {{ $isActive ? 'bg-secondary text-white' : 'bg-grounded-green/40 text-accent' }}">
                                            <i class="{{ $isActive ? 'ri-loader-4-line' : 'ri-check-line' }} text-lg"></i>
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        {{-- Row header: tanggal + nama kelas + badge status --}}
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                {{-- Tanggal: caption 12px --}}
                                                <p class="att-caption-upper text-dark/42">{{ $date?->format('l, d M Y') ?? '-' }}</p>
                                                {{-- H3 — Nama kelas: 20px mobile, 24px desktop --}}
                                                <h3 class="att-h3 mt-1 truncate text-dark">{{ $program }}</h3>
                                                {{-- Nama paket: desc 15px --}}
                                                <p class="att-desc mt-1 truncate text-dark/50">{{ $package }}</p>
                                            </div>
                                            {{-- Status badge: 12px, weight 600 --}}
                                            <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 {{ $isActive ? 'bg-light-pink text-secondary' : 'bg-grounded-green/40 text-accent' }}" style="font-size:12px; font-weight:600;">
                                                <i class="{{ $isActive ? 'ri-time-line' : 'ri-checkbox-circle-line' }}"></i>{{ $status }}
                                            </span>
                                        </div>

                                        {{-- Detail chips: check-in, check-out, durasi, metode --}}
                                        <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                                            <div class="attendance-chip rounded-2xl p-3">
                                                <p class="att-caption-upper text-dark/40">Check-in</p>
                                                <p class="att-chip-val mt-1 text-dark">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p>
                                            </div>
                                            <div class="attendance-chip rounded-2xl p-3">
                                                <p class="att-caption-upper text-dark/40">Check-out</p>
                                                <p class="att-chip-val mt-1 {{ $isActive ? 'text-secondary' : 'text-dark' }}">{{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}</p>
                                            </div>
                                            <div class="attendance-chip rounded-2xl p-3">
                                                <p class="att-caption-upper text-dark/40">Durasi</p>
                                                <p class="att-chip-val mt-1 text-dark">{{ $duration }}</p>
                                            </div>
                                            <div class="attendance-chip rounded-2xl p-3">
                                                <p class="att-caption-upper text-dark/40">Metode</p>
                                                <p class="att-chip-val mt-1 text-dark">{{ $method }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Empty search state --}}
                    <div id="attendanceEmptySearch" class="hidden px-6 py-14 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-cream text-secondary">
                            <i class="ri-search-eye-line text-3xl"></i>
                        </div>
                        <h3 class="att-h3 text-dark">Data tidak ditemukan</h3>
                        <p class="att-desc mt-2 text-dark/55">Coba kata kunci lain, misalnya nama kelas atau tanggal.</p>
                    </div>

                    {{-- Pagination --}}
                    <div class="border-t border-light-pink/60 bg-cream/50 px-4 py-4 sm:px-6 sm:py-5">{{ $attendances->links() }}</div>

                @else
                    {{-- Empty state --}}
                    <div class="px-6 py-14 sm:py-20 text-center">
                        <div class="attendance-empty-icon mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-[1.75rem]">
                            <i class="ri-qr-code-line text-4xl"></i>
                        </div>
                        <h3 class="att-h3 text-dark">Belum ada attendance</h3>
                        <p class="att-desc mx-auto mt-2 max-w-md text-dark/55">Tampilkan QR member Anda ke staff untuk check-in. Setelah dipindai, aktivitas latihan akan tersimpan otomatis di sini.</p>
                        @if($memberQrData)
                            <button type="button" onclick="openAttendanceQR()" class="att-btn mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 text-white transition hover:bg-dark">Mulai Check-in <i class="ri-qr-code-line"></i></button>
                        @else
                            <a href="{{ route('member.account') }}" class="att-btn mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 text-white transition hover:bg-dark">Aktifkan QR Member <i class="ri-arrow-right-line"></i></a>
                        @endif
                    </div>
                @endif
            </section>
        </div>
    </main>
</div>

{{-- ================================================
     QR CODE MODAL (always rendered; content depends on whether QR is active)
================================================ --}}
<div id="attendanceQrModal" class="qr-attendance-modal" onclick="closeAttendanceQR(event)">
    <div class="qr-attendance-card text-center" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between gap-3 text-left">
            <div>
                {{-- Caption label --}}
                <p class="att-caption-upper text-secondary/70">Member QR Code</p>
                {{-- H3 dalam modal --}}
                <h3 id="attendanceQrModalTitle" class="att-h3 mt-1 text-dark">Scan untuk check-in</h3>
            </div>
            <button
                type="button"
                onclick="closeAttendanceQR()"
                class="qr-close-button flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-light-pink text-secondary transition"
                aria-label="Kembali ke halaman attendance"
            >
                <span class="qr-close-icon" aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="qr-attendance-box mt-5">
            @if($memberQrData)
                <div class="qr-attendance-placeholder" aria-hidden="true" style="display:none">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z" />
                        <path d="M14 14h2v2h-2zM18 14h2M14 18h2M18 18h2v2h-2z" />
                    </svg>
                </div>
                <img id="attendanceQrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($memberQrData) }}&bgcolor=ffffff&color=7A2B4A" alt="QR Code Member">
            @else
                <div class="qr-attendance-placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z" />
                        <path d="M14 14h2v2h-2zM18 14h2M14 18h2M18 18h2v2h-2z" />
                    </svg>
                </div>
                <div class="p-6">
                    <p class="att-body text-dark/60">QR member Anda belum aktif. Anda dapat mengaktifkan QR dari panel akun. Setelah aktif, kembali ke halaman ini dan klik Scan QR lagi untuk menampilkan QR Anda.</p>
                    <div class="mt-4 flex justify-center gap-3">
                        <button type="button" onclick="window.open('{{ route('member.account') }}', '_blank')" class="att-btn bg-secondary px-4 py-2 rounded-2xl text-white">Buka Pengaturan Akun</button>
                        <button type="button" onclick="closeAttendanceQR()" class="att-btn border px-4 py-2 rounded-2xl">Tutup</button>
                    </div>
                </div>
            @endif
        </div>
        {{-- Body text: 16px --}}
        <p id="attendanceQrModalDesc" class="att-body mt-4 text-dark/60">Tunjukkan QR ini ke staff atau trainer. Jangan bagikan QR ke orang lain.</p>
        {{-- Nama member: 15px, weight 600 --}}
        <p id="attendanceQrMemberName" class="mt-2 text-secondary" style="font-size:15px; font-weight:600;">{{ $member?->name }}</p>
    </div>
</div>

<script>
    function openAttendanceQR() { document.getElementById('attendanceQrModal')?.classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeAttendanceQR(event) { if (event && event.target.id !== 'attendanceQrModal') return; document.getElementById('attendanceQrModal')?.classList.remove('open'); document.body.style.overflow = ''; }

    document.getElementById('attendanceSearch')?.addEventListener('input', function (event) {
        const keyword = event.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.attendance-row');
        const empty = document.getElementById('attendanceEmptySearch');
        let visible = 0;
        rows.forEach((row) => {
            const match = (row.dataset.search || row.textContent.toLowerCase()).includes(keyword);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        empty?.classList.toggle('hidden', visible !== 0 || keyword === '');
    });
</script>
@endsection
