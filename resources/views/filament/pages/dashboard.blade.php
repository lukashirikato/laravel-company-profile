@php
    use Illuminate\Support\Facades\Auth;

    $user        = Auth::user();
    $currentDate = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    $searchUrl   = route('admin.dashboard.search');
@endphp

<x-filament::page>
    <style>
        /* ============================================================
           FTM SOCIETY — DASHBOARD ADMIN
           Brand: Burnt Cherry #7A2B4A, Power Pink #EE4E8B,
                  Soft Petals #F4C9DF, Rising #FCF9F2, Layl #1C1C1C
           ============================================================ */

        /* Hide default Filament heading + breadcrumbs */
        .filament-page-heading,
        .filament-page-header,
        .filament-page > header,
        .filament-page-breadcrumbs,
        .filament-header-heading,
        .filament-page > .filament-header,
        h1.filament-page-heading {
            display: none !important;
        }
        .filament-page { padding-top: 0 !important; background: #FCF9F2 !important; }
        .filament-main, .filament-main-content { background: #FCF9F2 !important; }
        body { background: #FCF9F2 !important; }

        :root {
            --c-pink:        #EE4E8B;
            --c-cherry:      #7A2B4A;
            --c-cherry-dark: #5A1F37;
            --c-petal:       #F4C9DF;
            --c-petal-soft:  #FAE0EE;
            --c-rising:      #FCF9F2;
            --c-layl:        #1C1C1C;
            --c-green:       #1A7A5E;
            --c-green-soft:  #C8E8DD;
            --c-amber:       #E59A2C;
            --c-amber-soft:  #FFF1DC;

            --r-md: 12px;
            --r-lg: 16px;
            --r-xl: 22px;

            --sh-sm: 0 1px 2px rgba(122, 43, 74, 0.05);
            --sh-md: 0 4px 14px rgba(122, 43, 74, 0.07);
            --sh-lg: 0 12px 28px rgba(122, 43, 74, 0.10);
        }

        .ftm-dash {
            padding: 1.5rem 1.75rem 2.5rem;
            font-family: 'Poppins', system-ui, sans-serif;
            color: var(--c-layl);
            animation: ftmFade 0.4s ease-out;
        }
        @media (min-width: 1280px) {
            .ftm-dash { padding: 1.75rem 2rem 3rem; }
        }
        @keyframes ftmFade {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── PAGE HEAD ROW ─── */
        .ftm-pagehead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .ftm-pagehead-left h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--c-layl);
            margin: 0;
            letter-spacing: -0.01em;
        }
        .ftm-pagehead-left p {
            font-size: 0.85rem;
            color: rgba(28, 28, 28, 0.6);
            margin: 0.2rem 0 0;
        }
        .ftm-pagehead-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .ftm-search-wrap {
            position: relative;
        }
        .ftm-search {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: 999px;
            padding: 0.55rem 1.1rem;
            min-width: 250px;
            box-shadow: var(--sh-sm);
        }
        .ftm-search input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.85rem;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }
        .ftm-search svg { color: rgba(28,28,28,0.4); flex-shrink: 0; }
        
        .ftm-search-results {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            min-width: 320px;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-md);
            box-shadow: var(--sh-lg);
            padding: 0.5rem;
            z-index: 50;
            max-height: 380px;
            overflow-y: auto;
        }
        .ftm-sr-group { margin-bottom: 0.5rem; }
        .ftm-sr-group:last-child { margin-bottom: 0; }
        .ftm-sr-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--c-cherry);
            padding: 0.35rem 0.6rem 0.2rem;
        }
        .ftm-sr-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.6rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--c-layl);
            transition: background 0.15s;
        }
        .ftm-sr-item:hover { background: var(--c-petal-soft); }
        .ftm-sr-ico {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .ftm-sr-main { flex: 1; min-width: 0; }
        .ftm-sr-title { font-size: 0.82rem; font-weight: 600; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .ftm-sr-sub { font-size: 0.72rem; color: rgba(28,28,28,0.55); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .ftm-sr-chip { font-size: 0.65rem; font-weight: 600; padding: 2px 6px; border-radius: 4px; background: rgba(122,43,74,0.08); color: var(--c-cherry); }
        .ftm-sr-empty { padding: 1rem; text-align: center; font-size: 0.82rem; color: rgba(28,28,28,0.5); }

        .ftm-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: 999px;
            padding: 0.55rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--c-cherry);
            box-shadow: var(--sh-sm);
        }
        .ftm-bell-wrap { position: relative; }
        .ftm-bell {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--c-cherry);
            box-shadow: var(--sh-sm);
            cursor: pointer;
            transition: background .18s, transform .15s;
        }
        .ftm-bell:hover { background: var(--c-petal-soft); transform: translateY(-1px); }
        .ftm-bell-dot {
            position: absolute;
            top: 6px; right: 6px;
            background: var(--c-pink);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            border-radius: 999px;
            min-width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid #fff;
        }

        /* Notif Panel */
        .ftm-notif-panel {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            max-width: 90vw;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-lg);
            box-shadow: var(--sh-lg);
            padding: 1rem;
            z-index: 60;
        }
        .ftm-notif-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--c-petal);
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .ftm-notif-head h4 { font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--c-layl); }
        .ftm-notif-head p { font-size: 0.75rem; color: rgba(28,28,28,0.5); margin: 0.1rem 0 0; }
        .ftm-notif-head-actions { display: flex; align-items: center; gap: 0.5rem; }
        .ftm-notif-mark-all {
            background: none; border: none; font-size: 0.75rem; color: var(--c-pink);
            font-weight: 600; cursor: pointer; padding: 0;
        }
        .ftm-notif-mark-all:hover { text-decoration: underline; }
        .ftm-notif-close { background: none; border: none; color: rgba(28,28,28,0.4); cursor: pointer; padding: 2px; }
        .ftm-notif-list { max-height: 320px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.5rem; }
        .ftm-notif-item {
            display: flex; gap: 0.65rem; padding: 0.6rem; border-radius: 10px;
            background: #FAFAFA; cursor: pointer; transition: background .15s;
        }
        .ftm-notif-item:hover { background: var(--c-petal-soft); }
        .ftm-notif-item.unread { background: rgba(238,78,139,0.06); border-left: 3px solid var(--c-pink); }
        .ftm-notif-icon {
            width: 32px; height: 32px; border-radius: 8px; display: inline-flex;
            align-items: center; justify-content: center; flex-shrink: 0;
            background: var(--c-petal-soft); color: var(--c-cherry);
        }
        .ftm-notif-body { flex: 1; min-width: 0; }
        .ftm-notif-title { font-size: 0.8rem; font-weight: 600; margin: 0; }
        .ftm-notif-msg { font-size: 0.72rem; color: rgba(28,28,28,0.65); margin: 0.15rem 0 0; line-height: 1.3; }
        .ftm-notif-time { font-size: 0.68rem; color: rgba(28,28,28,0.4); margin: 0.25rem 0 0; }
        .ftm-notif-empty { text-align: center; padding: 1.5rem 0; font-size: 0.82rem; color: rgba(28,28,28,0.5); }
        .ftm-notif-loading { text-align: center; padding: 1.25rem; font-size: 0.8rem; color: rgba(28,28,28,0.4); }

        /* ─── HERO BANNER ─── */
        .ftm-hero {
            background: linear-gradient(120deg, var(--c-cherry) 0%, #5A1F37 60%, #4A1A2E 100%);
            color: #FFFFFF;
            padding: 2rem 2.25rem;
            border-radius: var(--r-xl);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--sh-md);
            position: relative;
            overflow: hidden;
        }
        .ftm-hero::after {
            content: "";
            position: absolute;
            right: -30px; top: -30px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(238,78,139,0.35) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .ftm-hero-text h2 {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0 0 0.4rem;
            letter-spacing: -0.01em;
        }
        .ftm-hero-text p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
            max-width: 600px;
            line-height: 1.5;
        }
        .ftm-hero-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }
        .ftm-hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.15);
            color: #FFFFFF;
            padding: 0.7rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.88rem;
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(8px);
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
            text-decoration: none;
        }
        .ftm-hero-btn:hover { background: rgba(255,255,255,0.28); transform: translateY(-2px); color: #fff; }
        .ftm-hero-btn.primary {
            background: var(--c-pink);
            border-color: var(--c-pink);
        }
        .ftm-hero-btn.primary:hover {
            background: #d83d78;
            border-color: #d83d78;
        }

        /* ─── QUICK NAV GRID ─── */
        .ftm-section-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--c-layl);
            margin: 0 0 1rem;
            letter-spacing: -0.01em;
        }
        .ftm-nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }
        .ftm-nav-card {
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-lg);
            padding: 1.35rem 1.4rem;
            display: flex;
            align-items: flex-start;
            gap: 1.1rem;
            text-decoration: none;
            color: var(--c-layl);
            box-shadow: var(--sh-sm);
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }
        .ftm-nav-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--sh-lg);
            border-color: var(--c-pink);
        }
        .ftm-nav-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--c-petal-soft);
            color: var(--c-cherry);
            transition: background .2s, color .2s;
        }
        .ftm-nav-card:hover .ftm-nav-icon {
            background: var(--c-pink);
            color: #FFFFFF;
        }
        .ftm-nav-content h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
            color: var(--c-layl);
        }
        .ftm-nav-content p {
            font-size: 0.78rem;
            color: rgba(28,28,28,0.6);
            margin: 0;
            line-height: 1.4;
        }
    </style>

    <div class="ftm-dash">
        {{-- ════════════ PAGE HEAD ════════════ --}}
        <div class="ftm-pagehead">
            <div class="ftm-pagehead-left">
                <h1>Dashboard</h1>
                <p>Selamat datang di panel manajemen FTM Society</p>
            </div>
            <div class="ftm-pagehead-right">
                <div class="ftm-search-wrap">
                    <div class="ftm-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" id="ftmSearchInput" placeholder="Cari member, invoice, booking, kelas…" autocomplete="off" aria-label="Pencarian">
                    </div>
                    <div id="ftmSearchResults" class="ftm-search-results" hidden></div>
                </div>

                <div class="ftm-chip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    {{ $currentDate }}
                </div>

                <div class="ftm-bell-wrap">
                    <button class="ftm-bell" type="button" id="ftmBellBtn" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span class="ftm-bell-dot" id="ftmBellDot" style="display:none;">0</span>
                    </button>
                    <div id="ftmNotifPanel" class="ftm-notif-panel" hidden>
                        <div class="ftm-notif-head">
                            <div>
                                <h4>Notifikasi</h4>
                                <p id="ftmNotifSubtitle">Memuat…</p>
                            </div>
                            <div class="ftm-notif-head-actions">
                                <button type="button" id="ftmMarkAllRead" class="ftm-notif-mark-all">
                                    Tandai semua dibaca
                                </button>
                                <button type="button" id="ftmNotifClose" class="ftm-notif-close" aria-label="Tutup">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </div>
                        <div id="ftmNotifList" class="ftm-notif-list">
                            <div class="ftm-notif-loading">Memuat notifikasi…</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════ HERO BANNER ════════════ --}}
        <style>
            .ftm-role-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-left: 10px;
                padding: 4px 12px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: .4px;
                text-transform: uppercase;
                vertical-align: middle;
                background: var(--c-petal);
                color: var(--c-cherry);
            }
            .ftm-role-badge.owner { background: var(--c-green-soft); color: var(--c-green); }
            .ftm-role-badge.admin { background: #FDECC8; color: #8A5A00; }
        </style>
        <div class="ftm-hero">
            <div class="ftm-hero-text">
                <h2>
                    Selamat datang kembali, {{ $user->name ?? 'Admin' }}! 👋
                    @if($user)
                        <span class="ftm-role-badge {{ strtolower($user->roleLabel()) }}">{{ $user->roleLabel() }}</span>
                    @endif
                </h2>
                @if($user && $user->isOwner())
                    <p><strong>Akses Owner (Penuh):</strong> Kelola data finansial, staf & role, pengaturan sistem, paket, jadwal, serta seluruh operasional gym.</p>
                @else
                    <p><strong>Akses Admin (Operasional):</strong> Kelola data anggota, verifikasi pembayaran, scan presensi QR, jadwal kelas, dan follow-up member.</p>
                @endif
            </div>
            <div class="ftm-hero-actions">
                <a href="{{ \App\Filament\Resources\CustomerResource::getUrl('index') }}" class="ftm-hero-btn primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Daftar Member
                </a>
                <a href="{{ \App\Filament\Pages\QrScanner::getUrl() }}" class="ftm-hero-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/></svg>
                    QR Scanner
                </a>
            </div>
        </div>

        {{-- ════════════ QUICK NAVIGATION ════════════ --}}
        <h2 class="ftm-section-title">Menu Utama</h2>
        <div class="ftm-nav-grid">
            {{-- Member --}}
            <a href="{{ \App\Filament\Resources\CustomerResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Data Member</h3>
                    <p>Kelola data profil, paket aktif, dan kontak member.</p>
                </div>
            </a>

            {{-- Pesanan & Transaksi --}}
            <a href="{{ \App\Filament\Resources\OrderResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Pesanan & Transaksi</h3>
                    <p>Pantau pembayaran Midtrans, invoice, dan status order.</p>
                </div>
            </a>

            {{-- Paket Program --}}
            <a href="{{ \App\Filament\Resources\PackageResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Paket Program</h3>
                    <p>Atur kuota, masa aktif, harga, dan jenis program membership.</p>
                </div>
            </a>

            {{-- Jadwal Kelas --}}
            <a href="{{ \App\Filament\Resources\ScheduleResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M12 14v4M10 16h4"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Jadwal Kelas</h3>
                    <p>Kelola jadwal harian, instruktur, jam kelas, dan kapasitas.</p>
                </div>
            </a>

            {{-- Presensi & Check-in --}}
            <a href="{{ \App\Filament\Resources\AttendanceResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 11 2 2 4-4"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Presensi Member</h3>
                    <p>Catatan kehadiran dan riwayat check-in kelas member.</p>
                </div>
            </a>

            {{-- Voucher Promo --}}
            <a href="{{ \App\Filament\Resources\VoucherResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Voucher Promo</h3>
                    <p>Buat kode voucher diskon dan batasan masa berlaku.</p>
                </div>
            </a>

            {{-- Manajemen User & Role (hanya admin & owner) --}}
            @if(\App\Filament\Resources\UserResource::can('viewAny'))
            <a href="{{ \App\Filament\Resources\UserResource::getUrl('index') }}" class="ftm-nav-card">
                <div class="ftm-nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="ftm-nav-content">
                    <h3>Manajemen User</h3>
                    <p>Tambah akun staff dan atur role User, Admin, atau Owner.</p>
                </div>
            </a>
            @endif
        </div>
    </div>

    <script>
        // ═══════════ SEARCH REAL-TIME ═══════════
        (function () {
            const SEARCH_URL  = '{{ $searchUrl }}';
            const searchInput = document.getElementById('ftmSearchInput');
            const searchBox   = document.getElementById('ftmSearchResults');
            let debounce = null;

            function esc(s) {
                if (s === null || s === undefined) return '';
                return String(s).replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
            }

            function renderSearch(results) {
                if (!results) { searchBox.hidden = true; return; }
                const groups = [
                    { key: 'customers', label: 'Member', icon: '👤' },
                    { key: 'orders', label: 'Invoice', icon: '🧾' },
                    { key: 'bookings', label: 'Booking', icon: '📅' },
                    { key: 'classes', label: 'Kelas', icon: '🏋' }
                ];
                let html = '';
                groups.forEach(function (g) {
                    const items = results[g.key] || [];
                    if (!items.length) return;
                    html += '<div class="ftm-sr-group"><div class="ftm-sr-label">' + g.label + '</div>';
                    html += items.map(function (it) {
                        let title = '', sub = '';
                        if (g.key === 'customers') { title = it.name; sub = (it.email || '') + (it.phone ? ' · ' + it.phone : ''); }
                        if (g.key === 'orders') { title = it.code; sub = it.name + ' · ' + it.package; }
                        if (g.key === 'bookings') { title = it.name; sub = it.label + ' · ' + it.date; }
                        if (g.key === 'classes') { title = it.label; sub = it.date + ' ' + (it.time || '') + (it.instructor ? ' · ' + it.instructor : ''); }
                        return '<a class="ftm-sr-item" href="' + esc(it.url || '#') + '">' +
                            '<span class="ftm-sr-ico" style="background:var(--c-petal-soft);">' + g.icon + '</span>' +
                            '<span class="ftm-sr-main"><span class="ftm-sr-title">' + esc(title) + '</span>' +
                            '<span class="ftm-sr-sub">' + esc(sub) + '</span></span>' +
                            '<span class="ftm-sr-chip">' + g.label + '</span></a>';
                    }).join('');
                    html += '</div>';
                });
                if (!html) html = '<div class="ftm-sr-empty">Tidak ditemukan hasil untuk "' + esc(searchInput.value) + '"</div>';
                searchBox.innerHTML = html;
                searchBox.hidden = false;
            }

            if (searchInput && searchBox) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.trim();
                    clearTimeout(debounce);
                    if (q.length < 2) { searchBox.hidden = true; return; }
                    debounce = setTimeout(function () {
                        fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'include'
                        })
                        .then(r => r.json())
                        .then(data => renderSearch(data.results))
                        .catch(() => { searchBox.hidden = true; });
                    }, 280);
                });

                document.addEventListener('click', function (e) {
                    if (!e.target.closest('.ftm-search-wrap')) searchBox.hidden = true;
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') searchBox.hidden = true;
                });
            }
        })();

        // ═══════════ NOTIFICATION CENTER ═══════════
        (function() {
            const bellBtn   = document.getElementById('ftmBellBtn');
            const bellDot   = document.getElementById('ftmBellDot');
            const panel     = document.getElementById('ftmNotifPanel');
            const list      = document.getElementById('ftmNotifList');
            const subtitle  = document.getElementById('ftmNotifSubtitle');
            const markAll   = document.getElementById('ftmMarkAllRead');
            const closeBtn  = document.getElementById('ftmNotifClose');
            if (!bellBtn || !panel) return;

            const FEED_URL    = '{{ route("admin.notifications.feed") }}';
            const READ_URL    = '{{ url("/staff/notifications") }}';
            const READ_ALL_URL= '{{ route("admin.notifications.readAll") }}';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

            const ICONS = {
                'user-plus':         '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>',
                'user-check':        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>',
                'shopping-bag':      '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                'credit-card-check': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><polyline points="14 16 16 18 20 14"/></svg>',
                'credit-card-x':     '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><line x1="14" x2="20" y1="15" y2="19"/><line x1="20" x2="14" y1="15" y2="19"/></svg>',
                'calendar-plus':     '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M12 14v4M10 16h4"/></svg>',
                'qr-code':           '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/></svg>',
                'bell':              '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/></svg>'
            };

            function iconSvg(name) { return ICONS[name] || ICONS['bell']; }

            function escapeHtml(s) {
                if (s === null || s === undefined) return '';
                return String(s).replace(/[&<>"']/g, m => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[m]));
            }

            function renderList(items) {
                if (!items || items.length === 0) {
                    list.innerHTML = '<div class="ftm-notif-empty">📭 Belum ada notifikasi</div>';
                    return;
                }
                list.innerHTML = items.map(n => {
                    const url = n.data && n.data.url ? n.data.url : null;
                    const cls = n.is_read ? '' : 'unread';
                    const ic  = iconSvg(n.icon || 'bell');
                    return `
                        <div class="ftm-notif-item ${cls}" data-id="${n.id}" data-url="${url || ''}">
                            <div class="ftm-notif-icon">${ic}</div>
                            <div class="ftm-notif-body">
                                <p class="ftm-notif-title">${escapeHtml(n.title)}</p>
                                <p class="ftm-notif-msg">${escapeHtml(n.message)}</p>
                                <p class="ftm-notif-time">${escapeHtml(n.time_human || '')}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                list.querySelectorAll('.ftm-notif-item').forEach(el => {
                    el.addEventListener('click', () => {
                        const id  = el.getAttribute('data-id');
                        const url = el.getAttribute('data-url');
                        markRead(id, () => {
                            el.classList.remove('unread');
                            if (url && url !== 'null') window.location.href = url;
                        });
                    });
                });
            }

            function fetchFeed() {
                fetch(FEED_URL, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'include'
                })
                .then(r => r.ok ? r.json() : Promise.reject(r.status))
                .then(data => {
                    const unread = parseInt(data.unread || 0);
                    if (unread > 0) {
                        bellDot.style.display = 'inline-flex';
                        bellDot.textContent = unread > 9 ? '9+' : unread;
                    } else {
                        bellDot.style.display = 'none';
                    }
                    if (subtitle) {
                        subtitle.textContent = unread > 0
                            ? unread + ' belum dibaca'
                            : 'Semua sudah dibaca';
                    }
                    renderList(data.notifications || []);
                })
                .catch(err => {
                    if (subtitle) subtitle.textContent = 'Gagal memuat';
                });
            }

            function markRead(id, cb) {
                fetch(`${READ_URL}/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                }).finally(() => {
                    fetchFeed();
                    if (cb) cb();
                });
            }

            function markAllRead() {
                fetch(READ_ALL_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                }).then(() => fetchFeed());
            }

            bellBtn.addEventListener('click', e => {
                e.stopPropagation();
                panel.hidden = !panel.hidden;
                if (!panel.hidden) fetchFeed();
            });

            markAll.addEventListener('click', e => {
                e.stopPropagation();
                markAllRead();
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    panel.hidden = true;
                });
            }

            document.addEventListener('click', e => {
                if (!panel.contains(e.target) && !bellBtn.contains(e.target)) {
                    panel.hidden = true;
                }
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') panel.hidden = true;
            });

            // Initial fetch notif
            fetchFeed();
        })();
    </script>
</x-filament::page>
