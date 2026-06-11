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
    .attendance-page { --att-plum:#762645; --att-rose:#e94683; background: radial-gradient(circle at 9% 6%, rgba(233,70,131,.18), transparent 18rem), radial-gradient(circle at 96% 1%, rgba(220,233,189,.58), transparent 22rem), linear-gradient(180deg, #fffaf7 0%, #fff6ef 46%, #ffffff 100%); }
    .attendance-shell { max-width: 1180px; }
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
    @media (max-width: 768px) { .hamburger-btn { display:flex !important; } .attendance-page main { width:100%; } .attendance-hero { border-radius:1.65rem; } .attendance-hero:after { width:12rem; height:12rem; right:-4rem; bottom:-5rem; } .attendance-mobile-tight { padding-top:4.25rem; } .attendance-timeline:before { left:1rem; } }
</style>

<div class="attendance-page min-h-screen flex">
    @include('partials.member-sidebar')

    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()" aria-label="Open menu">
        <span class="attendance-menu-lines" aria-hidden="true"><span></span><span></span><span></span></span>
    </button>

    <main class="main-content flex-1 overflow-auto">
        <div class="attendance-shell mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-10">
            <section class="attendance-hero attendance-mobile-tight relative overflow-hidden p-5 sm:p-8 lg:p-10 text-white attendance-animate">
                <div class="relative z-10 grid gap-5 lg:grid-cols-[1fr_21rem] lg:items-end">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/14 px-3 py-1.5 text-[10px] sm:text-xs font-black uppercase tracking-[0.18em] text-white/88 ring-1 ring-white/20">
                            <i class="ri-calendar-check-line"></i> Attendance Center
                        </div>
                        <h1 class="mt-3 max-w-2xl text-[2rem] sm:text-5xl font-black leading-[1.08] !text-white">Pusat kehadiran latihan member.</h1>
                        <p class="mt-2 max-w-xl text-sm sm:text-base leading-relaxed text-white/78">Pantau check-in, check-out, durasi, dan riwayat scan QR setiap sesi latihan Anda.</p>

                        <div class="mt-5 grid grid-cols-3 gap-2 sm:max-w-xl sm:gap-3">
                            <div class="attendance-quick rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-white/58">Total</p><p class="mt-1 text-2xl font-black text-white">{{ $totalAttendances }}</p></div>
                            <div class="attendance-quick rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-white/58">Bulan</p><p class="mt-1 text-2xl font-black text-white">{{ $monthAttendances }}</p></div>
                            <div class="attendance-quick rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-white/58">Minggu</p><p class="mt-1 text-2xl font-black text-white">{{ $weekAttendances }}</p></div>
                        </div>
                    </div>

                    <div class="rounded-[1.35rem] bg-white p-3 text-dark shadow-2xl shadow-black/10 sm:p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-light-pink text-secondary"><i class="ri-pulse-line text-xl"></i></div>
                            <div class="min-w-0 flex-1"><p class="text-[10px] font-black uppercase tracking-widest text-dark/45">Aktivitas terakhir</p><p class="mt-1 truncate text-lg font-black text-dark">{{ $lastAttendanceLabel }}</p><p class="mt-1 text-xs text-dark/50">Rata-rata durasi {{ $averageDurationLabel }}</p></div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a href="{{ route('member.qr.scanner') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-secondary px-3 py-3 text-sm font-black text-white transition hover:bg-dark"><i class="ri-qr-scan-2-line"></i> Scan QR</a>
                            <a href="{{ route('member.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-cream px-3 py-3 text-sm font-black text-secondary transition hover:bg-light-pink"><i class="ri-home-5-line"></i> Dashboard</a>
                        </div>
                    </div>
                </div>
            </section>

            @if($activeAttendance)
                @php $activeProgram = $activeAttendance->program ?? $activeAttendance->schedule?->classModel?->name ?? 'General Fitness'; @endphp
                <section class="mt-4 rounded-[1.5rem] bg-[#176a54] p-4 sm:p-5 text-white shadow-xl shadow-green-900/10 attendance-animate">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex gap-3"><div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15"><span class="relative flex h-3 w-3"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-70"></span><span class="relative inline-flex h-3 w-3 rounded-full bg-white"></span></span></div><div><p class="text-[10px] font-black uppercase tracking-widest text-white/60">Sedang check-in</p><h2 class="text-xl sm:text-2xl font-black !text-white">{{ $activeProgram }}</h2><p class="mt-1 text-sm text-white/75">Masuk {{ $activeAttendance->check_in_at?->format('H:i') ?? '-' }}. Scan QR untuk check-out.</p></div></div>
                        <a href="{{ route('member.qr.scanner') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 font-black text-accent transition hover:bg-cream">Check-out <i class="ri-arrow-right-line"></i></a>
                    </div>
                </section>
            @endif

            <section class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.04s"><div class="flex items-center justify-between gap-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/45">Total Visit</p><i class="ri-footprint-line text-secondary/70"></i></div><p class="mt-2 text-3xl sm:text-4xl font-black text-dark">{{ $totalAttendances }}</p><p class="mt-1 text-xs text-dark/55">Semua sesi tercatat</p></div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.08s"><div class="flex items-center justify-between gap-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/45">Bulan Ini</p><i class="ri-calendar-event-line text-secondary/70"></i></div><p class="mt-2 text-3xl sm:text-4xl font-black text-secondary">{{ $monthAttendances }}</p><p class="mt-1 text-xs text-dark/55">Konsistensi bulanan</p></div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.12s"><div class="flex items-center justify-between gap-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/45">Minggu Ini</p><i class="ri-fire-line text-secondary/70"></i></div><p class="mt-2 text-3xl sm:text-4xl font-black text-accent">{{ $weekAttendances }}</p><p class="mt-1 text-xs text-dark/55">Progress mingguan</p></div>
                <div class="attendance-metric rounded-[1.35rem] p-4 sm:p-5 attendance-animate" style="animation-delay:.16s"><div class="flex items-center justify-between gap-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/45">Durasi</p><i class="ri-timer-flash-line text-secondary/70"></i></div><p class="mt-2 text-3xl sm:text-4xl font-black text-dark">{{ $totalDurationLabel }}</p><p class="mt-1 text-xs text-dark/55">Rata-rata {{ $averageDurationLabel }}</p></div>
            </section>

            <section class="attendance-panel mt-4 rounded-[1.75rem] overflow-hidden attendance-animate" style="animation-delay:.2s">
                <div class="border-b border-light-pink/60 bg-white/76 p-4 sm:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div><p class="text-[10px] font-black uppercase tracking-[0.18em] text-secondary/70">Attendance Log</p><h2 class="mt-1 text-2xl sm:text-3xl font-black text-dark">Riwayat Attendance</h2><p class="mt-1 text-sm text-dark/55">Filter kelas, tanggal, status, paket, atau metode scan.</p></div>
                        <div class="relative w-full lg:w-96"><i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-dark/35"></i><input id="attendanceSearch" type="search" placeholder="Cari attendance..." class="w-full rounded-2xl border border-light-pink/80 bg-white py-3.5 pl-11 pr-4 text-sm font-semibold text-dark placeholder:text-dark/35 focus:border-secondary focus:outline-none focus:ring-4 focus:ring-light-pink/40"></div>
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
                            <article class="attendance-row rounded-[1.35rem] border border-light-pink/60 bg-white p-4 sm:p-5" data-search="{{ strtolower($program.' '.$status.' '.$method.' '.$package.' '.$date?->format('d M Y l')) }}">
                                <div class="flex gap-3 sm:gap-4">
                                    <div class="attendance-timeline relative shrink-0"><div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl {{ $isActive ? 'bg-secondary text-white' : 'bg-grounded-green/40 text-accent' }}"><i class="{{ $isActive ? 'ri-loader-4-line' : 'ri-check-line' }} text-lg"></i></div></div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"><div class="min-w-0"><p class="text-[10px] sm:text-xs font-black uppercase tracking-[0.16em] text-dark/42">{{ $date?->format('l, d M Y') ?? '-' }}</p><h3 class="mt-1 truncate text-lg sm:text-xl font-black text-dark">{{ $program }}</h3><p class="mt-1 truncate text-sm text-dark/50">{{ $package }}</p></div><span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 text-xs font-black {{ $isActive ? 'bg-light-pink text-secondary' : 'bg-grounded-green/40 text-accent' }}"><i class="{{ $isActive ? 'ri-time-line' : 'ri-checkbox-circle-line' }}"></i>{{ $status }}</span></div>
                                        <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3"><div class="attendance-chip rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/40">Check-in</p><p class="mt-1 font-black text-dark">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p></div><div class="attendance-chip rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/40">Check-out</p><p class="mt-1 font-black {{ $isActive ? 'text-secondary' : 'text-dark' }}">{{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}</p></div><div class="attendance-chip rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/40">Durasi</p><p class="mt-1 font-black text-dark">{{ $duration }}</p></div><div class="attendance-chip rounded-2xl p-3"><p class="text-[10px] font-black uppercase tracking-widest text-dark/40">Metode</p><p class="mt-1 font-black text-dark">{{ $method }}</p></div></div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div id="attendanceEmptySearch" class="hidden px-6 py-14 text-center"><div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-cream text-secondary"><i class="ri-search-eye-line text-3xl"></i></div><h3 class="text-xl font-black text-dark">Data tidak ditemukan</h3><p class="mt-2 text-dark/55">Coba kata kunci lain, misalnya nama kelas atau tanggal.</p></div>
                    <div class="border-t border-light-pink/60 bg-cream/50 px-4 py-4 sm:px-6 sm:py-5">{{ $attendances->links() }}</div>
                @else
                    <div class="px-6 py-14 sm:py-20 text-center"><div class="attendance-empty-icon mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-[1.75rem]"><i class="ri-qr-code-line text-4xl"></i></div><h3 class="text-2xl font-black text-dark">Belum ada attendance</h3><p class="mx-auto mt-2 max-w-md text-dark/55">Tampilkan QR member Anda ke staff untuk check-in. Setelah dipindai, aktivitas latihan akan tersimpan otomatis di sini.</p>@if($memberQrData)<button type="button" onclick="openAttendanceQR()" class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 font-black text-white transition hover:bg-dark">Mulai Check-in <i class="ri-qr-code-line"></i></button>@else<a href="{{ route('member.account') }}" class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-secondary px-6 py-3 font-black text-white transition hover:bg-dark">Aktifkan QR Member <i class="ri-arrow-right-line"></i></a>@endif</div>
                @endif
            </section>
        </div>
    </main>
</div>

@if($memberQrData)
<div id="attendanceQrModal" class="qr-attendance-modal" onclick="closeAttendanceQR(event)">
    <div class="qr-attendance-card text-center" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between gap-3 text-left">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-secondary/70">Member QR Code</p>
                <h3 class="mt-1 text-2xl font-black text-dark">Scan untuk check-in</h3>
            </div>
            <button type="button" onclick="closeAttendanceQR()" class="qr-close-button flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-light-pink text-secondary transition" aria-label="Kembali ke halaman attendance"><span class="qr-close-icon" aria-hidden="true">&times;</span></button>
        </div>
        <div class="qr-attendance-box mt-5">
            <div class="qr-attendance-placeholder" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z" />
                    <path d="M14 14h2v2h-2zM18 14h2M14 18h2M18 18h2v2h-2z" />
                </svg>
            </div>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($memberQrData) }}&bgcolor=ffffff&color=7A2B4A" alt="QR Code Member">
        </div>
        <p class="mt-4 text-sm text-dark/60">Tunjukkan QR ini ke staff atau trainer. Jangan bagikan QR ke orang lain.</p>
        <p class="mt-2 font-black text-secondary">{{ $member?->name }}</p>
    </div>
</div>
@endif

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

