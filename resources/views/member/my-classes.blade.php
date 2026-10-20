<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classes | FTM Society</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">

    <style>
        :root {
            --pink: #EE4E8B;
            --cherry: #7A2B4A;
            --petal: #F4C9DF;
            --green: #1A7A5E;
            --ivy: #1D5A4B;
            --rising: #FCF9F2;
            --layl: #1C1C1C;
            --card-bg: #FFFDF9;
            --section-bg: #F6D9E3;
            --caption: #555555;
            --springs-light: #E1F5EE;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--rising);
            color: var(--layl);
            -webkit-font-smoothing: antialiased;
        }

        .main-content {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* -- Masthead (konsisten dgn halaman Profil / Absensi / Transaksi) -- */
        .mc-masthead {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
            padding: .25rem 0 1.05rem;
            position: relative;
            margin-bottom: 1.5rem;
            animation: mcIn .45s ease both;
        }

        .mc-masthead::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(122, 43, 74, .25), rgba(238, 78, 139, .12), transparent 70%);
        }

        .mc-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--cherry);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .mc-eyebrow i { color: var(--pink); font-size: .85rem; }

        .mc-masthead h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--cherry);
            letter-spacing: -0.01em;
            line-height: 1.2;
            margin-top: .35rem;
        }

        .mc-masthead .mc-sub {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: var(--caption);
            line-height: 1.5;
            margin-top: 4px;
        }

        .mc-today-chip {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(122, 43, 74, .16);
            background: rgba(255, 255, 255, .85);
            box-shadow: 0 8px 20px rgba(122, 43, 74, .06);
            white-space: nowrap;
        }

        .mc-today-dot {
            width: .6rem;
            height: .6rem;
            border-radius: 999px;
            background: var(--pink);
            box-shadow: 0 0 0 4px rgba(238, 78, 139, .15);
        }

        .mc-today-label { color: rgba(28, 28, 28, .45); font-size: .75rem; }
        .mc-today-value { color: var(--layl); font-weight: 600; font-size: .78rem; }

        /* -- SIGNATURE: panel "Sesi Berikutnya" -- */
        .mc-next {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
            border-radius: 22px;
            background: linear-gradient(135deg, #7A2B4A 0%, #5A1F37 62%, #1C1C1C 100%);
            color: #fff;
            box-shadow: 0 16px 40px rgba(122, 43, 74, 0.30);
            animation: mcIn .5s ease .08s both;
        }

        .mc-next::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -50px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(238, 78, 139, .35) 0%, transparent 70%);
            pointer-events: none;
        }

        .mc-next-cal {
            position: relative;
            width: 92px;
            flex-shrink: 0;
            text-align: center;
            padding: .7rem .5rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, .09);
            border: 1px solid rgba(255, 255, 255, .18);
        }

        .mc-next-day {
            display: block;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.02em;
            color: #fff;
        }

        .mc-next-mon {
            display: block;
            margin-top: .3rem;
            font-size: .66rem;
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--petal);
        }

        .mc-next-body { flex: 1; min-width: 0; position: relative; }

        .mc-next-eyebrow {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--petal);
        }

        .mc-next-eyebrow .cd {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .15rem .55rem;
            border-radius: 999px;
            background: rgba(238, 78, 139, .28);
            border: 1px solid rgba(244, 201, 223, .35);
            color: #fff;
            font-size: 10px;
            letter-spacing: .06em;
        }

        .mc-next-eyebrow .cd-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--pink);
            animation: mcPing 1.8s ease-out infinite;
        }

        .mc-next-title {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -0.01em;
            margin-top: .45rem;
        }

        .mc-next-meta {
            margin-top: .4rem;
            font-size: .86rem;
            color: rgba(255, 255, 255, .78);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem .9rem;
        }

        .mc-next-meta i { color: var(--petal); font-size: .78rem; margin-right: .3rem; }

        .mc-next-pkg {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .72rem;
            font-weight: 600;
            padding: .2rem .6rem;
            border-radius: 999px;
            background: rgba(197, 215, 155, .18);
            border: 1px solid rgba(197, 215, 155, .4);
            color: #C5D79B;
        }

        .mc-next-side {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .65rem;
            flex-shrink: 0;
        }

        .mc-next-who { display: flex; align-items: center; gap: .55rem; }

        .mc-next-who .ava {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pink), #B83863);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, .35);
            flex-shrink: 0;
        }

        .mc-next-who .who-label {
            font-size: 9.5px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .5);
            line-height: 1.3;
        }

        .mc-next-who .who-name { font-size: .85rem; font-weight: 600; color: #fff; line-height: 1.3; }

        .mc-next-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .8rem;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(26, 122, 94, .45);
            border: 1px solid rgba(197, 215, 155, .45);
            color: #DDF3EA;
        }

        .mc-next-badge .d {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #7BE0BE;
        }

        /* -- Toolbar filter paket -- */
        .mc-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: .25rem;
        }

        .mc-toolbar-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(28, 28, 28, .45);
        }

        .mc-select-wrap {
            position: relative;
            width: 100%;
            max-width: 320px;
        }

        .mc-select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            background: #FFFFFF;
            border: 1px solid #E8C0D4;
            border-radius: 12px;
            padding: .65rem 2.5rem .65rem 1.1rem;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--layl);
            cursor: pointer;
            outline: none;
            box-shadow: 0 2px 8px rgba(122, 43, 74, .06);
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .mc-select:hover { border-color: var(--petal); }

        .mc-select:focus-visible {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(238, 78, 139, .18);
        }

        .mc-select-wrap i {
            pointer-events: none;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cherry);
            font-size: .8rem;
        }

        /* -- Judul seksi bergaris -- */
        .mc-sec {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin: 1.75rem 0 1rem;
        }

        .mc-sec-label {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--cherry);
            white-space: nowrap;
        }

        .mc-sec-rule {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(122, 43, 74, .28), rgba(244, 201, 223, .5));
        }

        .mc-sec-count {
            font-size: .72rem;
            font-weight: 600;
            color: rgba(28, 28, 28, .55);
            background: #fff;
            border: 1px solid rgba(122, 43, 74, .15);
            padding: .2rem .65rem;
            border-radius: 999px;
            white-space: nowrap;
        }

        @keyframes mcIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        @keyframes mcPing { 0% { box-shadow: 0 0 0 0 rgba(238, 78, 139, .55); } 70% { box-shadow: 0 0 0 7px rgba(238, 78, 139, 0); } 100% { box-shadow: 0 0 0 0 rgba(238, 78, 139, 0); } }

        @media (prefers-reduced-motion: reduce) {
            .mc-masthead,
            .mc-next,
            .mc-next-eyebrow .cd-dot { animation: none; }
        }

        .filter-empty {
            display: none;
            text-align: center;
            padding: 2.5rem 1.5rem;
            grid-column: 1 / -1;
        }

        .filter-empty.visible {
            display: block;
        }

        .filter-empty .fe-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--petal), rgba(238,78,139,0.06));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--cherry);
            margin: 0 auto 0.75rem;
        }

        .filter-empty h4 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--cherry);
            margin-bottom: 0.25rem;
        }

        .filter-empty p {
            font-size: 13px;
            color: var(--caption);
        }

        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.25rem;
        }

        .class-card {
            background: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #E8C0D4;
            box-shadow: 0 4px 16px rgba(122,43,74,0.10), 0 1px 4px rgba(122,43,74,0.06);
            transition: opacity 0.25s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .class-card:hover {
            box-shadow: 0 8px 28px rgba(122,43,74,0.14), 0 2px 8px rgba(122,43,74,0.08);
            transform: translateY(-2px);
        }

        .card-body {
            padding: 1.25rem;
            border: none !important;
            outline: none !important;
        }

        .card-header-row {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            margin-bottom: 0.85rem;
            border: none !important;
            outline: none !important;
        }

        .class-icon-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .card-title-area {
            flex: 1;
            min-width: 0;
            border: none !important;
            outline: none !important;
        }

        .card-class-name {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--cherry);
            line-height: 1.2;
            margin-bottom: 0.1rem;
            border: none !important;
            outline: none !important;
        }

        .card-subtitle {
            font-family: 'Instrument Serif', Georgia, serif;
            font-size: 14px;
            font-style: normal;
            line-height: 1.45;
            color: var(--caption);
            border: none !important;
            outline: none !important;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            padding: 0.75rem 0;
            margin-bottom: 0;
            border: none !important;
            outline: none !important;
        }

        .sched-cell {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .sched-cell .s-label {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--caption);
            line-height: 1.4;
        }

        .sched-cell .s-value {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--layl);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            line-height: 1.45;
        }

        .sched-cell .s-value i {
            font-size: 12px;
            color: var(--pink);
            width: 16px;
            text-align: center;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.75rem;
            border: none !important;
            outline: none !important;
        }

        .instructor-group {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .instructor-group .iavatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--cherry);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .instructor-group .iinfo .ilabel {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--caption);
            line-height: 1.4;
        }

        .instructor-group .iinfo .iname {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--layl);
            line-height: 1.45;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            background: var(--green);
            color: var(--springs-light);
            border: none !important;
            outline: none !important;
        }

        .status-pill .sdot {
            width: 5px;
            height: 5px;
            background: var(--springs-light);
            border-radius: 50%;
        }

        .empty-card {
            text-align: center;
            padding: 3rem 2rem;
            max-width: 380px;
            margin: 0 auto;
            border: none !important;
            outline: none !important;
        }

        .empty-card .eicon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--petal), rgba(238,78,139,0.08));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--cherry);
            margin: 0 auto 1rem;
        }

        .empty-card h3 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--cherry);
            margin-bottom: 0.4rem;
        }

        .empty-card p {
            font-size: 14px;
            color: var(--caption);
            margin-bottom: 1.5rem;
        }

        .btn-pink {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.75rem 1.5rem;
            background: var(--pink);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 3px 12px rgba(238,78,139,0.2);
            transition: background 0.2s ease;
        }

        .btn-pink:hover {
            background: var(--cherry);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.25rem;
                margin-top: 3.5rem;
            }

            .mc-masthead { margin-bottom: 1.25rem; }
            .mc-masthead h1 { font-size: 1.4rem !important; }
            .mc-masthead .mc-sub { font-size: 13px; }

            .mc-next {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.25rem;
            }

            .mc-next-cal {
                width: auto;
                display: inline-flex;
                align-items: baseline;
                gap: .45rem;
                padding: .45rem .9rem;
            }

            .mc-next-day { font-size: 1.7rem; }
            .mc-next-mon { margin-top: 0; }
            .mc-next-title { font-size: 1.25rem; }

            .mc-next-side {
                align-items: flex-start;
                width: 100%;
            }

            .mc-toolbar { flex-direction: column; align-items: stretch; }
            .mc-select-wrap { max-width: none; }

            .mc-sec { margin-top: 1.5rem; }
            .mc-sec-label { font-size: .95rem; }

            .classes-grid { grid-template-columns: 1fr; gap: 0.75rem; }

            .card-body { padding: 1rem; }

            .schedule-grid {
                gap: 0.35rem;
            }

            .card-footer {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
</head>

<body>

<div class="flex h-screen">
    @include('partials.member-sidebar')

    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="flex-1 overflow-y-auto">
        <div class="main-content">

            <!-- Page masthead -->
            <div class="mc-masthead">
                <div>
                    <p class="mc-eyebrow"><i class="fas fa-calendar-days"></i> Member Area &middot; Jadwal</p>
                    <h1>Kelas Saya</h1>
                    <p class="mc-sub">Sesi yang Anda booking — dari jadwal mendatang sampai riwayat latihan.</p>
                </div>
                <div class="mc-today-chip">
                    <span class="mc-today-dot"></span>
                    <span class="mc-today-label">Hari ini</span>
                    <span class="mc-today-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                </div>
            </div>

            {{-- -- SIGNATURE: panel Sesi Berikutnya -- --}}
            @if(!$myClasses->isEmpty())
                @php
                    $nextItem = $myClasses->first();
                    $nextSched = $nextItem->schedule;

                    $nextInstructor = $nextSched->instructor ?? 'FTM Coach';
                    $nextWords = explode(' ', $nextInstructor);
                    $nextInitials = '';
                    foreach ($nextWords as $w) { $nextInitials .= strtoupper(substr($w, 0, 1)); }
                    if (strlen($nextInitials) > 2) $nextInitials = substr($nextInitials, 0, 2);

                    $nextPkg = $nextItem->package_info['name'] ?? 'Membership';

                    $nextDate = null;
                    if ($nextSched->schedule_date) {
                        $nextDate = \Carbon\Carbon::parse($nextSched->schedule_date);
                    } else {
                        try { $nextDate = \Carbon\Carbon::parse($nextSched->schedule_date_formatted); } catch (\Exception $e) { $nextDate = null; }
                    }

                    $cdLabel = 'Segera';
                    if ($nextDate) {
                        $daysUntil = (int) \Carbon\Carbon::today()->diffInDays($nextDate->copy()->startOfDay());
                        $cdLabel = $daysUntil <= 0 ? 'Hari ini' : ($daysUntil === 1 ? 'Besok' : $daysUntil . ' hari lagi');
                    }
                @endphp
                <section class="mc-next">
                    <div class="mc-next-cal">
                        <span class="mc-next-day">{{ $nextDate ? $nextDate->format('d') : '--' }}</span>
                        <span class="mc-next-mon">{{ $nextDate ? $nextDate->format('M') : strtoupper(substr($nextSched->day ?? '', 0, 3)) }}</span>
                    </div>
                    <div class="mc-next-body">
                        <p class="mc-next-eyebrow">
                            <i class="fas fa-bolt" style="color:#EE4E8B;"></i> Sesi Berikutnya
                            <span class="cd"><span class="cd-dot"></span>{{ $cdLabel }}</span>
                        </p>
                        <h2 class="mc-next-title">{{ $nextSched->classModel->class_name ?? 'Class' }}</h2>
                        <div class="mc-next-meta">
                            <span><i class="far fa-calendar"></i>{{ $nextSched->day ?? '-' }}, {{ $nextSched->schedule_date_formatted }}</span>
                            <span><i class="far fa-clock"></i>{{ \Carbon\Carbon::parse($nextSched->class_time)->format('H:i') }}</span>
                            <span class="mc-next-pkg"><i class="fas fa-box-open"></i>{{ $nextPkg }}</span>
                        </div>
                    </div>
                    <div class="mc-next-side">
                        <div class="mc-next-who">
                            <div class="ava">{{ $nextInitials }}</div>
                            <div>
                                <div class="who-label">Instruktur</div>
                                <div class="who-name">{{ $nextInstructor }}</div>
                            </div>
                        </div>
                        <span class="mc-next-badge"><span class="d"></span>Terkonfirmasi</span>
                    </div>
                </section>
            @endif

            @if($myClasses->isEmpty() && $pastClasses->isEmpty())
                <div class="empty-card">
                    <div class="eicon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                    <h3>Belum Ada Kelas Terjadwal</h3>
                    <p>Anda belum booking kelas apapun.</p>
                    <a href="{{ route('member.book') }}" class="btn-pink">
                        <i class="fas fa-plus-circle"></i> Booking Kelas Pertama
                    </a>
                </div>
            @else
                @php
                    $allPkgs = $activePackages->pluck('package.name')->unique()->filter()->values();
                @endphp

                <div class="mc-toolbar">
                    <span class="mc-toolbar-label"><i class="fas fa-filter" style="margin-right:5px;"></i>Filter paket</span>
                    <div class="mc-select-wrap">
                        <select id="package-filter-select" class="mc-select" onchange="applyFilter(this.value)">
                            <option value="all" selected>Semua Kelas ({{ $myClasses->count() + $pastClasses->count() }})</option>
                            @foreach($allPkgs as $pkgName)
                                <option value="{{ Str::slug($pkgName) }}">{{ $pkgName }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                @if(!$myClasses->isEmpty())
                <div class="mc-sec">
                    <span class="mc-sec-label">Sesi Terjadwal</span>
                    <span class="mc-sec-rule"></span>
                    <span class="mc-sec-count">{{ $myClasses->count() }} sesi</span>
                </div>

                <div class="classes-grid" id="classesGrid">
                    @foreach($myClasses as $item)
                        @php
                            $className = $item->schedule->classModel->class_name ?? 'Class';
                            $classNameLower = strtolower($className);
                            $classIcon = 'fa-dumbbell';
                            $classType = 'Fitness';

                            if (str_contains($classNameLower, 'reformer pilates')) {
                                $classIcon = 'fa-dumbbell'; $classType = 'Reformer Pilates';
                            } elseif (str_contains($classNameLower, 'mat pilates')) {
                                $classIcon = 'fa-person-praying'; $classType = 'Mat Pilates';
                            } elseif (str_contains($classNameLower, 'pilates')) {
                                $classIcon = 'fa-spa'; $classType = 'Pilates';
                            } elseif (str_contains($classNameLower, 'muaythai')) {
                                $classIcon = 'fa-hand-fist'; $classType = 'Muaythai';
                            } elseif (str_contains($classNameLower, 'boxing')) {
                                $classIcon = 'fa-fire'; $classType = 'Boxing';
                            } elseif (str_contains($classNameLower, 'body shaping')) {
                                $classIcon = 'fa-heart-pulse'; $classType = 'Body Shaping';
                            } elseif (str_contains($classNameLower, 'mix')) {
                                $classIcon = 'fa-layer-group'; $classType = 'Mix Class';
                            } elseif (str_contains($classNameLower, 'yoga')) {
                                $classIcon = 'fa-person-praying'; $classType = 'Yoga';
                            } elseif (str_contains($classNameLower, 'private')) {
                                $classIcon = 'fa-crown'; $classType = 'Private';
                            } elseif (str_contains($classNameLower, 'single') || str_contains($classNameLower, 'visit')) {
                                $classIcon = 'fa-ticket'; $classType = 'Single Visit';
                            } elseif (str_contains($classNameLower, 'exclusive')) {
                                $classIcon = 'fa-gem'; $classType = 'Exclusive';
                            }

                            $instructor = $item->schedule->instructor ?? 'FTM Coach';
                            $words = explode(' ', $instructor);
                            $initials = '';
                            foreach ($words as $word) { $initials .= strtoupper(substr($word, 0, 1)); }
                            if (strlen($initials) > 2) $initials = substr($initials, 0, 2);

                            $pkgInfo = $item->package_info ?? [];
                            $pkgName = $pkgInfo['name'] ?? 'Membership';
                            $pkgSlug = Str::slug($pkgName);
                        @endphp

                        <div class="class-card" data-package="{{ $pkgSlug }}">
                            <div class="card-body">
                                <div class="card-header-row">
                                    <div class="class-icon-badge" style="background: var(--green);">
                                        <i class="fas {{ $classIcon }}"></i>
                                    </div>
                                    <div class="card-title-area">
                                        <div class="card-class-name">{{ $className }}</div>
                                        <div class="card-subtitle">
                                            <i class="fas fa-box-open" style="font-size: 11px; margin-right: 0.25rem; color: var(--pink);"></i> {{ $pkgName }}
                                        </div>
                                    </div>
                                </div>

                                <div class="schedule-grid">
                                    <div class="sched-cell">
                                        <span class="s-label">Hari</span>
                                        <span class="s-value"><i class="fas fa-calendar-alt"></i>{{ $item->schedule->day }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Tanggal</span>
                                        <span class="s-value"><i class="fas fa-calendar-day"></i>{{ $item->schedule->schedule_date_formatted }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Waktu</span>
                                        <span class="s-value"><i class="fas fa-clock"></i>{{ \Carbon\Carbon::parse($item->schedule->class_time)->format('H:i') }}</span>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="instructor-group">
                                        <div class="iavatar">{{ $initials }}</div>
                                        <div class="iinfo">
                                            <div class="ilabel">Instruktur</div>
                                            <div class="iname">{{ $instructor }}</div>
                                        </div>
                                    </div>
                                    <div class="status-pill">
                                        <span class="sdot"></span> Terkonfirmasi
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="filter-empty" id="filterEmpty">
                        <div class="fe-icon">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                        <h4>Tidak ada kelas di paket ini</h4>
                        <p>Ganti ke paket lain untuk melihat jadwalnya.</p>
                    </div>
                </div>
                @endif

                @if(!$pastClasses->isEmpty())
                <div class="mc-sec">
                    <span class="mc-sec-label">Riwayat Kelas</span>
                    <span class="mc-sec-rule"></span>
                    <span class="mc-sec-count">{{ $pastClasses->count() }} sesi</span>
                </div>

                <div class="classes-grid" id="pastClassesGrid" style="margin-top: 0;">
                    @foreach($pastClasses as $item)
                        @php
                            $className = $item->schedule->classModel->class_name ?? 'Class';
                            $classNameLower = strtolower($className);
                            $classIcon = 'fa-dumbbell';
                            $classType = 'Fitness';

                            if (str_contains($classNameLower, 'reformer pilates')) {
                                $classIcon = 'fa-dumbbell'; $classType = 'Reformer Pilates';
                            } elseif (str_contains($classNameLower, 'mat pilates')) {
                                $classIcon = 'fa-person-praying'; $classType = 'Mat Pilates';
                            } elseif (str_contains($classNameLower, 'pilates')) {
                                $classIcon = 'fa-spa'; $classType = 'Pilates';
                            } elseif (str_contains($classNameLower, 'muaythai')) {
                                $classIcon = 'fa-hand-fist'; $classType = 'Muaythai';
                            } elseif (str_contains($classNameLower, 'boxing')) {
                                $classIcon = 'fa-fire'; $classType = 'Boxing';
                            } elseif (str_contains($classNameLower, 'body shaping')) {
                                $classIcon = 'fa-heart-pulse'; $classType = 'Body Shaping';
                            } elseif (str_contains($classNameLower, 'mix')) {
                                $classIcon = 'fa-layer-group'; $classType = 'Mix Class';
                            } elseif (str_contains($classNameLower, 'yoga')) {
                                $classIcon = 'fa-person-praying'; $classType = 'Yoga';
                            } elseif (str_contains($classNameLower, 'private')) {
                                $classIcon = 'fa-crown'; $classType = 'Private';
                            } elseif (str_contains($classNameLower, 'single') || str_contains($classNameLower, 'visit')) {
                                $classIcon = 'fa-ticket'; $classType = 'Single Visit';
                            } elseif (str_contains($classNameLower, 'exclusive')) {
                                $classIcon = 'fa-gem'; $classType = 'Exclusive';
                            }

                            $instructor = $item->schedule->instructor ?? 'FTM Coach';
                            $words = explode(' ', $instructor);
                            $initials = '';
                            foreach ($words as $word) { $initials .= strtoupper(substr($word, 0, 1)); }
                            if (strlen($initials) > 2) $initials = substr($initials, 0, 2);

                            $pkgInfo = $item->package_info ?? [];
                            $pkgName = $pkgInfo['name'] ?? 'Membership';
                            $pkgSlug = Str::slug($pkgName);
                        @endphp

                        <div class="class-card" data-package="{{ $pkgSlug }}">
                            <div class="card-body">
                                <div class="card-header-row">
                                    <div class="class-icon-badge" style="background: var(--cherry); opacity: 0.75;">
                                        <i class="fas {{ $classIcon }}"></i>
                                    </div>
                                    <div class="card-title-area">
                                        <div class="card-class-name">{{ $className }}</div>
                                        <div class="card-subtitle">
                                            <i class="fas fa-box-open" style="font-size: 11px; margin-right: 0.25rem; color: var(--pink);"></i> {{ $pkgName }}
                                        </div>
                                    </div>
                                </div>

                                <div class="schedule-grid">
                                    <div class="sched-cell">
                                        <span class="s-label">Hari</span>
                                        <span class="s-value"><i class="fas fa-calendar-alt"></i>{{ $item->schedule->day }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Tanggal</span>
                                        <span class="s-value"><i class="fas fa-calendar-day"></i>{{ $item->schedule->schedule_date_formatted }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Waktu</span>
                                        <span class="s-value"><i class="fas fa-clock"></i>{{ \Carbon\Carbon::parse($item->schedule->class_time)->format('H:i') }}</span>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="instructor-group">
                                        <div class="iavatar">{{ $initials }}</div>
                                        <div class="iinfo">
                                            <div class="ilabel">Instruktur</div>
                                            <div class="iname">{{ $instructor }}</div>
                                        </div>
                                    </div>
                                    <div class="status-pill" style="background: var(--caption); color: #fff;">
                                        <span class="sdot"></span> Selesai
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            @endif
        </div>
    </main>
</div>

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
        document.body.style.overflow = 'hidden';
        if (hamburger) hamburger.style.display = 'none';
        document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
    } else {
        document.body.classList.remove('sidebar-open');
        document.body.style.overflow = '';
        if (hamburger) { hamburger.style.display = ''; hamburger.innerHTML = '<i class="fas fa-bars"></i>'; }
        document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#sidebar nav a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && sidebar.classList.contains('active')) toggleSidebar();
            }
        });
    });
});
// Package filter — professional filtering with transitions
function applyFilter(pkg) {
    var cards = document.querySelectorAll('.classes-grid .class-card');
    var emptyMsg = document.getElementById('filterEmpty');

    var visibleCount = 0;
    cards.forEach(function(card) {
        var match = pkg === 'all' || card.getAttribute('data-package') === pkg;
        if (match) {
            card.style.display = '';
            card.style.opacity = '0';
            requestAnimationFrame(function() {
                card.style.opacity = '1';
            });
            visibleCount++;
        } else {
            card.style.display = 'none';
            card.style.opacity = '0';
        }
    });

    if (emptyMsg) {
        emptyMsg.classList.toggle('visible', visibleCount === 0);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initial filter application for the dropdown
    const selectElement = document.getElementById('package-filter-select');
    if (selectElement) {
        applyFilter(selectElement.value);
    }
});

window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('active');
        if (hamburger) hamburger.style.display = '';
        if (hamburger) hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.style.overflow = '';
    }
});
</script>

</body>
</html>
