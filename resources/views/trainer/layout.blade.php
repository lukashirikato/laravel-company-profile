<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#EE4E8B">
    <title>@yield('title', 'Portal Pelatih') · FTM Society</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #EE4E8B;
            --pink-strong: #D63A76;
            --cherry: #7A2B4A;
            --cream: #FCF9F2;
            --surface: #FFFFFF;
            --ink: #1C1C1C;
            --muted: #6B6660;
            --line: #E9E2D8;
            --green: #1A7A5E;
            --green-soft: #E4F3ED;
            --soft: #F4C9DF;
            --amber-soft: #FDF1DF;
            --amber-ink: #8A5A12;
            --radius-sm: 10px;
            --radius: 14px;
            --radius-lg: 20px;
            --shadow: 0 1px 2px rgba(28,28,28,.04), 0 8px 24px rgba(28,28,28,.06);
            --shadow-hover: 0 2px 4px rgba(28,28,28,.06), 0 16px 36px rgba(122,43,74,.12);
            --ring: 0 0 0 4px rgba(238,78,139,.28);
        }
        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        :focus-visible { outline: none; box-shadow: var(--ring); border-radius: 6px; }

        .skip-link {
            position: absolute; left: 16px; top: -100px;
            background: var(--cherry); color: #fff; padding: 10px 16px;
            border-radius: var(--radius-sm); z-index: 60; font-weight: 600;
            transition: top .2s ease;
        }
        .skip-link:focus { top: 12px; }

        .shell { max-width: 1120px; margin: 0 auto; padding: 0 20px 72px; }

        /* Topbar */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            background: linear-gradient(135deg, var(--pink), var(--cherry));
            color: #fff;
            box-shadow: 0 6px 20px rgba(122,43,74,.22);
        }
        .topbar-inner {
            max-width: 1120px; margin: 0 auto;
            padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; flex-wrap: wrap;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-mark {
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.35);
            display: grid; place-items: center;
            font-weight: 700; font-size: 13px; letter-spacing: .04em;
        }
        .brand-text { display: flex; flex-direction: column; gap: 1px; }
        .brand small {
            letter-spacing: .16em; text-transform: uppercase;
            font-size: 10px; opacity: .82; font-weight: 500;
        }
        .brand strong { font-size: 17px; letter-spacing: .01em; font-weight: 600; }
        .nav { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .nav a, .nav button {
            border: 1px solid rgba(255,255,255,.35);
            background: rgba(255,255,255,.08);
            color: #fff; border-radius: 10px;
            padding: 8px 14px; font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: inherit;
            transition: background .18s ease, color .18s ease, transform .18s ease;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .nav a:hover, .nav button:hover { background: #fff; color: var(--cherry); transform: translateY(-1px); }
        .nav a.active { background: #fff; color: var(--cherry); }
        .nav form { margin: 0; display: inline; }

        /* Hero */
        .hero { padding: 40px 0 24px; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--pink-strong); font-size: 12px; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
        }
        .eyebrow::before {
            content: ''; width: 22px; height: 2px; border-radius: 2px;
            background: linear-gradient(90deg, var(--pink), var(--cherry));
        }
        h1 { margin: 10px 0 8px; font-size: clamp(26px, 4vw, 40px); line-height: 1.12; letter-spacing: -.01em; font-weight: 700; }
        h2 { margin: 0; font-size: 19px; line-height: 1.25; font-weight: 600; letter-spacing: -.005em; }
        h3 { margin: 0; font-size: 15px; font-weight: 600; }
        .muted { color: var(--muted); }
        .lead { font-size: 15px; max-width: 60ch; }
        .hero-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .chip {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--surface); border: 1px solid var(--line);
            border-radius: 999px; padding: 6px 12px;
            font-size: 12.5px; font-weight: 600; color: var(--cherry);
            box-shadow: var(--shadow);
        }
        .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green); }

        /* Stats */
        .stats {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px; margin: 28px 0 36px;
        }
        .stat {
            background: var(--surface); border: 1px solid var(--line);
            border-radius: var(--radius); padding: 18px;
            box-shadow: var(--shadow); display: flex; align-items: flex-start; gap: 14px;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
        .stat-icon {
            width: 40px; height: 40px; flex: 0 0 40px; border-radius: 11px;
            display: grid; place-items: center; background: var(--soft); color: var(--cherry);
        }
        .stat-icon.green { background: var(--green-soft); color: var(--green); }
        .stat-icon.amber { background: var(--amber-soft); color: var(--amber-ink); }
        .stat-icon.pink { background: #FDE3EE; color: var(--pink-strong); }
        .stat span { display: block; font-size: 11.5px; color: var(--muted); text-transform: uppercase; letter-spacing: .09em; font-weight: 600; }
        .stat strong { display: block; margin-top: 4px; font-size: 30px; line-height: 1; color: var(--ink); font-weight: 700; }

        /* Cards */
        .card {
            background: var(--surface); border: 1px solid var(--line);
            border-radius: var(--radius-lg); padding: 22px 22px 20px;
            margin-bottom: 20px; box-shadow: var(--shadow);
        }
        .section-head {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; margin-bottom: 16px; flex-wrap: wrap;
        }
        .section-head .hint { color: var(--muted); font-size: 12.5px; }

        /* Session list */
        .session {
            display: flex; align-items: center; gap: 16px;
            padding: 16px 0; border-bottom: 1px dashed var(--line);
        }
        .session:first-of-type { padding-top: 4px; }
        .session:last-child { border-bottom: 0; padding-bottom: 0; }
        .time-block {
            flex: 0 0 76px; text-align: center;
            background: #FFF7FB; border: 1px solid #F5D4E3;
            border-radius: var(--radius-sm); padding: 10px 6px; line-height: 1.15;
        }
        .time-block strong { display: block; font-size: 17px; color: var(--cherry); font-weight: 700; }
        .time-block span { display: block; margin-top: 3px; font-size: 10.5px; text-transform: uppercase; letter-spacing: .07em; color: var(--pink-strong); font-weight: 600; }
        .date-badge {
            flex: 0 0 62px; text-align: center;
            background: var(--cream); border: 1px solid var(--line);
            border-radius: var(--radius-sm); padding: 9px 6px; line-height: 1.1;
        }
        .date-badge strong { display: block; font-size: 20px; color: var(--cherry); font-weight: 700; }
        .date-badge span { font-size: 10.5px; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 600; }
        .session-main { flex: 1; min-width: 0; }
        .session-main strong { display: block; font-size: 15px; font-weight: 600; }
        .session-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 4px; color: var(--muted); font-size: 12.5px; }
        .session-meta .sep { width: 3px; height: 3px; border-radius: 50%; background: currentColor; opacity: .5; }
        .session-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .progress {
            margin-top: 8px; height: 6px; border-radius: 999px;
            background: #F0EAE0; overflow: hidden; max-width: 240px;
        }
        .progress > i {
            display: block; height: 100%; border-radius: 999px;
            background: linear-gradient(90deg, var(--pink), var(--cherry));
        }
        .progress.full > i { background: linear-gradient(90deg, #C0392B, #8E1B1B); }

        /* Date group heading */
        .day-group { margin-top: 18px; }
        .day-group:first-of-type { margin-top: 4px; }
        .day-heading {
            display: flex; align-items: center; gap: 10px;
            margin: 18px 0 6px; color: var(--cherry);
            font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
        }
        .day-heading::after { content: ''; flex: 1; height: 1px; background: var(--line); }

        /* Pills / buttons */
        .pill {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 999px; padding: 4px 11px;
            font-size: 11.5px; font-weight: 700;
            background: var(--soft); color: var(--cherry);
        }
        .pill.ok { background: var(--green-soft); color: var(--green); }
        .pill.today { background: var(--pink); color: #fff; }
        .pill.future { background: #F1EFE9; color: var(--muted); }
        .pill.full { background: #FBE4E2; color: #9B2C2C; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            border: 0; border-radius: var(--radius-sm);
            padding: 12px 18px; background: var(--pink); color: #fff;
            font-weight: 700; font-size: 14px; cursor: pointer; font-family: inherit;
            transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
            box-shadow: 0 6px 16px rgba(238,78,139,.24);
        }
        .btn:hover { background: var(--pink-strong); transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn.ghost {
            background: var(--surface); color: var(--cherry);
            border: 1px solid var(--line); box-shadow: none;
            padding: 8px 12px; font-size: 12.5px;
        }
        .btn.ghost:hover { border-color: var(--pink); color: var(--pink-strong); background: #FFF7FB; }

        /* Empty */
        .empty {
            border: 1.5px dashed #DED4C6; border-radius: var(--radius);
            padding: 30px 22px; text-align: center; color: var(--muted);
            background: linear-gradient(180deg, #fff, #FDFBF6);
        }
        .empty strong { display: block; color: var(--ink); font-size: 15px; margin-bottom: 4px; }
        .empty .emoji { font-size: 26px; display: block; margin-bottom: 8px; }

        /* Table */
        .table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .table th {
            text-align: left; font-size: 11px; letter-spacing: .08em;
            text-transform: uppercase; color: var(--muted); font-weight: 600;
            border-bottom: 1px solid var(--line); padding: 10px 10px;
            background: #FCFAF5;
        }
        .table th:first-child { border-top-left-radius: 8px; }
        .table th:last-child { border-top-right-radius: 8px; }
        .table td { padding: 13px 10px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        .table tr:last-child td { border-bottom: 0; }
        .table tbody tr { transition: background .15s ease; }
        .table tbody tr:hover { background: #FCFAF6; }
        .member { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 34px; height: 34px; flex: 0 0 34px; border-radius: 50%;
            display: grid; place-items: center; font-size: 12px; font-weight: 700;
            background: var(--soft); color: var(--cherry); text-transform: uppercase;
        }
        .phone-link { color: var(--pink-strong); font-weight: 600; }
        .phone-link:hover { text-decoration: underline; }

        /* Login */
        .login-wrap {
            min-height: 100vh; display: grid; place-items: center; padding: 28px;
            background:
                radial-gradient(1200px 500px at 100% -10%, rgba(238,78,139,.10), transparent 60%),
                radial-gradient(900px 500px at -10% 110%, rgba(122,43,74,.10), transparent 55%),
                var(--cream);
        }
        .login-card {
            width: 100%; max-width: 430px; background: var(--surface);
            border: 1px solid var(--line); border-radius: var(--radius-lg);
            padding: 32px; box-shadow: 0 24px 60px rgba(28,28,28,.12);
        }
        .login-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
        .login-brand .brand-mark { background: linear-gradient(135deg, var(--pink), var(--cherry)); color: #fff; border: 0; }
        .login-brand small { display: block; font-size: 10px; letter-spacing: .18em; text-transform: uppercase; color: var(--muted); font-weight: 600; }
        .login-brand strong { display: block; font-size: 15px; color: var(--ink); }
        .login-card h1 { font-size: 26px; margin: 0 0 6px; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 16px 0 6px; color: var(--ink); }
        .input-wrap { position: relative; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; border: 1px solid var(--line); border-radius: var(--radius-sm);
            padding: 13px 14px; font-size: 15px; font-family: inherit; background: #fff;
            color: var(--ink); transition: border-color .18s ease, box-shadow .18s ease;
        }
        input::placeholder { color: #A8A29A; }
        input:focus { outline: none; border-color: var(--pink); box-shadow: var(--ring); }
        .input-wrap input { padding-right: 48px; }
        .toggle-pass {
            position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: var(--muted);
            font-size: 12px; font-weight: 600; cursor: pointer; padding: 8px 10px;
            border-radius: 8px; font-family: inherit;
        }
        .toggle-pass:hover { color: var(--cherry); background: #F7F2EA; }
        .alert {
            background: #FDECEF; border: 1px solid #F5C2C7; color: #842029;
            border-radius: var(--radius-sm); padding: 11px 13px; font-size: 13px;
            margin-bottom: 6px; display: flex; gap: 8px; align-items: flex-start;
        }
        .alert::before { content: '!'; font-weight: 800; flex: 0 0 auto; }
        .check { display: flex; align-items: center; gap: 9px; margin-top: 16px; font-size: 13px; color: var(--muted); font-weight: 500; }
        .check input { width: auto; accent-color: var(--pink); }
        .divider { height: 1px; background: var(--line); margin: 22px 0; }
        .back {
            display: inline-flex; align-items: center; gap: 6px; margin-bottom: 14px;
            color: var(--pink-strong); font-weight: 600; font-size: 14px;
        }
        .back:hover { text-decoration: underline; }

        /* Detail */
        .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-top: 4px; }
        .detail-item { background: #FCFAF5; border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px 16px; }
        .detail-item span { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .09em; color: var(--muted); font-weight: 600; }
        .detail-item strong { display: block; margin-top: 4px; font-size: 15px; font-weight: 600; color: var(--ink); }

        /* Kinerja & penilaian */
        .filter-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 20px; }
        .filter-tabs a {
            display: inline-flex; align-items: center;
            border: 1px solid var(--line); background: var(--surface); color: var(--muted);
            border-radius: 999px; padding: 8px 14px; font-size: 12.5px; font-weight: 600;
            transition: border-color .18s ease, color .18s ease, background .18s ease;
        }
        .filter-tabs a:hover { border-color: var(--pink); color: var(--pink-strong); }
        .filter-tabs a.active { background: var(--cherry); border-color: var(--cherry); color: #fff; }

        .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; margin-bottom: 20px; }
        .grid-2 > .card { margin-bottom: 0; }

        .stat-note { display: block; margin-top: 6px; font-size: 11px; color: var(--muted); font-weight: 500; line-height: 1.35; }

        .rating { display: inline-flex; align-items: center; gap: 7px; white-space: nowrap; }
        .stars { position: relative; display: inline-block; line-height: 1; letter-spacing: 1px; font-size: 15px; color: #DED4C6; }
        .stars-track, .stars-fill { display: block; white-space: nowrap; }
        .stars-fill { position: absolute; inset: 0 auto 0 0; overflow: hidden; color: #F2A516; }
        .stars.sm { font-size: 13px; letter-spacing: .5px; }
        .stars.lg { font-size: 22px; letter-spacing: 1.5px; }
        .stars-value { font-size: 13px; font-weight: 700; color: var(--cherry); }

        .rating-summary-box { display: flex; align-items: center; gap: 28px; flex-wrap: wrap; }
        .rating-score { flex: 0 0 auto; display: flex; flex-direction: column; align-items: flex-start; gap: 4px; min-width: 130px; }
        .rating-score strong { font-size: 44px; line-height: 1; font-weight: 700; color: var(--cherry); letter-spacing: -.02em; }
        .rating-score > span:last-child { font-size: 12px; color: var(--muted); font-weight: 500; }
        .rating-bars { flex: 1; min-width: 220px; }

        .distribution { display: flex; flex-direction: column; gap: 7px; }
        .distribution-row { display: grid; grid-template-columns: 34px 1fr 34px; align-items: center; gap: 10px; }
        .distribution-label { font-size: 12px; font-weight: 600; color: var(--muted); }
        .distribution-label span { color: #F2A516; }
        .distribution-track { display: block; height: 8px; border-radius: 999px; background: #F1EFE9; overflow: hidden; }
        .distribution-fill { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, #F2A516, var(--pink)); }
        .distribution-count { font-size: 12px; font-weight: 600; color: var(--cherry); text-align: right; }

        .trend { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px; align-items: end; }
        .trend-col { display: flex; flex-direction: column; align-items: center; gap: 5px; text-align: center; }
        .trend-value { font-size: 12px; font-weight: 700; color: var(--cherry); }
        .trend-bar-wrap { display: flex; align-items: flex-end; width: 100%; max-width: 34px; height: 110px; background: #FCFAF5; border: 1px solid var(--line); border-radius: 8px; overflow: hidden; }
        .trend-bar { display: block; width: 100%; background: linear-gradient(180deg, var(--pink), var(--cherry)); border-radius: 6px 6px 0 0; }
        .trend-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
        .trend-meta { font-size: 10px; color: var(--muted); line-height: 1.25; }

        .attendance-cell { display: flex; flex-direction: column; gap: 5px; min-width: 110px; }
        .attendance-cell span { font-size: 13px; font-weight: 600; }
        .attendance-cell .progress { margin-top: 0; max-width: none; }
        .progress.wide { max-width: none; margin-top: 16px; height: 8px; }

        .review-list { display: flex; flex-direction: column; gap: 12px; }
        .review-card { border: 1px solid var(--line); border-radius: var(--radius); background: #FCFAF5; padding: 16px 18px; }
        .review-head { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .review-author { display: flex; flex-direction: column; gap: 1px; flex: 1; min-width: 0; }
        .review-author strong { font-size: 14px; font-weight: 600; }
        .review-author span { font-size: 12px; color: var(--muted); }
        .review-rating { margin-left: auto; }
        .review-comment { margin: 10px 0 0; font-size: 14px; line-height: 1.65; color: var(--ink); overflow-wrap: anywhere; white-space: pre-line; }
        .review-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 10px; font-size: 11.5px; color: var(--muted); }
        .review-meta .sep { width: 3px; height: 3px; border-radius: 50%; background: currentColor; opacity: .5; }

        .pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--line); }
        .pager-link { display: inline-flex; align-items: center; border: 1px solid var(--line); background: var(--surface); color: var(--cherry); border-radius: var(--radius-sm); padding: 8px 12px; font-size: 12.5px; font-weight: 600; }
        .pager-link:hover { border-color: var(--pink); color: var(--pink-strong); }
        .pager-link.disabled { color: #B7B0A6; cursor: not-allowed; }
        .pager-info { font-size: 12px; color: var(--muted); }

        .footer {
            max-width: 1120px; margin: 0 auto; padding: 22px 20px 40px;
            color: var(--muted); font-size: 12.5px;
            display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap;
            border-top: 1px solid var(--line);
        }
        .footer a { color: var(--cherry); font-weight: 600; }
        .footer a:hover { text-decoration: underline; }

        .icon { width: 20px; height: 20px; stroke-width: 1.8; }

        @media (max-width: 860px) {
            .grid-2 { grid-template-columns: 1fr; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }
        @media (max-width: 720px) {
            .topbar-inner { align-items: flex-start; }
            .nav { width: 100%; }
            .session { align-items: flex-start; flex-wrap: wrap; }
            .session-main { flex-basis: calc(100% - 92px); }
            .session-actions { width: 100%; justify-content: space-between; }
            .table th:nth-child(4), .table td:nth-child(4) { display: none; }
            .card { padding: 18px 16px; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .rating-summary-box { gap: 18px; }
            .rating-score strong { font-size: 36px; }
            .trend { gap: 6px; }
            .trend-meta { display: none; }
            .review-rating { margin-left: 0; width: 100%; }
        }
        @media (max-width: 460px) {
            .brand strong { font-size: 15px; }
            .table th:nth-child(3), .table td:nth-child(3) { display: none; }
        }
    </style>
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main">Lewati ke konten</a>

    @if (auth('trainer')->check() && auth('trainer')->user()?->isTrainer())
        <header class="topbar">
            <div class="topbar-inner">
                <div class="brand">
                    <div class="brand-mark" aria-hidden="true">FTM</div>
                    <div class="brand-text">
                        <small>FTM Fitness Society</small>
                        <strong>Portal Pelatih</strong>
                    </div>
                </div>
                <nav class="nav" aria-label="Navigasi utama">
                    <a href="{{ route('trainer.dashboard') }}" @class(['active' => request()->routeIs('trainer.dashboard')]) @if (request()->routeIs('trainer.dashboard')) aria-current="page" @endif>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                        Jadwal
                    </a>
                    <a href="{{ route('trainer.performance') }}" @class(['active' => request()->routeIs('trainer.performance')]) @if (request()->routeIs('trainer.performance')) aria-current="page" @endif>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-6"/></svg>
                        Kinerja
                    </a>
                    <a href="{{ route('home') }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                        Website
                    </a>
                    <form method="POST" action="{{ route('trainer.logout') }}">
                        @csrf
                        <button type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3"/><path d="M10 17l5-5-5-5M15 12H3"/></svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </header>
    @endif

    <div id="main">
        @yield('content')
    </div>

    <footer class="footer">
        <span>&copy; {{ date('Y') }} FTM Fitness Society · Portal Pelatih</span>
        <span>Butuh bantuan? <a href="{{ route('home') }}">Hubungi admin</a></span>
    </footer>

    @stack('scripts')
</body>
</html>