<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .status-pill.status-pill-pending {
            background: #FFF1DC;
            color: #9A5B00;
            border: 1px solid rgba(201, 123, 0, .25) !important;
        }

        .status-pill.status-pill-pending .sdot {
            background: #C97B00;
        }

        .rate-locked {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-family: 'Poppins', sans-serif;
            font-size: 11.5px;
            font-weight: 500;
            color: rgba(28, 28, 28, .45);
            line-height: 1.4;
        }

        .rate-locked i {
            font-size: 10px;
            color: #C97B00;
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

        /* ============================================
           PENILAIAN PELATIH
           ============================================ */
        .rate-row {
            margin-top: .85rem;
            padding-top: .8rem;
            border-top: 1px dashed rgba(122, 43, 74, .16);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .rate-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem 1.05rem;
            border-radius: 999px;
            border: 1px solid rgba(238, 78, 139, .3);
            background: rgba(238, 78, 139, .07);
            color: var(--pink);
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .16s ease, background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .rate-btn i { font-size: 11px; }

        .rate-btn:hover {
            background: var(--pink);
            border-color: var(--pink);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(238, 78, 139, .28);
        }

        .rate-btn:focus-visible,
        .rate-edit:focus-visible,
        .rate-star:focus-visible,
        .rate-cancel:focus-visible,
        .rate-submit:focus-visible,
        .rate-modal-close:focus-visible {
            outline: 2px solid var(--cherry);
            outline-offset: 2px;
        }

        .rate-done {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .rate-stars-static {
            display: inline-flex;
            gap: 2px;
        }

        .rate-stars-static i {
            font-size: 12px;
            color: rgba(122, 43, 74, .16);
        }

        .rate-stars-static i.is-on { color: var(--pink); }

        .rate-done .rate-done-label {
            font-family: 'Poppins', sans-serif;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--green);
        }

        .rate-edit {
            padding: 0;
            border: none;
            background: none;
            color: var(--cherry);
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 2px;
            cursor: pointer;
        }

        /* --- Modal --- */
        .rate-modal {
            position: fixed;
            inset: 0;
            z-index: 90;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity .22s ease, visibility .22s ease;
        }

        .rate-modal.open {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .rate-modal.open {
            opacity: 1;
            visibility: visible;
        }

        .rate-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(28, 28, 28, .55);
            backdrop-filter: blur(4px);
        }

        .rate-modal-card {
            position: relative;
            width: min(440px, 100%);
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            background: var(--rising);
            border: 1px solid rgba(238, 78, 139, .16);
            border-radius: 22px;
            padding: 1.75rem 1.6rem 1.5rem;
            box-shadow: 0 30px 70px rgba(28, 28, 28, .35);
            transform: translateY(14px) scale(.98);
            transition: transform .26s cubic-bezier(.2, .8, .2, 1);
        }

        .rate-modal.open .rate-modal-card { transform: none; }

        .rate-modal-close {
            position: absolute;
            top: .9rem;
            right: .9rem;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            border: 1px solid rgba(122, 43, 74, .16);
            background: #fff;
            color: var(--cherry);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }

        .rate-modal-close:hover {
            background: var(--cherry);
            border-color: var(--cherry);
            color: #fff;
        }

        .rate-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--pink);
        }

        .rate-modal-card h3 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.25;
            color: var(--cherry);
            margin-top: .35rem;
            padding-right: 1.75rem;
        }

        .rate-modal-card h3 span { color: var(--pink); }

        .rate-class-name {
            font-family: 'Instrument Serif', Georgia, serif;
            font-style: italic;
            font-size: 1rem;
            color: var(--caption);
            margin-top: .15rem;
        }

        .rate-stars {
            display: flex;
            justify-content: center;
            gap: .4rem;
            margin: 1.15rem 0 .35rem;
        }

        .rate-star {
            padding: .15rem;
            border: none;
            background: none;
            font-size: 1.9rem;
            line-height: 1;
            color: rgba(122, 43, 74, .17);
            cursor: pointer;
            transition: transform .15s ease, color .15s ease;
        }

        .rate-star.is-on { color: var(--pink); text-shadow: 0 4px 12px rgba(238, 78, 139, .35); }

        .rate-star.is-hover { transform: scale(1.12); }

        .rate-hint {
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: rgba(28, 28, 28, .45);
            margin-bottom: 1rem;
        }

        .rate-comment-label {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--cherry);
            margin-bottom: .35rem;
        }

        .rate-comment-label span {
            font-weight: 500;
            color: rgba(28, 28, 28, .4);
        }

        .rate-comment {
            width: 100%;
            min-height: 86px;
            resize: vertical;
            padding: .75rem .9rem;
            border-radius: 12px;
            border: 1px solid rgba(122, 43, 74, .18);
            background: #fff;
            font-family: 'Instrument Serif', Georgia, serif;
            font-size: 1.05rem;
            line-height: 1.5;
            color: var(--layl);
            transform: rotate(-1deg);
            transition: transform .22s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .rate-comment::placeholder {
            color: rgba(28, 28, 28, .35);
            font-style: italic;
        }

        .rate-comment:focus {
            outline: none;
            transform: rotate(0deg);
            border-color: var(--pink);
            box-shadow: 0 0 0 4px rgba(238, 78, 139, .12);
        }

        .rate-modal-actions {
            display: flex;
            gap: .6rem;
            margin-top: 1rem;
        }

        .rate-cancel {
            flex: 0 0 auto;
            padding: .65rem 1.1rem;
            border-radius: 999px;
            border: 1px solid rgba(122, 43, 74, .18);
            background: transparent;
            color: var(--cherry);
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s ease;
        }

        .rate-cancel:hover { background: rgba(122, 43, 74, .06); }

        .rate-submit {
            flex: 1;
            padding: .65rem 1.1rem;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--cherry), var(--pink));
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(238, 78, 139, .28);
            transition: transform .15s ease, box-shadow .2s ease, opacity .2s ease;
        }

        .rate-submit:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(238, 78, 139, .34);
        }

        .rate-submit:disabled { opacity: .55; cursor: default; }

        .rate-error {
            min-height: 1rem;
            margin-top: .5rem;
            font-family: 'Poppins', sans-serif;
            font-size: 11.5px;
            color: #C0264F;
        }

        @media (prefers-reduced-motion: reduce) {
            .rate-modal,
            .rate-modal-card,
            .rate-star,
            .rate-comment,
            .rate-btn,
            .rate-submit { transition: none; }
            .rate-comment { transform: none; }
            .rate-star.is-hover { transform: none; }
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
                                    @if($item->has_checked_out)
                                        <div class="status-pill" style="background: var(--caption); color: #fff;">
                                            <span class="sdot"></span> Selesai
                                        </div>
                                    @elseif($item->has_checked_in)
                                        <div class="status-pill status-pill-pending">
                                            <span class="sdot"></span> Belum Check-out
                                        </div>
                                    @else
                                        <div class="status-pill status-pill-pending">
                                            <span class="sdot"></span> Belum Check-in
                                        </div>
                                    @endif
                                </div>

                                @php $userRating = $item->user_rating ?? null; @endphp
                                <div class="rate-row" data-rate-row
                                     data-schedule-id="{{ $item->schedule_id }}"
                                     data-instructor="{{ $instructor }}"
                                     data-class="{{ $className }}">
                                    @if($userRating)
                                        <div class="rate-done">
                                            <div class="rate-stars-static">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <i class="fas fa-star {{ $s <= $userRating->rating ? 'is-on' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="rate-done-label">Ulasan terkirim</span>
                                            <button type="button" class="rate-edit" data-rate-open
                                                    data-rating="{{ $userRating->rating }}"
                                                    data-comment="{{ $userRating->comment }}">Ubah</button>
                                        </div>
                                    @elseif($item->has_checked_out)
                                        <button type="button" class="rate-btn" data-rate-open
                                                data-rating="0" data-comment="">
                                            <i class="fas fa-star"></i> Beri Penilaian
                                        </button>
                                    @else
                                        <span class="rate-locked">
                                            <i class="fas fa-lock"></i>
                                            Penilaian aktif setelah check-out di halaman Attendance
                                        </span>
                                    @endif
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

{{-- PENILAIAN PELATIH: MODAL --}}
<div class="rate-modal" id="rateModal" aria-hidden="true">
    <div class="rate-modal-backdrop" data-rate-close></div>
    <div class="rate-modal-card" role="dialog" aria-modal="true" aria-labelledby="rateModalTitle">
        <button type="button" class="rate-modal-close" data-rate-close aria-label="Tutup">
            <i class="fas fa-xmark"></i>
        </button>

        <p class="rate-eyebrow">Ulasan Kelas</p>
        <h3 id="rateModalTitle">Beri penilaian untuk <span id="rateInstructor">pelatih</span></h3>
        <p class="rate-class-name" id="rateClassName"></p>

        <div class="rate-stars" role="radiogroup" aria-label="Rating 1 sampai 5 bintang">
            <button type="button" class="rate-star" data-value="1" role="radio" aria-checked="false" aria-label="1 bintang"><i class="fas fa-star"></i></button>
            <button type="button" class="rate-star" data-value="2" role="radio" aria-checked="false" aria-label="2 bintang"><i class="fas fa-star"></i></button>
            <button type="button" class="rate-star" data-value="3" role="radio" aria-checked="false" aria-label="3 bintang"><i class="fas fa-star"></i></button>
            <button type="button" class="rate-star" data-value="4" role="radio" aria-checked="false" aria-label="4 bintang"><i class="fas fa-star"></i></button>
            <button type="button" class="rate-star" data-value="5" role="radio" aria-checked="false" aria-label="5 bintang"><i class="fas fa-star"></i></button>
        </div>
        <p class="rate-hint" id="rateHint">Ketuk bintang untuk memberi rating</p>

        <label class="rate-comment-label" for="rateComment">
            Komentar <span>opsional</span>
        </label>
        <textarea id="rateComment" class="rate-comment" rows="3" maxlength="1000"
                  placeholder="Ceritakan pengalamanmu di kelas ini..."></textarea>

        <div class="rate-modal-actions">
            <button type="button" class="rate-cancel" data-rate-close>Batal</button>
            <button type="button" class="rate-submit" id="rateSubmit">Kirim Ulasan</button>
        </div>
        <p class="rate-error" id="rateError" role="alert"></p>
    </div>
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

// ============================================
// PENILAIAN PELATIH
// ============================================
(function () {
    const modal = document.getElementById('rateModal');
    if (!modal) return;

    const RATE_ENDPOINT = @json(route('member.ratings.store'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const titleInstructor = document.getElementById('rateInstructor');
    const classNameEl = document.getElementById('rateClassName');
    const hintEl = document.getElementById('rateHint');
    const commentEl = document.getElementById('rateComment');
    const errorEl = document.getElementById('rateError');
    const submitBtn = document.getElementById('rateSubmit');
    const starEls = Array.from(modal.querySelectorAll('.rate-star'));

    const ratingLabels = {
        1: 'Kurang',
        2: 'Cukup',
        3: 'Baik',
        4: 'Sangat baik',
        5: 'Luar biasa'
    };

    let activeRow = null;
    let rating = 0;

    function paint() {
        starEls.forEach(function (star) {
            const value = parseInt(star.dataset.value, 10);
            star.classList.toggle('is-on', value <= rating);
            star.setAttribute('aria-checked', value === rating ? 'true' : 'false');
        });
        submitBtn.disabled = rating < 1;
        hintEl.textContent = rating ? ratingLabels[rating] : 'Ketuk bintang untuk memberi rating';
    }

    function openModal(opener) {
        activeRow = opener.closest('[data-rate-row]');
        if (!activeRow) return;

        rating = parseInt(opener.dataset.rating || '0', 10) || 0;
        titleInstructor.textContent = activeRow.dataset.instructor || 'pelatih';
        classNameEl.textContent = activeRow.dataset.class || '';
        commentEl.value = opener.dataset.comment || '';
        errorEl.textContent = '';
        paint();

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        setTimeout(function () { commentEl.focus(); }, 120);
    }

    function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        activeRow = null;
    }

    function renderDone(row, value, comment) {
        row.innerHTML = '';

        const done = document.createElement('div');
        done.className = 'rate-done';

        const stars = document.createElement('div');
        stars.className = 'rate-stars-static';
        for (let i = 1; i <= 5; i++) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-star' + (i <= value ? ' is-on' : '');
            stars.appendChild(icon);
        }

        const label = document.createElement('span');
        label.className = 'rate-done-label';
        label.textContent = 'Ulasan terkirim';

        const edit = document.createElement('button');
        edit.type = 'button';
        edit.className = 'rate-edit';
        edit.setAttribute('data-rate-open', '');
        edit.dataset.rating = String(value);
        edit.dataset.comment = comment || '';
        edit.textContent = 'Ubah';

        done.appendChild(stars);
        done.appendChild(label);
        done.appendChild(edit);
        row.appendChild(done);
    }

    starEls.forEach(function (star) {
        star.addEventListener('click', function () {
            rating = parseInt(star.dataset.value, 10);
            errorEl.textContent = '';
            paint();
        });

        star.addEventListener('mouseenter', function () {
            const hoverValue = parseInt(star.dataset.value, 10);
            starEls.forEach(function (s) {
                s.classList.toggle('is-hover', parseInt(s.dataset.value, 10) <= hoverValue);
            });
        });
    });

    modal.querySelector('.rate-stars').addEventListener('mouseleave', function () {
        starEls.forEach(function (s) { s.classList.remove('is-hover'); });
    });

    document.addEventListener('click', function (e) {
        const opener = e.target.closest('[data-rate-open]');
        if (opener) {
            e.preventDefault();
            openModal(opener);
            return;
        }
        if (e.target.closest('[data-rate-close]')) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });

    submitBtn.addEventListener('click', function () {
        if (rating < 1 || !activeRow) return;

        const scheduleId = activeRow.dataset.scheduleId;
        const comment = commentEl.value.trim();
        const row = activeRow;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';
        errorEl.textContent = '';

        fetch(RATE_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                schedule_id: scheduleId,
                rating: rating,
                comment: comment
            })
        })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, status: response.status, data: data };
            });
        })
        .then(function (result) {
            if (!result.ok) {
                let message = result.data.message || 'Ulasan gagal dikirim. Coba lagi.';
                if (result.data.errors) {
                    const firstKey = Object.keys(result.data.errors)[0];
                    if (firstKey) message = result.data.errors[firstKey][0];
                }
                errorEl.textContent = message;
                return;
            }
            renderDone(row, rating, comment);
            closeModal();
        })
        .catch(function () {
            errorEl.textContent = 'Koneksi terputus. Periksa jaringan lalu coba lagi.';
        })
        .finally(function () {
            submitBtn.disabled = rating < 1;
            submitBtn.textContent = 'Kirim Ulasan';
        });
    });

    paint();
})();
</script>

</body>
</html>
