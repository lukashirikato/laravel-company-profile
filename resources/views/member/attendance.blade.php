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
.attendance-page {
        --att-plum:  #762645;
        --att-rose:  #E94683;
        --att-cherry:#7A2B4A;
        --att-pink:  #EE4E8B;
        --att-petal: #F4C9DF;
        --att-sage:  #C5D79B;
        --att-green: #1A7A5E;
        --att-ink:   #1C1C1C;
        line-height: 1.55;
        background:
            radial-gradient(circle at 6% 4%, rgba(238,78,139,.10), transparent 20rem),
            radial-gradient(circle at 97% 10%, rgba(197,215,155,.5), transparent 24rem),
            linear-gradient(180deg, #FCF9F2 0%, #FAF3EA 46%, #FFFFFF 100%);
        overflow-x: hidden;
    }
    .attendance-shell { width:100%; max-width: 1220px; margin:0 auto; box-sizing:border-box; }

    /* ===================== TYPOGRAPHY ===================== */
    .att-h1 { font-size:30px; font-weight:700; line-height:1.15; }
    @media (min-width:768px){ .att-h1{ font-size:34px; } }
    .att-h2 { font-size:24px; font-weight:600; line-height:1.25; }
    @media (min-width:768px){ .att-h2{ font-size:28px; } }
    .att-h3 { font-size:20px; font-weight:600; line-height:1.3; }
    @media (min-width:768px){ .att-h3{ font-size:24px; } }
    .att-body { font-size:16px; font-weight:400; line-height:1.55; }
    .att-desc { font-size:15px; font-weight:400; line-height:1.55; }
    .att-btn  { font-size:14px; font-weight:500; line-height:1.5; }
    .att-label{ font-size:14px; font-weight:500; }
    .att-caption { font-size:12px; font-weight:400; line-height:1.5; letter-spacing:0.06em; }
    .att-caption-upper { font-size:11px; font-weight:600; line-height:1.4; letter-spacing:0.14em; text-transform:uppercase; }
    .att-metric-num { font-size:32px; font-weight:700; line-height:1.1; }
    @media (min-width:768px){ .att-metric-num{ font-size:38px; } }
    .att-quick-num { font-size:24px; font-weight:700; line-height:1.1; }
    .att-input { font-size:15px; font-weight:400; }
    .att-th { font-size:13px; font-weight:600; letter-spacing:.05em; text-transform:uppercase; }
    .att-td { font-size:14px; font-weight:400; }
    .att-chip-val { font-size:15px; font-weight:600; line-height:1.4; }
    .att-muted { color: rgba(28,28,28,.55) !important; }
    .att-eyebrow { font-size:11px; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--att-cherry); }

    /* ===================== MASTHEAD ===================== */
    .att-masthead {
        position:relative;
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap:1.25rem;
        flex-wrap:wrap;
        padding:.25rem 0 1.1rem;
    }
    .att-masthead:after {
        content:'';
        position:absolute;
        left:0; right:0; bottom:0;
        height:1px;
        background:linear-gradient(90deg, rgba(122,43,74,.25), rgba(238,78,139,.12), transparent 70%);
    }
    .att-today-chip {
        display:inline-flex;
        align-items:center;
        gap:.6rem;
        padding:.55rem 1rem;
        border-radius:999px;
        border:1px solid rgba(122,43,74,.16);
        background:rgba(255,255,255,.8);
        box-shadow:0 8px 20px rgba(122,43,74,.06);
        white-space:nowrap;
    }
    .att-today-dot { width:.6rem; height:.6rem; border-radius:999px; background:var(--att-pink); box-shadow:0 0 0 4px rgba(238,78,139,.15); }
    .att-today-label{ color:rgba(28,28,28,.45); }
    .att-today-value{ color:var(--att-ink); font-weight:600; }

    /* ===================== HERO / SCAN CTA ===================== */
    .att-hero {
        position:relative;
        overflow:hidden;
        margin-top:1.25rem;
        border-radius:1.75rem;
        padding:1.6rem 1.6rem;
        background:
            radial-gradient(circle at 6% 10%, rgba(255,255,255,.18), transparent 12rem),
            linear-gradient(120deg, rgba(122,43,74,.97) 0%, rgba(160,48,92,.97) 52%, rgba(238,78,139,.96) 100%);
        color:#fff;
        box-shadow:0 24px 64px rgba(118,38,69,.22);
    }
    .att-hero:after { content:''; position:absolute; right:-4rem; bottom:-7rem; width:18rem; height:18rem; border-radius:999px; background:rgba(255,255,255,.09); }
    .att-hero:before { content:''; position:absolute; right:7rem; bottom:-9rem; width:12rem; height:12rem; border-radius:999px; border:1.5rem solid rgba(255,255,255,.07); }
    .att-hero-inner { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }
    .att-hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:rgba(255,255,255,.8); }
    .att-hero h1 { color:#fff !important; }
    .att-hero-desc { font-size:15px; line-height:1.55; color:rgba(255,255,255,.78); max-width:32rem; }
    .att-scan-btn {
        display:inline-flex;
        align-items:center;
        gap:.6rem;
        padding:.95rem 1.75rem;
        border-radius:1.1rem;
        background:#fff;
        color:var(--att-cherry);
        font-weight:700;
        font-size:15px;
        box-shadow:0 12px 32px rgba(28,28,28,.25);
        transition:transform .18s ease, box-shadow .18s ease;
        cursor:pointer;
    }
    .att-scan-btn:hover { transform:translateY(-2px); box-shadow:0 16px 40px rgba(28,28,28,.3); }
    .att-scan-btn i { font-size:1.15rem; color:var(--att-pink); }
    .att-ghost-btn {
        display:inline-flex;
        align-items:center;
        gap:.5rem;
        padding:.85rem 1.35rem;
        border-radius:1rem;
        border:1px solid rgba(255,255,255,.32);
        background:rgba(255,255,255,.12);
        color:#fff;
        font-weight:600;
        font-size:14px;
        transition:background .18s ease;
        cursor:pointer;
    }
    .att-ghost-btn:hover { background:rgba(255,255,255,.22); }

    /* ===================== LIVE SESSION ===================== */
    .att-live {
        margin-top:1.25rem;
        border-radius:1.4rem;
        padding:1rem 1.25rem;
        background:linear-gradient(120deg, rgba(26,122,94,.96), rgba(29,90,75,.96));
        color:#fff;
        box-shadow:0 18px 44px rgba(26,122,94,.2);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
        flex-wrap:wrap;
    }
    .att-live-ping { width:.7rem; height:.7rem; border-radius:999px; background:#fff; box-shadow:0 0 0 0 rgba(255,255,255,.7); animation:attPing 1.4s infinite; }
    @keyframes attPing { 0%{ box-shadow:0 0 0 0 rgba(255,255,255,.55);} 70%{ box-shadow:0 0 0 9px rgba(255,255,255,0);} 100%{ box-shadow:0 0 0 0 rgba(255,255,255,0);} }
    .att-live-btn {
        padding:.7rem 1.35rem;
        border-radius:1rem;
        background:#fff;
        color:var(--att-green);
        font-weight:700;
        font-size:14px;
        cursor:pointer;
        transition:transform .18s ease;
    }
    .att-live-btn:hover { transform:translateY(-1px); }

    /* ===================== STATS TILES ===================== */
    .att-stats { margin-top:1.25rem; display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:1rem; }
    @media (min-width:1024px){ .att-stats{ grid-template-columns:repeat(4, minmax(0,1fr)); } }
    .att-tile {
        position:relative;
        border-radius:1.35rem;
        padding:1.1rem 1.25rem;
        background:linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,250,244,.92));
        border:1px solid rgba(122,43,74,.10);
        overflow:hidden;
        transition:transform .18s ease, box-shadow .18s ease;
    }
    .att-tile:hover { transform:translateY(-3px); box-shadow:0 18px 44px rgba(118,38,69,.10); }
    .att-tile .att-tile-accent { position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:4px; }
    .att-tile .att-tile-ic { width:2.6rem; height:2.6rem; border-radius:.85rem; display:flex; align-items:center; justify-content:center; font-size:1.15rem; }
    .att-tile .att-tile-ic i { font-size:1.15rem; }
    .att-tile-caption { margin-top:.85rem; }
    .att-tile-num { margin-top:.15rem; }

    /* ===================== LAYOUT ===================== */
    .att-layout { margin-top:1.5rem; display:grid; grid-template-columns:minmax(0,1fr); gap:1.5rem; }
    @media (min-width:1024px){ .att-layout{ grid-template-columns:minmax(0,1fr) 22rem; align-items:start; } }
    .att-rail { display:flex; flex-direction:column; gap:1.25rem; }
    @media (min-width:1024px){ .att-rail{ position:sticky; top:1.5rem; } }

    .att-card {
        border:1px solid rgba(122,43,74,.11);
        background:rgba(255,255,255,.9);
        box-shadow:0 18px 58px rgba(118,38,69,.08);
        backdrop-filter:blur(18px);
        border-radius:1.6rem;
        overflow:hidden;
    }

    /* ===================== LOG PANEL ===================== */
    .att-log-head {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
        flex-wrap:wrap;
        padding:1.25rem 1.4rem;
        border-bottom:1px solid rgba(244,201,223,.6);
    }
    .att-search-wrap { position:relative; width:100%; }
    @media (min-width:640px){ .att-search-wrap{ width:20rem; } }
    .att-search-wrap i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:rgba(28,28,28,.32); }
    .att-search { width:100%; border:1px solid rgba(244,201,223,.9); background:#fff; border-radius:1rem; padding:.8rem 1rem .8rem 2.6rem; color:var(--att-ink); }
    .att-search:focus { outline:none; border-color:var(--att-pink); box-shadow:0 0 0 4px rgba(238,78,139,.12); }

    .att-rowlist { padding:.9rem; display:flex; flex-direction:column; gap:.75rem; }
    @media (min-width:640px){ .att-rowlist{ padding:1.25rem; gap:1rem; } }
    .attendance-row {
        border-radius:1.25rem;
        border:1px solid rgba(244,201,223,.65);
        background:#fff;
        padding:.95rem 1rem;
        position:relative;
        overflow:hidden;
        transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .attendance-row:before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; }
    .attendance-row.is-done:before { background:linear-gradient(180deg, var(--att-sage), var(--att-green)); }
    .attendance-row.is-active:before { background:linear-gradient(180deg, var(--att-pink), var(--att-cherry)); }
    .attendance-row:hover { transform:translateY(-2px); border-color:rgba(238,78,139,.4); box-shadow:0 18px 44px rgba(118,38,69,.09); }
    @media (min-width:640px){ .attendance-row{ display:grid; grid-template-columns:7.5rem minmax(0,1fr); gap:1.1rem; align-items:flex-start; } }
    .att-row-date { padding-left:.5rem; }
    @media (min-width:640px){ .att-row-date{ padding-left:0; border-right:1px dashed rgba(122,43,74,.15); } }
    .att-row-time { font-weight:700; font-size:15px; color:var(--att-ink); }
    .att-row-checkin small { display:block; color:rgba(28,28,28,.45); font-size:11.5px; }
    .att-row-checkin-date { font-weight:500; font-size:13px !important; color:rgba(28,28,28,.6) !important; text-transform:capitalize; letter-spacing:0; }
    .att-row-body { min-width:0; }
    .att-row-title { display:flex; align-items:flex-start; justify-content:space-between; gap:.75rem; flex-wrap:wrap; }
    .att-badge { display:inline-flex; align-items:center; gap:.4rem; border-radius:999px; padding:.4rem .85rem; font-size:11.5px; font-weight:700; letter-spacing:.02em; }
    .att-badge-done { background:rgba(26,122,94,.12); color:var(--att-green); }
    .att-badge-active{ background:rgba(238,78,139,.14); color:var(--att-rose); }
    .att-chipgrid { margin-top:.9rem; display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:.55rem; }
    @media (min-width:480px){ .att-chipgrid{ grid-template-columns:repeat(4, minmax(0,1fr)); } }
    .att-chip { border-radius:.9rem; padding:.65rem .8rem; background:rgba(252,249,242,.85); border:1px solid rgba(122,43,74,.08); min-width:0; }
    .att-chip .att-caption-upper { color:rgba(28,28,28,.42); font-size:10px; }
    .att-chip .att-chip-val { font-size:14.5px; font-weight:600; color:var(--att-ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    /* ===================== RAIL CARDS ===================== */
    .att-rail-card { border:1px solid rgba(122,43,74,.11); background:rgba(255,255,255,.9); box-shadow:0 18px 58px rgba(118,38,69,.08); backdrop-filter:blur(18px); border-radius:1.5rem; padding:1.25rem; }
    .att-qr-mini { display:flex; align-items:center; gap:1rem; }
    .att-qr-box { width:3.4rem; height:3.4rem; flex-shrink:0; border-radius:1rem; background:linear-gradient(135deg, var(--att-cherry), var(--att-pink)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.4rem; }
    .att-qr-mini-btn { margin-top:1rem; width:100%; display:inline-flex; align-items:center; justify-content:center; gap:.5rem; padding:.85rem 1rem; border-radius:1rem; background:linear-gradient(120deg, var(--att-cherry), var(--att-rose)); color:#fff; font-weight:700; font-size:14px; box-shadow:0 10px 26px rgba(118,38,69,.24); cursor:pointer; transition:transform .18s ease; }
    .att-qr-mini-btn:hover { transform:translateY(-1px); }

    /* ---- Weekly rhythm ---- */
    .att-rhythm-head { display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin-bottom:1rem; }
    .att-rhythm-grid { display:flex; align-items:flex-end; gap:.5rem; height:11rem; }
    .att-rhythm-col { flex:1 1 0; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; gap:.55rem; height:100%; }
    .att-rhythm-bar-wrap { width:100%; max-width:2rem; height:8.5rem; display:flex; align-items:flex-end; justify-content:center; border-radius:.55rem; background:rgba(122,43,74,.05); overflow:hidden; }
    .att-rhythm-bar { width:100%; border-radius:.55rem; background:linear-gradient(180deg, var(--att-pink), var(--att-cherry)); transition:height .5s ease; }
    .att-rhythm-bar.hot { background:linear-gradient(180deg, var(--att-sage), var(--att-green)); }
    .att-rhythm-bar.empty { background:rgba(122,43,74,.14); height:4px !important; }
    .att-rhythm-day { font-size:10.5px; font-weight:700; letter-spacing:.04em; color:rgba(28,28,28,.5); text-transform:uppercase; }
    .att-rhythm-day.today { color:var(--att-rose); }
    .att-rhythm-day .att-rhythm-d { display:block; font-weight:400; font-size:10px; color:rgba(28,28,28,.38); }

    /* ---- Last activity ---- */
    .att-last-ic { width:2.8rem; height:2.8rem; flex-shrink:0; border-radius:1rem; background:rgba(244,201,223,.6); color:var(--att-cherry); display:flex; align-items:center; justify-content:center; font-size:1.2rem; }

    /* ---- Empty state ---- */
    .att-empty { padding:2.5rem 1.4rem; text-align:center; }
    .att-empty .att-empty-ic { width:4rem; height:4rem; margin:0 auto 1rem; border-radius:1.25rem; background:rgba(244,201,223,.45); display:flex; align-items:center; justify-content:center; color:var(--att-cherry); font-size:1.5rem; }
    .att-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }

    /* ===================== QR MODAL ===================== */
    .qr-attendance-modal { position:fixed; inset:0; z-index:80; display:none; align-items:center; justify-content:center; padding:1rem; background:rgba(25,21,23,.72); backdrop-filter:blur(14px); }
    .qr-attendance-modal.open { display:flex; }
    .qr-attendance-card { width:min(92vw,25rem); background:#fffaf2; border-radius:2rem; padding:1.25rem; box-shadow:0 28px 90px rgba(25,21,23,.35); }
    .qr-attendance-box { position:relative; background:#fff; border:1px solid rgba(118,38,69,.12); border-radius:1.5rem; padding:1rem; overflow:hidden; }
    .qr-attendance-box:before { content:''; position:absolute; inset:1rem; border-radius:1.15rem; background:linear-gradient(135deg, rgba(255,229,240,.65), rgba(255,248,238,.78)); }
    .qr-attendance-placeholder { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(233,70,131,.35); }
    .qr-attendance-placeholder svg { width:5rem; height:5rem; }
    .qr-attendance-box img { position:relative; z-index:1; width:100%; max-width:260px; height:auto; margin:0 auto; display:block; border-radius:1rem; background:#fff; }
    /* Empty state illustration */
    .attendance-empty-art { width:210px; max-width:78%; height:auto; display:block; filter:drop-shadow(0 20px 38px rgba(233,70,131,.18)); }
    .attendance-empty-art .att-scan { transform-box:fill-box; transform-origin:center; animation:attScanSweep 2.6s ease-in-out infinite; }
    @keyframes attScanSweep {
        0%, 100% { transform:translateY(-14px); opacity:.25; }
        50%      { transform:translateY(14px);  opacity:.9;  }
    }
    @media (prefers-reduced-motion: reduce) {
        .attendance-empty-art .att-scan { animation:none; opacity:.6; }
    }
    .qr-close-button { border:1px solid rgba(118,38,69,.12); box-shadow:0 10px 24px rgba(118,38,69,.10); }
    .qr-close-button:hover { background:#762645; color:#fff; transform:translateY(-1px); }
    .qr-close-icon { color:currentColor; font-size:1.85rem; font-weight:900; line-height:1; }
    .attendance-menu-lines { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; }
    .attendance-menu-lines span { width:18px; height:3px; border-radius:999px; background:#fff; box-shadow:0 0 0 1px rgba(255,255,255,.08); }
    @keyframes attendanceRise { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    .attendance-animate { animation: attendanceRise .42s ease both; }

    /* ── Live scan feedback: toast + konfeti (polling admin scan) ── */
    #scanToastHost { position: fixed; right: 1rem; bottom: 1rem; z-index: 120; display: flex; flex-direction: column; gap: .65rem; width: min(92vw, 22rem); pointer-events: none; }
    .scan-toast { pointer-events: auto; display: flex; gap: .75rem; align-items: flex-start; background: #fff; border-radius: 1.1rem; padding: .9rem 1rem; box-shadow: 0 18px 46px rgba(25,21,23,.22); border: 1px solid rgba(118,38,69,.12); transform: translateY(14px); opacity: 0; transition: transform .32s cubic-bezier(.2,.8,.2,1), opacity .32s; }
    .scan-toast.show { transform: translateY(0); opacity: 1; }
    .scan-toast.hide { transform: translateY(14px); opacity: 0; }
    .scan-toast .st-icon { flex: 0 0 auto; width: 2.1rem; height: 2.1rem; border-radius: .8rem; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; }
    .scan-toast.t-in .st-icon { background: #176a54; }
    .scan-toast.t-out .st-icon { background: #762645; }
    .scan-toast.t-info .st-icon { background: #E94683; }
    .scan-toast .st-title { font-size: 14px; font-weight: 700; color: #1C1C1C; }
    .scan-toast .st-msg { font-size: 13px; color: rgba(28,28,28,.62); margin-top: 2px; }
    .scan-toast .st-close { margin-left: auto; background: none; border: 0; color: rgba(28,28,28,.4); cursor: pointer; font-size: 16px; line-height: 1; padding: 2px; }
    .confetti-piece { position: fixed; z-index: 130; width: 9px; height: 14px; top: 38%; left: 50%; border-radius: 2px; pointer-events: none; opacity: 0; animation: confettiBurst 2.2s cubic-bezier(.15,.6,.3,1) forwards; }
    @keyframes confettiBurst { 0% { opacity: 1; transform: translate(0,0) rotate(0deg); } 100% { opacity: 0; transform: translate(var(--cx, 0), 68vh) rotate(var(--cr, 360deg)); } }

    @media (max-width: 768px) {
        .hamburger-btn { display:flex !important; }
        .attendance-page main { width:100%; }
        .att-hero { border-radius:1.5rem; }
        .att-hero:after { width:12rem; height:12rem; right:-3rem; bottom:-5rem; }
        .att-hero:before { display:none; }
        .attendance-mobile-tight { padding-top:4.5rem; }
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
        .att-hero {
            position:relative;
            top:auto;
            z-index:1;
        }
    }

    @media (min-width: 1280px) {
        .attendance-shell { max-width: 1240px; }
    }

    /* Standarisasi spacing antar section utama */
    .attendance-shell > .att-section {
        margin-top: 1.5rem;
    }
    .attendance-shell > .att-section:first-child {
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
                 MASTHEAD
            ================================================ --}}
            <section class="att-section attendance-mobile-tight">
                <div class="att-masthead">
                    <div>
                        <p class="att-eyebrow flex items-center gap-2"><i class="fa-solid fa-calendar-check"></i> Log Kehadiran Member</p>
                        <h1 class="att-h1 mt-2 text-dark">Riwayat Latihan &amp; Consistensi</h1>
                        <p class="att-desc att-caption mt-2 text-dark">Pantau check-in, check-out, durasi, dan ritme latihan Anda di FTM Society.</p>
                    </div>
                    <div class="att-today-chip">
                        <span class="att-today-dot"></span>
                        <span class="att-today-label">Hari ini</span>
                        <span class="att-today-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                </div>
            </section>

            {{-- ================================================
                 HERO / SCAN CTA
            ================================================ --}}
            <section class="att-section">
                <div class="att-hero attendance-animate">
                    <div class="att-hero-inner">
                        <div>
                            <p class="att-hero-eyebrow">FTM SOCIETY &middot; MEMBER</p>
                            <h1 class="att-h1">{{ $activeAttendance ? 'Sedang berlatih sekarang' : 'Siap mulai latihan?' }}</h1>
                            <p class="att-hero-desc mt-2">
                                @if($activeAttendance)
                                    Total <strong>{{ $totalAttendances }}</strong> sesi sudah tercatat. Tunjukkan QR untuk <strong>check-out</strong>.
                                @else
                                    Total <strong>{{ $totalAttendances }}</strong> sesi sudah tercatat. Tunjukkan QR member untuk <strong>check-in</strong> di resepsionis.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('member.dashboard') }}" class="att-ghost-btn"><i class="fa-solid fa-house"></i> Beranda</a>
                            <button type="button" onclick="openAttendanceQR()" class="att-scan-btn"><i class="fa-solid fa-qrcode"></i> Scan QR</button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================================================
                 ACTIVE ATTENDANCE BANNER
            ================================================ --}}
            <div id="activeAttendanceSlot" class="att-section">
                @if($activeAttendance)
                    @php $activeProgram = $activeAttendance->program ?? $activeAttendance->schedule?->classModel?->name ?? 'General Fitness'; @endphp
                    <section id="activeAttendanceBanner" class="att-live attendance-animate">
                        <div class="flex items-center gap-3">
                            <span class="att-live-ping"></span>
                            <div>
                                <p class="att-caption-upper text-white/70">Sedang check-in</p>
                                <h2 class="att-h2 !text-white">{{ $activeProgram }}</h2>
                                <p class="att-desc mt-1 text-white/80">Masuk {{ $activeAttendance->check_in_at?->format('H:i') ?? '-' }}. Scan QR untuk check-out.</p>
                            </div>
                        </div>
                        <button type="button" onclick="openAttendanceQR()" class="att-live-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Check-out</button>
                    </section>
                @endif
            </div>

            <template id="activeBannerTpl">
                <section id="activeAttendanceBanner" class="att-live attendance-animate">
                    <div class="flex items-center gap-3">
                        <span class="att-live-ping"></span>
                        <div>
                            <p class="att-caption-upper text-white/70">Sedang check-in</p>
                            <h2 class="att-h2 !text-white" data-banner-program>__PROGRAM__</h2>
                            <p class="att-desc mt-1 text-white/80" data-banner-time>Masuk __TIME__. Scan QR untuk check-out.</p>
                        </div>
                    </div>
                    <button type="button" onclick="openAttendanceQR()" class="att-live-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Check-out</button>
                </section>
            </template>

                        {{-- ================================================
                 STATS TILES
            ================================================ --}}
            <section class="att-stats">
                    <div class="att-tile attendance-animate" style="animation-delay:.05s">
                        <span class="att-tile-accent" style="background:linear-gradient(180deg,#EE4E8B,#762645);"></span>
                        <div class="att-tile-ic" style="background:rgba(238,78,139,.12); color:var(--att-pink);"><i class="fa-solid fa-shoe-prints"></i></div>
                        <p class="att-tile-caption att-caption-upper text-dark/45">Total Sesi</p>
                        <p class="att-tile-num att-metric-num">{{ $totalAttendances }}</p>
                        <p class="att-caption mt-1 text-dark/50">Semua kunjungan tercatat</p>
                    </div>
                    <div class="att-tile attendance-animate" style="animation-delay:.1s">
                        <span class="att-tile-accent" style="background:linear-gradient(180deg,#F4C9DF,#762645);"></span>
                        <div class="att-tile-ic" style="background:rgba(244,201,223,.55); color:var(--att-cherry);"><i class="fa-solid fa-calendar-days"></i></div>
                        <p class="att-tile-caption att-caption-upper text-dark/45">Bulan Ini</p>
                        <p class="att-tile-num att-metric-num">{{ $monthAttendances }}</p>
                        <p class="att-caption mt-1 text-dark/50">Konsistensi bulanan</p>
                    </div>
                    <div class="att-tile attendance-animate" style="animation-delay:.15s">
                        <span class="att-tile-accent" style="background:linear-gradient(180deg,#1A7A5E,#1D5A4B);"></span>
                        <div class="att-tile-ic" style="background:rgba(26,122,94,.12); color:var(--att-green);"><i class="fa-solid fa-fire-flame-curved"></i></div>
                        <p class="att-tile-caption att-caption-upper text-dark/45">Minggu Ini</p>
                        <p class="att-tile-num att-metric-num">{{ $weekAttendances }}</p>
                        <p class="att-caption mt-1 text-dark/50">Progress mingguan</p>
                    </div>
                    <div class="att-tile attendance-animate" style="animation-delay:.2s">
                        <span class="att-tile-accent" style="background:linear-gradient(180deg,#C5D79B,#1A7A5E);"></span>
                        <div class="att-tile-ic" style="background:rgba(197,215,155,.55); color:#5A7C1F;"><i class="fa-solid fa-stopwatch"></i></div>
                        <p class="att-tile-caption att-caption-upper text-dark/45">Durasi Rata-rata</p>
                        <p class="att-tile-num att-metric-num">{{ $averageDurationLabel }}</p>
                        <p class="att-caption mt-1 text-dark/50">Total {{ $totalDurationLabel }} berlatih</p>
                    </div>
                </section>

{{-- ================================================
                 MAIN GRID: LOG + RAIL
            ================================================ --}}
            <div class="att-layout">

                {{-- LEFT: ACTIVITY LOG --}}
                <section class="att-card attendance-animate" style="animation-delay:.25s">

                    <div class="att-log-head">
                        <div>
                            <p class="att-eyebrow flex items-center gap-2"><i class="fa-solid fa-list-check"></i> Log Absensi</p>
                            <h2 class="att-h2 mt-1 text-dark">Riwayat Kehadiran</h2>
                        </div>
                        <div class="att-search-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input
                                id="attendanceSearch"
                                type="search"
                                placeholder="Cari kelas, paket, tanggal..."
                                class="att-input att-search"
                            >
                        </div>
                    </div>

                    @if($attendances->count())
                        <div class="att-rowlist">
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
                                    class="attendance-row {{ $isActive ? 'is-active' : 'is-done' }}"
                                    data-search="{{ strtolower($program.' '.$status.' '.$method.' '.$package.' '.$date?->format('d M Y l')) }}"
                                >
                                    {{-- Date/time column --}}
                                    <div class="att-row-date">
                                        <div class="att-row-time">{{ $date?->format('H:i') ?? '-' }}</div>
                                        <div class="att-row-checkin mt-0.5">
                                            <small class="att-caption-upper">{{ $date?->format('D') ?? '' }}</small>
                                            <small class="att-row-checkin-date mt-0.5">{{ $date?->format('d M Y') ?? '-' }}</small>
                                        </div>
                                    </div>

                                    {{-- Body --}}
                                    <div class="att-row-body">
                                        <div class="att-row-title">
                                            <div class="min-w-0">
                                                <h3 class="att-h3 text-dark">{{ $program }}</h3>
                                                <p class="att-desc mt-0.5 truncate text-dark/50">{{ $package }}</p>
                                            </div>
                                            <span class="att-badge {{ $isActive ? 'att-badge-active' : 'att-badge-done' }}">
                                                <i class="fa-solid {{ $isActive ? 'fa-bolt' : 'fa-circle-check' }}"></i>{{ $status }}
                                            </span>
                                        </div>

                                        <div class="att-chipgrid">
                                            <div class="att-chip">
                                                <p class="att-caption-upper">Check-in</p>
                                                <p class="att-chip-val mt-0.5">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p>
                                            </div>
                                            <div class="att-chip">
                                                <p class="att-caption-upper">Check-out</p>
                                                <p class="att-chip-val mt-0.5">{{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}</p>
                                            </div>
                                            <div class="att-chip">
                                                <p class="att-caption-upper">Durasi</p>
                                                <p class="att-chip-val mt-0.5">{{ $duration }}</p>
                                            </div>
                                            <div class="att-chip">
                                                <p class="att-caption-upper">Metode</p>
                                                <p class="att-chip-val mt-0.5">{{ $method }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Empty search state --}}
                        <div id="attendanceEmptySearch" class="hidden px-6 py-14 text-center">
                            <div class="att-empty-ic"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <h3 class="att-h3 text-dark">Data tidak ditemukan</h3>
                            <p class="att-desc mt-2 text-dark/55">Coba kata kunci lain, misalnya nama kelas atau tanggal.</p>
                        </div>

                        {{-- Pagination --}}
                        <div class="border-t border-[rgba(244,201,223,.6)] bg-[rgba(252,249,242,.6)] px-4 py-4 sm:px-6 sm:py-5">{{ $attendances->links() }}</div>

                    @else
                        {{-- Empty state --}}
                        <div class="att-empty">
                            <svg class="attendance-empty-art mx-auto mb-5" viewBox="0 0 240 180" role="img" aria-labelledby="attEmptyTitle" xmlns="http://www.w3.org/2000/svg">
                                <title id="attEmptyTitle">Kartu member dengan QR yang siap dipindai di meja resepsionis</title>

                                <circle cx="120" cy="90" r="74" fill="#FFF0F6"/>

                                {{-- Kartu member --}}
                                <rect x="68" y="28" width="104" height="124" rx="16" fill="#FFFFFF" stroke="#F7DCE8" stroke-width="2"/>
                                <path d="M84 28 H156 A16 16 0 0 1 172 44 V50 H68 V44 A16 16 0 0 1 84 28 Z" fill="#EE4E8B"/>
                                <text x="120" y="45" text-anchor="middle" font-family="Nord, Poppins, sans-serif" font-size="10" font-weight="800" letter-spacing="1.6" fill="#FFFFFF">FTM SOCIETY</text>

                                {{-- Modul QR --}}
                                <rect x="88" y="62" width="64" height="64" rx="9" fill="#FFFFFF" stroke="#F0D3E0" stroke-width="1.5"/>
                                <rect x="94" y="68" width="18" height="18" rx="3" fill="#7A2B4A"/>
                                <rect x="99" y="73" width="8" height="8" rx="2" fill="#FFFFFF"/>
                                <rect x="128" y="68" width="18" height="18" rx="3" fill="#7A2B4A"/>
                                <rect x="133" y="73" width="8" height="8" rx="2" fill="#FFFFFF"/>
                                <rect x="94" y="102" width="18" height="18" rx="3" fill="#7A2B4A"/>
                                <rect x="99" y="107" width="8" height="8" rx="2" fill="#FFFFFF"/>
                                <rect x="128" y="102" width="6" height="6" rx="1.5" fill="#7A2B4A"/>
                                <rect x="138" y="102" width="6" height="6" rx="1.5" fill="#7A2B4A"/>
                                <rect x="128" y="112" width="6" height="6" rx="1.5" fill="#7A2B4A"/>
                                <rect x="137" y="111" width="5" height="5" rx="1.5" fill="#7A2B4A"/>
                                <rect x="129" y="119" width="6" height="6" rx="1.5" fill="#7A2B4A"/>

                                {{-- Sinar pemindai --}}
                                <rect class="att-scan" x="82" y="90" width="76" height="6" rx="3" fill="#EE4E8B" opacity=".9"/>

                                {{-- Garis nama pada kartu --}}
                                <rect x="88" y="134" width="64" height="7" rx="3.5" fill="#E9C7D6"/>

                                {{-- Bracket pemindai --}}
                                <path d="M80 66 V54 H92" fill="none" stroke="#EE4E8B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M160 66 V54 H148" fill="none" stroke="#EE4E8B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M80 122 V134 H92" fill="none" stroke="#EE4E8B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M160 122 V134 H148" fill="none" stroke="#EE4E8B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>

                                {{-- Lencana terverifikasi --}}
                                <circle cx="172" cy="126" r="16" fill="#1A7A5E" stroke="#FFFFFF" stroke-width="3"/>
                                <path d="M165 126 l5 5 l9 -10" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h3 class="att-h3 text-dark">Belum ada attendance</h3>
                            <p class="att-desc mx-auto mt-2 max-w-md text-dark/55">Tampilkan QR member Anda ke staff untuk check-in. Setelah dipindai, aktivitas latihan akan tersimpan otomatis di sini.</p>
                            @if($memberQrData)
                                <button type="button" onclick="openAttendanceQR()" class="att-btn mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 text-white transition hover:bg-dark">Mulai Check-in <i class="fa-solid fa-qrcode"></i></button>
                            @else
                                <a href="{{ route('member.account') }}" class="att-btn mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 text-white transition hover:bg-dark">Aktifkan QR Member <i class="fa-solid fa-arrow-right"></i></a>
                            @endif
                        </div>
                    @endif
                </section>

                {{-- RIGHT: RAIL --}}
                <aside class="att-rail attendance-animate" style="animation-delay:.3s">

                    {{-- QR shortcut --}}
                    <div class="att-rail-card">
                        <div class="att-qr-mini">
                            <div class="att-qr-box"><i class="fa-solid fa-qrcode"></i></div>
                            <div class="min-w-0">
                                <p class="att-caption-upper text-dark/45">QR Member</p>
                                <p class="att-h3 mt-0.5 text-dark">{{ $member?->name }}</p>
                                <p class="att-caption mt-0.5 text-dark/45">{{ $memberQrData ? 'QR aktif — siap dipindai' : 'Aktifkan QR dari menu profil' }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="openAttendanceQR()" class="att-qr-mini-btn"><i class="fa-solid fa-qrcode"></i> Buka QR Member</button>
                    </div>

                    {{-- Last activity --}}
                    <div class="att-rail-card">
                        <div class="flex items-center gap-3">
                            <div class="att-last-ic"><i class="fa-solid fa-bolt"></i></div>
                            <div class="min-w-0">
                                <p class="att-caption-upper text-dark/45">Aktivitas Terakhir</p>
                                <p class="att-body mt-0.5 font-semibold text-dark">{{ $lastAttendanceLabel }}</p>
                                <p class="att-caption mt-0.5 text-dark/50">Rata-rata durasi {{ $averageDurationLabel }}</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
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

    /* ── Live scan feedback: poll status tiap 3 detik, reaksi saat admin scan QR ── */
    const ATT_STATUS_URL = @json(route('member.attendance.status'));
    let attState = { activeId: @json($activeAttendance?->id) };

    function showScanToast(type, title, msg) {
        let host = document.getElementById('scanToastHost');
        if (!host) {
            host = document.createElement('div');
            host.id = 'scanToastHost';
            host.setAttribute('aria-live', 'polite');
            document.body.appendChild(host);
        }
        const el = document.createElement('div');
        el.className = 'scan-toast t-' + type;
        el.innerHTML = '<div class="st-icon">' + (type === 'in' ? '&#10003;' : type === 'out' ? '&#8595;' : 'i') + '</div>'
            + '<div><p class="st-title"></p><p class="st-msg"></p></div>'
            + '<button class="st-close" aria-label="Tutup notifikasi">&times;</button>';
        el.querySelector('.st-title').textContent = title;
        el.querySelector('.st-msg').textContent = msg;
        host.appendChild(el);
        requestAnimationFrame(() => el.classList.add('show'));
        const remove = () => { el.classList.add('hide'); setTimeout(() => el.remove(), 380); };
        el.querySelector('.st-close').addEventListener('click', remove);
        setTimeout(remove, 4500);
        while (host.children.length > 3) host.firstElementChild.remove();
    }

    function burstConfetti() {
        const colors = ['#E94683', '#176A54', '#F6E7DC', '#762645', '#EE4E8B'];
        for (let i = 0; i < 24; i++) {
            const p = document.createElement('div');
            p.className = 'confetti-piece';
            p.style.background = colors[i % colors.length];
            p.style.setProperty('--cx', ((Math.random() * 2 - 1) * 42) + 'vw');
            p.style.setProperty('--cr', ((Math.random() * 2 - 1) * 540) + 'deg');
            p.style.animationDelay = (Math.random() * 0.25) + 's';
            document.body.appendChild(p);
            setTimeout(() => p.remove(), 2700);
        }
    }

    function renderActiveBanner(active) {
        const slot = document.getElementById('activeAttendanceSlot');
        const tpl = document.getElementById('activeBannerTpl');
        if (!slot || !tpl) return;
        let banner = document.getElementById('activeAttendanceBanner');
        if (!active) { banner?.remove(); return; }
        if (!banner) {
            banner = tpl.content.firstElementChild.cloneNode(true);
            slot.appendChild(banner);
        }
        const prog = banner.querySelector('[data-banner-program]');
        const time = banner.querySelector('[data-banner-time]');
        if (prog) prog.textContent = active.program || 'General Fitness';
        if (time) time.textContent = 'Masuk ' + String(active.check_in_at || '').slice(11, 16) + '. Scan QR untuk check-out.';
    }

    async function syncAttStatus() {
        if (document.hidden) return;
        try {
            const res = await fetch(ATT_STATUS_URL, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            const prev = attState.activeId;
            const cur = data.active ? data.active.id : null;

            if (cur && cur !== prev) {
                showScanToast('in', 'Check-in tercatat', (data.active.program || 'Kelas') + ' · masuk ' + String(data.active.check_in_at || '').slice(11, 16));
                burstConfetti();
                renderActiveBanner(data.active);
            } else if (!cur && prev) {
                const dur = data.latest && data.latest.check_out_at ? Number(data.latest.duration_minutes || 0) : 0;
                showScanToast('out', 'Check-out tercatat', dur > 0 ? 'Durasi latihan ' + dur + ' menit. Sampai jumpa!' : 'Sesi latihan selesai. Sampai jumpa!');
                renderActiveBanner(null);
            }

            attState.activeId = cur;
        } catch (e) {
            /* offline / sesi berakhir — abaikan, coba lagi periode berikutnya */
        }
    }

    setInterval(syncAttStatus, 3000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) syncAttStatus(); });
</script>
@endsection
