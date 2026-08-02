@php
    use Illuminate\Support\Facades\Auth;

    $user        = Auth::user();
    $currentDate = now()->locale('id')->isoFormat('D MMM YYYY');

    // Seluruh metrik analytics dimuat secara real-time oleh JavaScript
    // melalui endpoint staff/dashboard/analytics (polling tiap 30 detik).
    $analyticsUrl = route('admin.dashboard.analytics');
    $searchUrl    = route('admin.dashboard.search');
@endphp

<x-filament::page>
    <style>
        /* ============================================================
           FTM SOCIETY — DASHBOARD ADMIN (SaaS Modern)
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
            padding: 1.5rem 1.75rem 2rem;
            font-family: 'Poppins', system-ui, sans-serif;
            color: var(--c-layl);
            animation: ftmFade 0.5s ease-out;
        }
        @media (min-width: 1280px) {
            .ftm-dash { padding: 1.75rem 2rem 2.5rem; }
        }
        @keyframes ftmFade {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── PAGE HEAD ROW ─── */
        .ftm-pagehead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }
        .ftm-pagehead-left h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--c-layl);
            margin: 0;
            letter-spacing: -0.01em;
        }
        .ftm-pagehead-left p {
            font-size: 0.82rem;
            color: rgba(28, 28, 28, 0.6);
            margin: 0.15rem 0 0;
        }
        .ftm-pagehead-right {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }
        .ftm-search {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: 999px;
            padding: 0.55rem 1rem;
            min-width: 240px;
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

        /* ─── HERO BANNER ─── */
        .ftm-hero {
            background: linear-gradient(120deg, var(--c-cherry) 0%, #5A1F37 60%, #4A1A2E 100%);
            color: #FFFFFF;
            padding: 1.5rem 1.75rem;
            border-radius: var(--r-xl);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--sh-md);
            position: relative;
            overflow: hidden;
        }
        .ftm-hero::after {
            content: "";
            position: absolute;
            right: -40px; top: -40px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(238,78,139,0.35) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .ftm-hero-text h2 {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            margin: 0 0 0.3rem;
            letter-spacing: -0.01em;
        }
        .ftm-hero-text p {
            font-size: 0.85rem;
            opacity: 0.85;
            margin: 0;
        }
        .ftm-hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.12);
            color: #FFFFFF;
            padding: 0.65rem 1.1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(8px);
            cursor: pointer;
            transition: background .18s, transform .15s;
            white-space: nowrap;
            position: relative;
            z-index: 1;
            text-decoration: none;
        }
        .ftm-hero-btn:hover { background: rgba(255,255,255,0.22); transform: translateY(-1px); color: #fff; }

        /* ─── KPI GRID ─── */
        .ftm-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 1100px) { .ftm-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px)  { .ftm-kpi-grid { grid-template-columns: 1fr; } }

        .ftm-kpi {
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-lg);
            padding: 1.1rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            box-shadow: var(--sh-sm);
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }
        .ftm-kpi:hover {
            transform: translateY(-3px);
            box-shadow: var(--sh-lg);
            border-color: var(--c-pink);
        }
        .ftm-kpi-top {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .ftm-kpi-icon {
            width: 48px; height: 48px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            flex-shrink: 0;
        }
        .ftm-kpi-icon svg { width: 22px; height: 22px; }
        .ftm-kpi--pink   .ftm-kpi-icon { background: var(--c-pink); }
        .ftm-kpi--green  .ftm-kpi-icon { background: var(--c-green); }
        .ftm-kpi--cherry .ftm-kpi-icon { background: var(--c-cherry); }
        .ftm-kpi--amber  .ftm-kpi-icon { background: var(--c-amber); }
        .ftm-kpi-label {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--c-cherry);
        }
        .ftm-kpi-value {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 800;
            font-size: 1.65rem;
            line-height: 1.1;
            color: var(--c-layl);
            letter-spacing: -0.02em;
        }
        .ftm-kpi-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .ftm-kpi-meta.up    { color: var(--c-green); }
        .ftm-kpi-meta.down  { color: var(--c-amber); }
        .ftm-kpi-meta.muted { color: rgba(28,28,28,0.55); }
        .ftm-kpi-meta svg   { width: 12px; height: 12px; }

        /* ─── CONTENT GRID (chart + sidebar) ─── */
        .ftm-grid-2 {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 1100px) { .ftm-grid-2 { grid-template-columns: 1fr; } }

        .ftm-card {
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            box-shadow: var(--sh-sm);
        }
        .ftm-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .ftm-card-title {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 700;
            color: var(--c-layl);
            font-size: 0.95rem;
            margin: 0;
        }
        .ftm-card-link {
            font-size: 0.78rem;
            color: var(--c-cherry);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .ftm-card-link:hover { color: var(--c-pink); }

        .ftm-period {
            font-size: 0.78rem;
            color: var(--c-cherry);
            background: var(--c-petal-soft);
            border: 1px solid var(--c-petal);
            border-radius: 8px;
            padding: 0.35rem 0.7rem;
            font-weight: 600;
            cursor: pointer;
        }

        /* ─── Chart row ─── */
        .ftm-chart-wrap {
            position: relative;
            height: 260px;
        }
        .ftm-chart-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--c-petal);
        }
        .ftm-chart-stat-label {
            font-size: 0.72rem;
            color: rgba(28,28,28,0.55);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        .ftm-chart-stat-value {
            font-family: 'Nord','Poppins',sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--c-layl);
            margin: 0 0 0.15rem;
            letter-spacing: -0.01em;
        }
        .ftm-chart-stat-meta {
            font-size: 0.7rem;
            color: rgba(28,28,28,0.5);
        }

        /* ─── Activity feed ─── */
        .ftm-feed { display: flex; flex-direction: column; gap: 0.85rem; }
        .ftm-feed-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .ftm-feed-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ftm-feed-icon svg { width: 16px; height: 16px; }
        .ftm-feed-icon.pink   { background: var(--c-petal-soft); color: var(--c-pink); }
        .ftm-feed-icon.cherry { background: var(--c-petal-soft); color: var(--c-cherry); }
        .ftm-feed-icon.green  { background: var(--c-green-soft); color: var(--c-green); }
        .ftm-feed-icon.amber  { background: var(--c-amber-soft); color: var(--c-amber); }
        .ftm-feed-body { flex: 1; min-width: 0; }
        .ftm-feed-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--c-layl);
            margin: 0;
        }
        .ftm-feed-sub {
            font-size: 0.75rem;
            color: rgba(28,28,28,0.55);
            margin: 0.15rem 0 0;
        }
        .ftm-feed-time {
            font-size: 0.72rem;
            color: rgba(28,28,28,0.45);
            white-space: nowrap;
            margin-left: 0.5rem;
        }

        /* ─── Reminder cards ─── */
        .ftm-rem {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem;
            border-radius: var(--r-md);
            border: 1px solid;
            margin-bottom: 0.65rem;
        }
        .ftm-rem:last-child { margin-bottom: 0; }
        .ftm-rem-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ftm-rem-icon svg { width: 16px; height: 16px; }
        .ftm-rem--danger  { background: #FFF0F2; border-color: #FAD0D5; }
        .ftm-rem--danger .ftm-rem-icon { background: #F8C5CF; color: #B22336; }
        .ftm-rem--green   { background: #EBF8F2; border-color: #C8E8DD; }
        .ftm-rem--green .ftm-rem-icon { background: #C8E8DD; color: var(--c-green); }
        .ftm-rem--cherry  { background: var(--c-petal-soft); border-color: var(--c-petal); }
        .ftm-rem--cherry .ftm-rem-icon { background: var(--c-petal); color: var(--c-cherry); }
        .ftm-rem-body { flex: 1; min-width: 0; }
        .ftm-rem-title {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--c-layl);
            margin: 0;
        }
        .ftm-rem-sub {
            font-size: 0.72rem;
            color: rgba(28,28,28,0.55);
            margin: 0.15rem 0 0;
        }
        .ftm-rem-action {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--c-cherry);
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: 8px;
            padding: 0.35rem 0.7rem;
            cursor: pointer;
            white-space: nowrap;
        }
        .ftm-rem-action:hover { background: var(--c-petal-soft); }

        /* ─── Member table ─── */
        .ftm-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ftm-table thead th {
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(28,28,28,0.55);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--c-petal);
            background: var(--c-rising);
        }
        .ftm-table tbody td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid var(--c-petal);
            font-size: 0.85rem;
            color: var(--c-layl);
            vertical-align: middle;
        }
        .ftm-table tbody tr:last-child td { border-bottom: none; }
        .ftm-table tbody tr:hover td { background: rgba(244,201,223,0.18); }
        .ftm-table-name {
            font-weight: 600;
            color: var(--c-layl);
        }
        .ftm-table-email { color: rgba(28,28,28,0.6); font-size: 0.82rem; }

        .ftm-status {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
        }
        .ftm-status.active {
            background: var(--c-green-soft);
            color: var(--c-green);
        }
        .ftm-status.inactive {
            background: var(--c-amber-soft);
            color: var(--c-amber);
        }

        .ftm-row-action {
            background: transparent;
            border: none;
            cursor: pointer;
            color: rgba(28,28,28,0.45);
            padding: 0.4rem;
            border-radius: 6px;
        }
        .ftm-row-action:hover { background: var(--c-petal-soft); color: var(--c-cherry); }

        /* ─── Empty state ─── */
        .ftm-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: rgba(28,28,28,0.55);
        }
        .ftm-empty svg { color: var(--c-petal); width: 56px; height: 56px; margin-bottom: 0.85rem; }
        .ftm-empty-title { font-weight: 700; color: var(--c-layl); margin-bottom: 0.25rem; }

        /* ─── Modal (kept from old) ─── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(28, 28, 28, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            animation: ftmFade 0.25s ease;
        }
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-content {
            background: var(--c-rising);
            border: 1px solid var(--c-petal);
            border-radius: var(--r-xl);
            box-shadow: var(--sh-lg);
            width: 100%;
            max-width: 880px;
            max-height: 86vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--c-petal);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--c-rising);
            z-index: 10;
        }
        .modal-header h2 {
            font-family: 'Nord','Poppins',sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--c-cherry);
            margin: 0;
        }
        .modal-close {
            background: none; border: none;
            cursor: pointer; color: rgba(28,28,28,0.6);
            font-size: 1.4rem;
            line-height: 1;
        }
        .modal-close:hover { color: var(--c-cherry); }
        .modal-body { padding: 1.25rem 1.5rem; }
        .modal-filters {
            display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;
        }
        .filter-btn {
            padding: 0.5rem 0.9rem;
            border: 1px solid var(--c-petal);
            background: #FFFFFF;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--c-cherry);
            font-family: 'Poppins', sans-serif;
        }
        .filter-btn:hover { background: var(--c-petal-soft); }
        .filter-btn.active {
            background: var(--c-cherry);
            color: #FFFFFF;
            border-color: var(--c-cherry);
        }
        .customer-list { list-style: none; padding: 0; margin: 0; }
        .customer-item {
            padding: 1rem;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-md);
            margin-bottom: 0.85rem;
            background: #FFFFFF;
        }
        .customer-item-header {
            display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.6rem;
        }
        .customer-name { font-weight: 700; color: var(--c-layl); }
        .customer-status { font-size: 0.7rem; padding: 0.25rem 0.65rem; border-radius: 999px; font-weight: 700; }
        .customer-status.active   { background: var(--c-green-soft); color: var(--c-green); }
        .customer-status.inactive { background: var(--c-amber-soft); color: var(--c-amber); }
        .customer-info {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;
            margin-bottom: 0.85rem; font-size: 0.82rem;
        }
        .customer-info-item { display: flex; flex-direction: column; }
        .customer-info-label { font-weight: 600; color: rgba(28,28,28,0.6); margin-bottom: 0.15rem; font-size: 0.72rem; }
        .customer-info-value { color: var(--c-layl); }
        .customer-actions { display: flex; gap: 0.5rem; justify-content: flex-end; flex-wrap: wrap; }
        .action-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 0.85rem; border-radius: 8px;
            font-weight: 600; font-size: 0.8rem; text-decoration: none;
            border: none; cursor: pointer;
        }
        .action-link.whatsapp { background: var(--c-green); color: #fff; }
        .action-link.whatsapp:hover { background: #136047; }
        .action-link.view { background: var(--c-petal-soft); color: var(--c-cherry); }
        .action-link.view:hover { background: var(--c-cherry); color: #fff; }
        .loading { text-align: center; padding: 2rem; color: rgba(28,28,28,0.6); }
        .loading-spinner {
            display: inline-block; width: 36px; height: 36px;
            border: 3px solid var(--c-petal);
            border-top: 3px solid var(--c-pink);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 0.75rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .empty-state-modal { text-align: center; padding: 2rem; color: rgba(28,28,28,0.6); }
        .empty-icon-modal { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }

        /* ─── Notification Panel Dropdown ─── */
        .ftm-bell-wrap { position: relative; display: inline-block; }
        .ftm-notif-panel[hidden] { display: none !important; }
        .ftm-notif-panel {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 380px;
            max-width: 92vw;
            max-height: 480px;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: var(--r-lg);
            box-shadow: var(--sh-lg);
            z-index: 100;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: ftmFade 0.2s ease-out;
        }
        .ftm-notif-head {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--c-petal);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            background: var(--c-rising);
        }
        .ftm-notif-head h4 {
            font-family: 'Nord','Poppins',sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--c-cherry);
            margin: 0;
        }
        .ftm-notif-head p {
            font-size: 0.72rem;
            color: rgba(28,28,28,0.55);
            margin: 0.1rem 0 0;
        }
        .ftm-notif-mark-all {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--c-cherry);
            background: transparent;
            border: 1px solid var(--c-petal);
            padding: 0.4rem 0.7rem;
            border-radius: 8px;
            cursor: pointer;
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
        }
        .ftm-notif-mark-all:hover {
            background: var(--c-petal-soft);
            color: var(--c-pink);
        }
        .ftm-notif-head-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .ftm-notif-close {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: transparent;
            border: 1px solid var(--c-petal);
            color: var(--c-cherry);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, transform .15s;
            flex-shrink: 0;
        }
        .ftm-notif-close:hover {
            background: var(--c-petal);
            color: var(--c-cherry-dark);
            transform: rotate(90deg);
        }
        .ftm-notif-list {
            overflow-y: auto;
            flex: 1;
        }
        .ftm-notif-loading,
        .ftm-notif-empty {
            text-align: center;
            padding: 2rem 1rem;
            color: rgba(28,28,28,0.55);
            font-size: 0.85rem;
        }
        .ftm-notif-item {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(244, 201, 223, 0.5);
            cursor: pointer;
            transition: background .15s;
            position: relative;
        }
        .ftm-notif-item:last-child { border-bottom: none; }
        .ftm-notif-item:hover { background: rgba(244, 201, 223, 0.18); }
        .ftm-notif-item.unread { background: rgba(238, 78, 139, 0.04); }
        .ftm-notif-item.unread::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            width: 8px; height: 8px;
            background: var(--c-pink);
            border-radius: 50%;
        }
        .ftm-notif-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ftm-notif-icon svg { width: 16px; height: 16px; }
        .ftm-notif-icon.green  { background: var(--c-green-soft); color: var(--c-green); }
        .ftm-notif-icon.pink   { background: var(--c-petal-soft); color: var(--c-pink); }
        .ftm-notif-icon.cherry { background: var(--c-petal-soft); color: var(--c-cherry); }
        .ftm-notif-icon.amber  { background: var(--c-amber-soft); color: var(--c-amber); }
        .ftm-notif-icon.red    { background: #FFE5E9; color: #B22336; }
        .ftm-notif-body { flex: 1; min-width: 0; padding-right: 16px; }
        .ftm-notif-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--c-layl);
            margin: 0;
            line-height: 1.3;
        }
        .ftm-notif-msg {
            font-size: 0.78rem;
            color: rgba(28,28,28,0.65);
            margin: 0.2rem 0 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ftm-notif-time {
            font-size: 0.7rem;
            color: rgba(28,28,28,0.45);
            margin-top: 0.3rem;
        }
        @media (max-width: 600px) {
            .ftm-notif-panel { right: -10px; width: calc(100vw - 32px); }
        }

        /* Mobile tweaks */
        @media (max-width: 768px) {
            .ftm-dash { padding: 1rem; }
            .ftm-search { min-width: 0; flex: 1; }
            .ftm-pagehead-right { width: 100%; }
            .ftm-hero { flex-direction: column; align-items: flex-start; }
            .ftm-chart-stats { grid-template-columns: repeat(2, 1fr); }
        }

        /* ============================================================
           ANALYTICS REAL-TIME (ditambahkan tanpa mengubah desain inti)
           ============================================================ */

        /* KPI grid — 6 kartu: 3 kolom di desktop */
        .ftm-kpi-grid { grid-template-columns: repeat(3, 1fr); }
        @media (max-width: 1100px) { .ftm-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px)  { .ftm-kpi-grid { grid-template-columns: 1fr; } }

        .ftm-kpi-value .ftm-skel {
            display: inline-block; width: 110px; height: 28px;
        }

        /* ─── Range filter bar ─── */
        .ftm-filters {
            display: flex; align-items: center; gap: 0.45rem;
            flex-wrap: wrap; margin-bottom: 1.25rem;
        }
        .ftm-filter-btn {
            border: 1px solid var(--c-petal);
            background: #FFFFFF;
            color: var(--c-cherry);
            padding: 0.5rem 0.85rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.78rem;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all .18s;
            display: inline-flex; align-items: center; gap: 0.35rem;
        }
        .ftm-filter-btn:hover { background: var(--c-petal-soft); }
        .ftm-filter-btn.active {
            background: var(--c-cherry); color: #FFF; border-color: var(--c-cherry);
            box-shadow: 0 4px 12px rgba(122, 43, 74, 0.25);
        }
        .ftm-filter-custom {
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .ftm-filter-custom input[type="date"] {
            border: 1px solid var(--c-petal);
            background: #FFF;
            border-radius: 10px;
            padding: 0.45rem 0.6rem;
            font-size: 0.78rem;
            font-family: 'Poppins', sans-serif;
            color: var(--c-layl);
            outline: none;
        }
        .ftm-filter-custom input[type="date"]:focus { border-color: var(--c-pink); }
        .ftm-updated {
            margin-left: auto; font-size: 0.72rem; color: rgba(28,28,28,0.5);
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .ftm-live-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--c-green); box-shadow: 0 0 0 0 rgba(26,122,94,0.5);
            animation: ftmLive 1.8s infinite;
        }
        @keyframes ftmLive {
            0% { box-shadow: 0 0 0 0 rgba(26,122,94,0.45); }
            70% { box-shadow: 0 0 0 7px rgba(26,122,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(26,122,94,0); }
        }

        /* ─── Search dropdown ─── */
        .ftm-search-wrap { position: relative; }
        .ftm-search-results {
            position: absolute; top: calc(100% + 8px); right: 0; left: 0;
            background: #FFFFFF;
            border: 1px solid var(--c-petal);
            border-radius: 14px;
            box-shadow: var(--sh-lg);
            z-index: 120;
            max-height: 420px; overflow-y: auto;
            animation: ftmFade .18s ease-out;
        }
        .ftm-search-results[hidden] { display: none; }
        .ftm-sr-empty { padding: 1.25rem; text-align: center; color: rgba(28,28,28,0.55); font-size: 0.82rem; }
        .ftm-sr-group { padding: 0.5rem 0; border-bottom: 1px solid rgba(244,201,223,0.5); }
        .ftm-sr-group:last-child { border-bottom: none; }
        .ftm-sr-label {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; color: rgba(28,28,28,0.45);
            padding: 0.25rem 0.85rem 0.35rem;
        }
        .ftm-sr-item {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.5rem 0.85rem; text-decoration: none;
            color: var(--c-layl); transition: background .12s;
        }
        .ftm-sr-item:hover { background: var(--c-petal-soft); }
        .ftm-sr-item .ftm-sr-ico {
            width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
        }
        .ftm-sr-item .ftm-sr-main { flex: 1; min-width: 0; }
        .ftm-sr-item .ftm-sr-title {
            font-size: 0.82rem; font-weight: 600; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .ftm-sr-item .ftm-sr-sub {
            font-size: 0.72rem; color: rgba(28,28,28,0.55);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ftm-sr-chip {
            font-size: 0.64rem; font-weight: 700; color: var(--c-cherry);
            background: var(--c-petal-soft); padding: 0.15rem 0.5rem;
            border-radius: 999px; white-space: nowrap;
        }

        /* ─── Charts 2×2 ─── */
        .ftm-charts-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem; margin-bottom: 1.5rem;
        }
        @media (max-width: 900px) { .ftm-charts-grid { grid-template-columns: 1fr; } }
        .ftm-chart-tools { display: inline-flex; gap: 0.35rem; }
        .ftm-chart-tool {
            background: #FFFFFF; border: 1px solid var(--c-petal);
            color: var(--c-cherry); border-radius: 8px;
            width: 28px; height: 28px; display: inline-flex;
            align-items: center; justify-content: center; cursor: pointer;
            font-size: 0.72rem; transition: all .15s;
        }
        .ftm-chart-tool:hover { background: var(--c-cherry); color: #FFF; border-color: var(--c-cherry); }
        .ftm-chart-wrap { height: 250px; }
        .ftm-chart-skel {
            width: 100%; height: 250px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ─── Skeleton shimmer ─── */
        .ftm-skel {
            display: block; border-radius: 8px;
            background: linear-gradient(90deg, #F4E6EE 25%, #FBEFF5 37%, #F4E6EE 63%);
            background-size: 400% 100%;
            animation: ftmShimmer 1.3s ease infinite;
        }
        @keyframes ftmShimmer {
            0% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }
        .ftm-feed-item .ftm-skel { flex: 1; height: 40px; }

        /* Refresh flash */
        .ftm-flash { animation: ftmFlash 0.8s ease; }
        @keyframes ftmFlash {
            0% { background: rgba(238,78,139,0.12); }
            100% { background: transparent; }
        }
    </style>

    <div class="ftm-dash">
        {{-- ════════════ PAGE HEAD ════════════ --}}
        <div class="ftm-pagehead">
            <div class="ftm-pagehead-left">
                <h1>Dashboard</h1>
                <p>Overview sistem gym Anda</p>
            </div>
            <div class="ftm-pagehead-right">
                <div class="ftm-search-wrap">
                    <div class="ftm-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" id="ftmSearchInput" placeholder="Cari member, invoice, booking, kelas…" autocomplete="off" aria-label="Pencarian real-time">
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
        <div class="ftm-hero">
            <div class="ftm-hero-text">
                <h2>Selamat datang kembali, {{ $user->name ?? 'Admin' }}! 👋</h2>
                <p>Kelola sistem gym Anda dengan dashboard yang powerful dan modern.</p>
            </div>
            <a href="#" class="ftm-hero-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                Lihat Laporan
            </a>
        </div>

        {{-- ════════════ RANGE FILTER (real-time) ════════════ --}}
        <div class="ftm-filters" id="ftmFilters">
            <button class="ftm-filter-btn" data-range="today">Hari Ini</button>
            <button class="ftm-filter-btn" data-range="yesterday">Kemarin</button>
            <button class="ftm-filter-btn" data-range="7d">7 Hari</button>
            <button class="ftm-filter-btn active" data-range="30d">30 Hari</button>
            <button class="ftm-filter-btn" data-range="3m">3 Bulan</button>
            <button class="ftm-filter-btn" data-range="6m">6 Bulan</button>
            <button class="ftm-filter-btn" data-range="1y">1 Tahun</button>
            <button class="ftm-filter-btn" data-range="custom" id="ftmBtnCustom">Custom</button>
            <span class="ftm-filter-custom" id="ftmCustomDates" style="display:none;">
                <input type="date" id="ftmFromDate">
                <span style="color:rgba(28,28,28,0.4);">–</span>
                <input type="date" id="ftmToDate">
                <button class="ftm-filter-btn" id="ftmApplyCustom">Terapkan</button>
            </span>
            <span class="ftm-updated"><span class="ftm-live-dot"></span> <span id="ftmLastUpdated">Memuat…</span></span>
        </div>

        {{-- ════════════ KPI GRID ════════════ --}}
        <div class="ftm-kpi-grid">
            {{-- Total Member --}}
            <div class="ftm-kpi ftm-kpi--pink" onclick="openCustomerModal('all')">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Total Member</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiTotal"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiTotalMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Memuat…
                </div>
            </div>

            {{-- Member Aktif --}}
            <div class="ftm-kpi ftm-kpi--green" onclick="openCustomerModal('active')">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Member Aktif</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiActive"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiActiveMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Memuat…
                </div>
            </div>

            {{-- Member Tidak Aktif --}}
            <div class="ftm-kpi ftm-kpi--cherry" onclick="openCustomerModal('inactive')">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="10" x2="10" y1="15" y2="9"/><line x1="14" x2="14" y1="15" y2="9"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Member Tidak Aktif</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiInactive"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiInactiveMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    Memuat…
                </div>
            </div>

            {{-- Pendapatan --}}
            <div class="ftm-kpi ftm-kpi--amber">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Pendapatan</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiRevenue"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiRevenueMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Memuat…
                </div>
            </div>

            {{-- Booking Kelas --}}
            <div class="ftm-kpi ftm-kpi--pink">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M12 14v4M10 16h4"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Booking Kelas</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiBookings"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiBookingsMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Memuat…
                </div>
            </div>

            {{-- Check-in --}}
            <div class="ftm-kpi ftm-kpi--green">
                <div class="ftm-kpi-top">
                    <div class="ftm-kpi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m19 8 3 3 5-5"/></svg>
                    </div>
                    <div>
                        <div class="ftm-kpi-label">Check-in</div>
                    </div>
                </div>
                <div class="ftm-kpi-value" id="kpiCheckins"><span class="ftm-skel"></span></div>
                <div class="ftm-kpi-meta muted" id="kpiCheckinsMeta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Memuat…
                </div>
            </div>
        </div>

        {{-- ════════════ CHARTS 2×2 (ApexCharts) ════════════ --}}
        <div class="ftm-charts-grid">
            <div class="ftm-card">
                <div class="ftm-card-head">
                    <h3 class="ftm-card-title">Member Baru</h3>
                    <div class="ftm-chart-tools">
                        <button class="ftm-chart-tool" data-export="png" data-chart="members" title="Download PNG">⬇</button>
                        <button class="ftm-chart-tool" data-export="csv" data-chart="members" title="Download CSV">CSV</button>
                    </div>
                </div>
                <div class="ftm-chart-wrap"><div id="chartMembers" class="ftm-chart-skel"><span class="ftm-skel" style="width:70%;height:12px"></span></div></div>
            </div>

            <div class="ftm-card">
                <div class="ftm-card-head">
                    <h3 class="ftm-card-title">Pendapatan</h3>
                    <div class="ftm-chart-tools">
                        <button class="ftm-chart-tool" data-export="png" data-chart="revenue" title="Download PNG">⬇</button>
                        <button class="ftm-chart-tool" data-export="csv" data-chart="revenue" title="Download CSV">CSV</button>
                    </div>
                </div>
                <div class="ftm-chart-wrap"><div id="chartRevenue" class="ftm-chart-skel"><span class="ftm-skel" style="width:70%;height:12px"></span></div></div>
            </div>

            <div class="ftm-card">
                <div class="ftm-card-head">
                    <h3 class="ftm-card-title">Booking Kelas</h3>
                    <div class="ftm-chart-tools">
                        <button class="ftm-chart-tool" data-export="png" data-chart="bookings" title="Download PNG">⬇</button>
                        <button class="ftm-chart-tool" data-export="csv" data-chart="bookings" title="Download CSV">CSV</button>
                    </div>
                </div>
                <div class="ftm-chart-wrap"><div id="chartBookings" class="ftm-chart-skel"><span class="ftm-skel" style="width:70%;height:12px"></span></div></div>
            </div>

            <div class="ftm-card">
                <div class="ftm-card-head">
                    <h3 class="ftm-card-title">Check-in</h3>
                    <div class="ftm-chart-tools">
                        <button class="ftm-chart-tool" data-export="png" data-chart="checkins" title="Download PNG">⬇</button>
                        <button class="ftm-chart-tool" data-export="csv" data-chart="checkins" title="Download CSV">CSV</button>
                    </div>
                </div>
                <div class="ftm-chart-wrap"><div id="chartCheckins" class="ftm-chart-skel"><span class="ftm-skel" style="width:70%;height:12px"></span></div></div>
            </div>
        </div>

        {{-- ════════════ AKTIVITAS TERBARU ════════════ --}}
        <div class="ftm-card">
            <div class="ftm-card-head">
                <h3 class="ftm-card-title">Aktivitas Terbaru</h3>
                <span class="ftm-period" style="cursor:default;">Real-time</span>
            </div>
            <div class="ftm-feed" id="ftmFeed">
                <div class="ftm-feed-item"><span class="ftm-skel"></span></div>
                <div class="ftm-feed-item"><span class="ftm-skel"></span></div>
                <div class="ftm-feed-item"><span class="ftm-skel"></span></div>
                <div class="ftm-feed-item"><span class="ftm-skel"></span></div>
            </div>
        </div>

        {{-- ════════════ MEMBER TABLE ════════════ --}}
        <div class="ftm-card" style="padding: 0; overflow: hidden;">
            <div class="ftm-card-head" style="padding: 1.1rem 1.25rem; margin: 0; border-bottom: 1px solid var(--c-petal);">
                <h3 class="ftm-card-title">Member Terbaru</h3>
                <a href="{{ url('/admin/resources/customers') }}" class="ftm-card-link">Lihat Semua →</a>
            </div>

            <div style="overflow-x: auto;">
                <table class="ftm-table">
                    <thead>
                        <tr>
                            <th style="padding-left:1.25rem;">Member</th>
                            <th>Email</th>
                            <th>Paket</th>
                            <th>Bergabung</th>
                            <th>Status</th>
                            <th style="text-align:right; padding-right:1.25rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ftmRecentBody">
                        <tr><td style="padding:1rem 1.25rem;" colspan="6"><span class="ftm-skel" style="height:40px;"></span></td></tr>
                        <tr><td style="padding:1rem 1.25rem;" colspan="6"><span class="ftm-skel" style="height:40px;"></span></td></tr>
                        <tr><td style="padding:1rem 1.25rem;" colspan="6"><span class="ftm-skel" style="height:40px;"></span></td></tr>
                    </tbody>
                </table>
            </div>
            <div id="ftmRecentEmpty" class="ftm-empty" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/></svg>
                <div class="ftm-empty-title">Belum Ada Member</div>
                <div>Member yang baru mendaftar akan muncul di sini.</div>
            </div>
        </div>
    </div>

    {{-- ════════════ CUSTOMER MODAL (logic preserved) ════════════ --}}
    <div id="customerModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Member</h2>
                <button class="modal-close" onclick="closeCustomerModal()" aria-label="Tutup">×</button>
            </div>
            <div class="modal-body">
                <div id="customerListContainer">
                    <div class="loading">
                        <div class="loading-spinner"></div>
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════ APEXCHARTS via CDN ════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
    <script>
        // ═══════════ DASHBOARD ANALYTICS REAL-TIME ═══════════
        (function () {
            const ANALYTICS_URL = '{{ $analyticsUrl }}';
            const SEARCH_URL    = '{{ $searchUrl }}';

            const state = { range: '30d', from: null, to: null, timer: null, firstLoad: true };
            const charts = {};

            const C = {
                pink:   '#EE4E8B',
                cherry: '#7A2B4A',
                green:  '#1A7A5E',
                amber:  '#E59A2C',
                petal:  '#F4C9DF',
                ink:    'rgba(28,28,28,0.55)',
                grid:   'rgba(244,201,223,0.4)'
            };

            // ── Helpers ─────────────────────────────────────────
            function esc(s) {
                if (s === null || s === undefined) return '';
                return String(s).replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
            }
            function fmtMoney(n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }
            function relTime(iso) {
                if (!iso) return '';
                const d = new Date(iso), now = new Date();
                const sec = Math.floor((now - d) / 1000);
                if (sec < 60) return 'baru saja';
                if (sec < 3600) return Math.floor(sec/60) + ' menit lalu';
                if (sec < 86400) return Math.floor(sec/3600) + ' jam lalu';
                return Math.floor(sec/86400) + ' hari lalu';
            }
            function arrowSvg(up) {
                return up
                    ? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m6 17 6-6 4 4 6-6"/><path d="M14 7h8v8"/></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m6 7 6 6 4-4 6 6"/><path d="M14 17h8v-8"/></svg>';
            }
            function deltaMeta(delta, label) {
                const up = delta >= 0;
                return '<span class="ftm-kpi-meta ' + (delta === 0 ? 'muted' : (up ? 'up' : 'down')) + '">' +
                    arrowSvg(up) + ' ' + (delta > 0 ? '+' : '') + delta + '% ' + label + '</span>';
            }
            function animateNumber(el, target, fmt) {
                const start = 0, dur = 700, t0 = performance.now();
                function step(t) {
                    const p = Math.min((t - t0) / dur, 1);
                    const ease = 1 - Math.pow(1 - p, 3);
                    const val = Math.round(start + (target - start) * ease);
                    el.textContent = fmt ? fmt(val) : val.toLocaleString('id-ID');
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }

            const FEED_ICONS = {
                'user-plus':        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>',
                'shopping-bag':     '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                'credit-card-check':'<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><polyline points="14 16 16 18 20 14"/></svg>',
                'credit-card-x':    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><line x1="14" x2="20" y1="15" y2="19"/><line x1="20" x2="14" y1="15" y2="19"/></svg>',
                'calendar-plus':    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M12 14v4M10 16h4"/></svg>',
                'qr-code':          '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/></svg>',
                'bell':             '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/></svg>'
            };

            // ── Overview (KPI) ──────────────────────────────────
            function renderOverview(o) {
                animateNumber(document.getElementById('kpiTotal'), o.totalMembers);
                animateNumber(document.getElementById('kpiActive'), o.activeMembers);
                animateNumber(document.getElementById('kpiInactive'), o.inactiveMembers);
                animateNumber(document.getElementById('kpiRevenue'), Math.round(o.revenue), fmtMoney);
                animateNumber(document.getElementById('kpiBookings'), o.bookings);
                animateNumber(document.getElementById('kpiCheckins'), o.checkins);

                document.getElementById('kpiTotalMeta').outerHTML =
                    '<div class="ftm-kpi-meta ' + (o.newMembersDelta >= 0 ? 'up' : 'down') + '" id="kpiTotalMeta">' +
                    arrowSvg(o.newMembersDelta >= 0) + ' ' + (o.newMembersDelta > 0 ? '+' : '') + o.newMembersDelta +
                    '% · ' + o.newMembers + ' member baru</div>';

                document.getElementById('kpiActiveMeta').outerHTML =
                    '<div class="ftm-kpi-meta muted" id="kpiActiveMeta"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ' + o.retention + '% retention rate</div>';

                document.getElementById('kpiInactiveMeta').outerHTML =
                    '<div class="ftm-kpi-meta ' + (o.churn >= 12 ? 'down' : 'muted') + '" id="kpiInactiveMeta">' +
                    arrowSvg(false) + ' churn ' + o.churn + '%</div>';

                document.getElementById('kpiRevenueMeta').outerHTML =
                    '<div class="ftm-kpi-meta ' + (o.revenueDelta >= 0 ? 'up' : 'down') + '" id="kpiRevenueMeta">' +
                    arrowSvg(o.revenueDelta >= 0) + ' ' + (o.revenueDelta > 0 ? '+' : '') + o.revenueDelta +
                    '% · ' + o.revenueCount + ' transaksi · rata-rata ' + fmtMoney(o.revenueAvg) + '</div>';

                document.getElementById('kpiBookingsMeta').outerHTML =
                    '<div class="ftm-kpi-meta ' + (o.bookingsDelta >= 0 ? 'up' : 'down') + '" id="kpiBookingsMeta">' +
                    arrowSvg(o.bookingsDelta >= 0) + ' ' + (o.bookingsDelta > 0 ? '+' : '') + o.bookingsDelta + '% vs periode lalu</div>';

                document.getElementById('kpiCheckinsMeta').outerHTML =
                    '<div class="ftm-kpi-meta ' + (o.checkinsDelta >= 0 ? 'up' : 'down') + '" id="kpiCheckinsMeta">' +
                    arrowSvg(o.checkinsDelta >= 0) + ' ' + (o.checkinsDelta > 0 ? '+' : '') + o.checkinsDelta + '% vs periode lalu</div>';
            }

            // ── Charts ──────────────────────────────────────────
            function baseOpts(tooltipFmt) {
                return {
                    chart: {
                        type: 'area', toolbar: { show: false }, zoom: { enabled: true },
                        fontFamily: 'Poppins, sans-serif', animations: { speed: 500 }
                    },
                    dataLabels: { enabled: false },
                    stroke: { width: 2.5, curve: 'smooth' },
                    colors: [C.pink],
                    grid: { borderColor: C.grid },
                    xaxis: { labels: { style: { colors: C.ink, fontSize: '11px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                    yaxis: { labels: { style: { colors: C.ink, fontSize: '11px' } } },
                    tooltip: {
                        theme: 'dark', style: { fontSize: '12px' },
                        y: { formatter: tooltipFmt || function (v) { return v; } }
                    },
                    legend: { show: false }
                };
            }

            function buildChart(id, opts) {
                const el = document.getElementById(id);
                if (!el || typeof ApexCharts === 'undefined') return null;
                el.classList.remove('ftm-chart-skel');
                el.innerHTML = '';
                charts[id] = new ApexCharts(el, opts);
                charts[id].render();
                return charts[id];
            }

            function renderCharts(data) {
                if (typeof ApexCharts === 'undefined') return;
                const labels = data.labels || [];

                const series = [
                    { name: 'Pendapatan', data: data.revenue || [] },
                    { name: 'Member Baru', data: data.newMembers || [] },
                    { name: 'Booking', data: data.bookings || [] },
                    { name: 'Check-in', data: data.checkins || [] }
                ];

                // Member Baru
                let o = baseOpts();
                o.chart.type = 'area';
                o.series = [{ name: 'Member Baru', data: series[1].data }];
                o.labels = labels;
                o.colors = [C.pink];
                o.fill = { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0 } };
                if (charts['members']) { charts['members'].updateOptions({ labels }); charts['members'].updateSeries([{ name: 'Member Baru', data: series[1].data }]); }
                else buildChart('chartMembers', o);

                // Pendapatan
                o = baseOpts();
                o.chart.type = 'bar';
                o.chart.bar = { borderRadius: 6, columnWidth: '55%' };
                o.series = [{ name: 'Pendapatan', data: series[0].data }];
                o.labels = labels;
                o.colors = [C.cherry];
                o.tooltip.y.formatter = function (v) { return fmtMoney(v); };
                if (charts['revenue']) { charts['revenue'].updateOptions({ labels }); charts['revenue'].updateSeries([{ name: 'Pendapatan', data: series[0].data }]); }
                else buildChart('chartRevenue', o);

                // Booking Kelas
                o = baseOpts();
                o.chart.type = 'bar';
                o.chart.bar = { borderRadius: 6, columnWidth: '55%' };
                o.series = [{ name: 'Booking', data: series[2].data }];
                o.labels = labels;
                o.colors = [C.green];
                if (charts['bookings']) { charts['bookings'].updateOptions({ labels }); charts['bookings'].updateSeries([{ name: 'Booking', data: series[2].data }]); }
                else buildChart('chartBookings', o);

                // Check-in
                o = baseOpts();
                o.chart.type = 'area';
                o.series = [{ name: 'Check-in', data: series[3].data }];
                o.labels = labels;
                o.colors = [C.amber];
                o.fill = { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0 } };
                if (charts['checkins']) { charts['checkins'].updateOptions({ labels }); charts['checkins'].updateSeries([{ name: 'Check-in', data: series[3].data }]); }
                else buildChart('chartCheckins', o);
            }

            // ── Activity feed ────────────────────────────────────
            function renderActivity(items) {
                const feed = document.getElementById('ftmFeed');
                if (!items || !items.length) {
                    feed.innerHTML = '<div class="ftm-notif-empty">Belum ada aktivitas</div>';
                    return;
                }
                feed.innerHTML = items.map(a =>
                    '<div class="ftm-feed-item">' +
                        '<div class="ftm-feed-icon ' + esc(a.color || 'cherry') + '">' + (FEED_ICONS[a.icon] || FEED_ICONS.bell) + '</div>' +
                        '<div class="ftm-feed-body">' +
                            '<p class="ftm-feed-title">' + esc(a.title) + '</p>' +
                            '<p class="ftm-feed-sub">' + esc(a.text || '') + '</p>' +
                        '</div>' +
                        '<span class="ftm-feed-time">' + esc(relTime(a.time)) + '</span>' +
                    '</div>'
                ).join('');
            }

            // ── Recent members table ─────────────────────────────
            function renderRecentMembers(list) {
                const body = document.getElementById('ftmRecentBody');
                const empty = document.getElementById('ftmRecentEmpty');
                if (!list || !list.length) {
                    body.innerHTML = '';
                    empty.style.display = 'block';
                    return;
                }
                empty.style.display = 'none';
                body.innerHTML = list.map(m =>
                    '<tr>' +
                        '<td style="padding-left:1.25rem;"><div class="ftm-table-name">' + esc(m.name || 'N/A') + '</div></td>' +
                        '<td><span class="ftm-table-email">' + esc(m.email || '—') + '</span></td>' +
                        '<td><span class="ftm-table-email">' + esc(m.package || '—') + '</span></td>' +
                        '<td>' + esc(m.created_at_label || '—') + '</td>' +
                        '<td><span class="ftm-status ' + (m.is_active ? 'active' : 'inactive') + '">' + (m.is_active ? 'Aktif' : 'Tidak Aktif') + '</span></td>' +
                        '<td style="text-align:right; padding-right:1.25rem;"><a href="' + esc(m.url) + '" class="ftm-row-action" style="text-decoration:none;" aria-label="Detail">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>' +
                        '</a></td>' +
                    '</tr>'
                ).join('');
            }

            // ── Fetch & render ───────────────────────────────────
            function loadAnalytics() {
                const p = new URLSearchParams({ range: state.range });
                if (state.range === 'custom' && state.from) p.set('from', state.from);
                if (state.range === 'custom' && state.to) p.set('to', state.to);

                fetch(ANALYTICS_URL + '?' + p.toString(), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'include'
                })
                .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
                .then(data => {
                    if (!data.success) throw new Error('invalid');
                    renderOverview(data.overview);
                    renderCharts(data.charts);
                    renderActivity(data.activity);
                    renderRecentMembers(data.recentMembers);
                    document.getElementById('ftmLastUpdated').textContent = 'Diperbarui ' +
                        new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    state.firstLoad = false;
                })
                .catch(function () {
                    if (state.firstLoad) {
                        document.getElementById('ftmLastUpdated').textContent = 'Gagal memuat data';
                    }
                })
                .finally(function () {
                    clearTimeout(state.timer);
                    state.timer = setTimeout(loadAnalytics, 30000);
                });
            }

            // ── Export PNG / CSV ─────────────────────────────────
            document.querySelectorAll('[data-export]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const chart = charts[this.getAttribute('data-chart')];
                    if (!chart) return;
                    const format = this.getAttribute('data-export') === 'png' ? 'png' : 'csv';
                    chart.exportChart(format, { filename: 'ftm-' + this.getAttribute('data-chart') + '-' + Date.now() });
                });
            });

            // ── Range filter ─────────────────────────────────────
            const btnCustom = document.getElementById('ftmBtnCustom');
            document.querySelectorAll('#ftmFilters .ftm-filter-btn').forEach(function (btn) {
                if (btn.id === 'ftmApplyCustom') return;
                btn.addEventListener('click', function () {
                    document.querySelectorAll('#ftmFilters .ftm-filter-btn').forEach(function (b) { b.classList.remove('active'); });
                    this.classList.add('active');
                    state.range = this.getAttribute('data-range');
                    if (state.range === 'custom') {
                        document.getElementById('ftmCustomDates').style.display = 'inline-flex';
                    } else {
                        document.getElementById('ftmCustomDates').style.display = 'none';
                    }
                    loadAnalytics();
                });
            });
            document.getElementById('ftmApplyCustom').addEventListener('click', function () {
                state.from = document.getElementById('ftmFromDate').value;
                state.to   = document.getElementById('ftmToDate').value;
                loadAnalytics();
            });

            // ── Search real-time ─────────────────────────────────
            const searchInput = document.getElementById('ftmSearchInput');
            const searchBox   = document.getElementById('ftmSearchResults');
            let debounce = null;

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

            // ── Start ────────────────────────────────────────────
            loadAnalytics();
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

            // SVG icon set per type
            const ICONS = {
                'user-plus':         '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>',
                'user-check':        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>',
                'shopping-bag':      '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                'credit-card-check': '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><polyline points="14 16 16 18 20 14"/></svg>',
                'credit-card-x':     '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><line x1="14" x2="20" y1="15" y2="19"/><line x1="20" x2="14" y1="15" y2="19"/></svg>',
                'calendar-plus':     '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M12 14v4M10 16h4"/></svg>',
                'qr-code':           '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-1"/></svg>',
                'ticket':            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>',
                'clock-alert':       '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                'package-x':         '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><line x1="3.27" x2="12" y1="6.96" y2="12.01"/><line x1="12" x2="12" y1="22.08" y2="12"/></svg>',
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
                            <div class="ftm-notif-icon ${escapeHtml(n.color || 'cherry')}">${ic}</div>
                            <div class="ftm-notif-body">
                                <p class="ftm-notif-title">${escapeHtml(n.title)}</p>
                                <p class="ftm-notif-msg">${escapeHtml(n.message)}</p>
                                <p class="ftm-notif-time">${escapeHtml(n.time_human || '')}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                // attach click
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

            // initial + polling tiap 30 detik
            fetchFeed();
            setInterval(fetchFeed, 30000);
        })();

        // ═══════════ MODAL LOGIC (preserved 100%) ═══════════
        let currentFilter = 'all';

        function openCustomerModal(filterType = 'all') {
            currentFilter = filterType;
            document.getElementById('customerModal').classList.add('active');
            updateModalTitle(filterType);
            loadCustomers(filterType);
        }
        function closeCustomerModal() {
            document.getElementById('customerModal').classList.remove('active');
        }
        function updateModalTitle(filterType) {
            const titles = {
                'all': 'Daftar Semua Member',
                'active': 'Member Aktif',
                'inactive': 'Member Tidak Aktif',
            };
            document.getElementById('modalTitle').textContent = titles[filterType] || 'Member';
        }
        function filterCustomers(filterType) {
            currentFilter = filterType;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            updateModalTitle(filterType);
            loadCustomers(filterType);
        }
        function loadCustomers(filterType) {
            const container = document.getElementById('customerListContainer');
            container.innerHTML = '<div class="loading"><div class="loading-spinner"></div><p>Memuat data...</p></div>';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            fetch(`/api/customers?status=${filterType}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include'
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.customers && data.customers.length > 0) {
                    renderCustomerList(data.customers, filterType);
                } else {
                    const msg = {
                        'all': 'Tidak ada member',
                        'active': 'Tidak ada member aktif',
                        'inactive': 'Tidak ada member tidak aktif'
                    };
                    container.innerHTML = `<div class="empty-state-modal"><div class="empty-icon-modal">📭</div><h3>Tidak Ada Data</h3><p>${msg[filterType] || 'Tidak ada data'}</p></div>`;
                }
            })
            .catch(err => {
                container.innerHTML = `<div class="empty-state-modal"><div class="empty-icon-modal">⚠️</div><h3>Error</h3><p>Gagal memuat data. (${err.message})</p></div>`;
            });
        }
        function renderCustomerList(customers, filterType) {
            const container = document.getElementById('customerListContainer');
            let html = '<div class="modal-filters">';
            html += `<button class="filter-btn ${currentFilter === 'all' ? 'active' : ''}" onclick="filterCustomers('all')">Semua Member</button>`;
            html += `<button class="filter-btn ${currentFilter === 'active' ? 'active' : ''}" onclick="filterCustomers('active')">Member Aktif</button>`;
            html += `<button class="filter-btn ${currentFilter === 'inactive' ? 'active' : ''}" onclick="filterCustomers('inactive')">Tidak Aktif</button>`;
            html += '</div><ul class="customer-list">';

            customers.forEach(c => {
                const isActive = c.is_login_active === 1;
                const statusBadge = isActive
                    ? '<span class="customer-status active">Aktif</span>'
                    : '<span class="customer-status inactive">Tidak Aktif</span>';
                const phone = c.phone_number || '—';
                const joinDate = c.created_at || '—';
                const lastActive = c.last_activity || '—';
                let waBtn = '';
                if (c.phone_number && c.whatsapp_url) {
                    waBtn = `<a href="${c.whatsapp_url}" target="_blank" class="action-link whatsapp">💬 WhatsApp</a>`;
                }
                html += `
                <li class="customer-item">
                    <div class="customer-item-header">
                        <div class="customer-name">${c.name || 'N/A'}</div>
                        ${statusBadge}
                    </div>
                    <div class="customer-info">
                        <div class="customer-info-item"><span class="customer-info-label">Email</span><span class="customer-info-value">${c.email || '—'}</span></div>
                        <div class="customer-info-item"><span class="customer-info-label">Telepon</span><span class="customer-info-value">${phone}</span></div>
                        <div class="customer-info-item"><span class="customer-info-label">Bergabung</span><span class="customer-info-value">${joinDate}</span></div>
                        <div class="customer-info-item"><span class="customer-info-label">Aktivitas Terakhir</span><span class="customer-info-value">${lastActive}</span></div>
                    </div>
                    <div class="customer-actions">
                        <a href="/filament/resources/customers/${c.id}" class="action-link view">Lihat Detail</a>
                        ${waBtn}
                    </div>
                </li>`;
            });
            html += '</ul>';
            container.innerHTML = html;
        }
        document.getElementById('customerModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeCustomerModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeCustomerModal();
        });
    </script>
</x-filament::page>
