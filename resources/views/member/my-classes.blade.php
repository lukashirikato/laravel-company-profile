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
            --caption: #B4907C;
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

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--cherry);
            margin-bottom: 0.15rem;
        }

        .page-header p {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: var(--caption);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            max-width: 600px;
            margin-bottom: 2.25rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none !important;
            outline: none !important;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon.pink { background: var(--pink); }
        .stat-icon.green { background: var(--green); }

        .stat-info .num {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--cherry);
            line-height: 1.1;
        }

        .stat-info .label {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 400;
            color: var(--caption);
        }

        .section-title {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--cherry);
            margin-bottom: 1rem;
        }

        .package-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.625rem;
            margin-bottom: 1.5rem;
        }

        .package-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 1.125rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: var(--caption);
            background: transparent;
            border: 1px solid #F0C4D6;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .package-tab:hover {
            background: var(--section-bg);
            color: var(--cherry);
            border-color: var(--petal);
        }

        .package-tab.active {
            background: var(--pink);
            color: #fff;
            border-color: var(--pink);
            box-shadow: 0 3px 10px rgba(238,78,139,0.2);
        }

        .package-tab .tab-count {
            font-size: 11px;
            font-weight: 600;
            opacity: 0.65;
        }

        .package-tab.active .tab-count {
            opacity: 0.9;
        }

        .package-tab .tab-icon {
            font-size: 12px;
            opacity: 0.7;
        }

        .package-tab.active .tab-icon {
            opacity: 1;
        }

        .card-package-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 10px;
            font-weight: 500;
            color: var(--ivy);
            padding: 0.1rem 0.5rem;
            border-radius: 3px;
            background: rgba(197, 215, 155, 0.18);
            margin-left: 0.5rem;
            vertical-align: middle;
            letter-spacing: 0.01em;
        }

        .card-package-badge i {
            font-size: 8px;
            opacity: 0.7;
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
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #E8C0D4;
            box-shadow: 0 2px 8px rgba(122,43,74,0.06), 0 1px 3px rgba(122,43,74,0.04);
            transition: opacity 0.25s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .class-card:hover {
            box-shadow: 0 6px 20px rgba(122,43,74,0.10), 0 2px 6px rgba(122,43,74,0.06);
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
            font-style: italic;
            color: var(--caption);
            border: none !important;
            outline: none !important;
        }

        .visit-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 0.2rem 0.7rem;
            border-radius: 999px;
            background: var(--section-bg);
            color: var(--cherry);
            margin-bottom: 0.85rem;
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
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--caption);
        }

        .sched-cell .s-value {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--layl);
            display: flex;
            align-items: center;
            gap: 0.35rem;
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
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--caption);
        }

        .instructor-group .iinfo .iname {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--layl);
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

            .page-header h1 { font-size: 22px; }
            .page-header p { font-size: 13px; }

            .stats-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                margin-bottom: 1.75rem;
            }

            .stat-card { padding: 0.85rem 1rem; }
            .stat-icon { width: 38px; height: 38px; font-size: 0.95rem; }
            .stat-info .num { font-size: 22px; }

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

            <div class="page-header">
                <h1>My Classes</h1>
                <p>Manage your scheduled sessions</p>
            </div>

            @if(!$myClasses->isEmpty())
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon pink">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <div class="num">{{ $stats['total_classes'] ?? 0 }}</div>
                            <div class="label">Scheduled Classes</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <div class="num">{{ $stats['unique_packages'] ?? 0 }}</div>
                            <div class="label">Active Memberships</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($myClasses->isEmpty())
                <div class="empty-card">
                    <div class="eicon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                    <h3>No Scheduled Classes</h3>
                    <p>You haven't booked any classes yet.</p>
                    <a href="{{ route('member.book') }}" class="btn-pink">
                        <i class="fas fa-plus-circle"></i> Book Your First Class
                    </a>
                </div>
            @else
                <div class="section-title">Scheduled Sessions</div>

                @php
                    $hasMulti = $activePackages->count() > 1;
                    $allPkgs = $activePackages->pluck('package.name')->unique()->filter()->values();
                    if ($hasMulti && $allPkgs->count() < 2) $hasMulti = false;
                @endphp

                @if($hasMulti)
                <div class="package-tabs" id="packageTabs">
                    <button class="package-tab active" data-package="all">
                        <i class="fas fa-th-large tab-icon"></i> All Classes
                        <span class="tab-count">{{ $myClasses->count() }}</span>
                    </button>
                    @foreach($allPkgs as $pkgName)
                        @php
                            $pkgSlug = Str::slug($pkgName);
                            $pkgCount = $myClasses->filter(function($c) use ($pkgName) {
                                $info = $c->package_info ?? [];
                                return ($info['name'] ?? '') === $pkgName;
                            })->count();
                        @endphp
                        @if($pkgCount > 0)
                            <button class="package-tab" data-package="{{ $pkgSlug }}">
                                <i class="fas fa-box-open tab-icon"></i> {{ $pkgName }}
                                <span class="tab-count">{{ $pkgCount }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>
                @endif

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
                                            {{ $classType }}
                                            @if($hasMulti)
                                            <span class="card-package-badge">
                                                <i class="fas fa-tag"></i> {{ $pkgName }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="visit-badge">
                                    <i class="fas fa-ticket" style="font-size: 10px; margin-right: 0.25rem;"></i>
                                    {{ $classType }}
                                </div>

                                <div class="schedule-grid">
                                    <div class="sched-cell">
                                        <span class="s-label">Day</span>
                                        <span class="s-value"><i class="fas fa-calendar-alt"></i>{{ $item->schedule->day }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Date</span>
                                        <span class="s-value"><i class="fas fa-calendar-day"></i>{{ $item->schedule->schedule_date_formatted }}</span>
                                    </div>
                                    <div class="sched-cell">
                                        <span class="s-label">Time</span>
                                        <span class="s-value"><i class="fas fa-clock"></i>{{ \Carbon\Carbon::parse($item->schedule->class_time)->format('H:i') }}</span>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="instructor-group">
                                        <div class="iavatar">{{ $initials }}</div>
                                        <div class="iinfo">
                                            <div class="ilabel">Instructor</div>
                                            <div class="iname">{{ $instructor }}</div>
                                        </div>
                                    </div>
                                    <div class="status-pill">
                                        <span class="sdot"></span> Confirmed
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="filter-empty" id="filterEmpty">
                        <div class="fe-icon">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                        <h4>No classes in this package</h4>
                        <p>Switch to another package to see its schedule.</p>
                    </div>
                </div>
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
(function() {
    var tabs = document.querySelectorAll('.package-tab');
    var grid = document.getElementById('classesGrid');
    var cards = grid ? grid.querySelectorAll('.class-card') : [];
    var emptyMsg = document.getElementById('filterEmpty');
    if (!tabs.length || !cards.length) return;

    function applyFilter(pkg) {
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

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            tabs.forEach(function(t) { t.classList.remove('active'); });
            tab.classList.add('active');
            applyFilter(tab.getAttribute('data-package'));
        });
    });
})();

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
