@extends('trainer.layout')

@section('title', 'Jadwal Mengajar')

@section('content')
    <main class="shell">
        <section class="hero">
            <div class="eyebrow">{{ $today->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</div>
            <h1>Halo, {{ $instructor->name }}</h1>
            <p class="muted lead" style="margin:0;">Berikut ringkasan jadwal mengajar Anda hari ini dan yang akan datang.</p>

            <div class="hero-meta">
                <span class="chip"><span class="dot" aria-hidden="true"></span> {{ $stats['today'] }} sesi hari ini</span>
                @if ($stats['upcoming'] > 0)
                    <span class="chip">{{ $stats['upcoming'] }} sesi akan datang</span>
                @endif
            </div>
        </section>

        <section class="stats" aria-label="Ringkasan jadwal">
            <div class="stat">
                <div class="stat-icon pink" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                </div>
                <div>
                    <span>Hari Ini</span>
                    <strong>{{ $stats['today'] }}</strong>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h10"/></svg>
                </div>
                <div>
                    <span>7 Hari</span>
                    <strong>{{ $stats['week'] }}</strong>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon green" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 7v5l3 2"/><circle cx="12" cy="12" r="9"/></svg>
                </div>
                <div>
                    <span>Akan Datang</span>
                    <strong>{{ $stats['upcoming'] }}</strong>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon amber" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m5 13 4 4L19 7"/></svg>
                </div>
                <div>
                    <span>Selesai</span>
                    <strong>{{ $stats['completed'] }}</strong>
                </div>
            </div>
        </section>

        <section class="card" aria-labelledby="performance-title">
            <div class="section-head">
                <h2 id="performance-title">Penilaian Member</h2>
                <a href="{{ route('trainer.performance') }}" class="btn ghost">Lihat kinerja lengkap</a>
            </div>

            @if ((int) ($ratingSummary->total_reviews ?? 0) === 0)
                <div class="empty">
                    <span class="emoji" aria-hidden="true">★</span>
                    <strong>Belum ada penilaian</strong>
                    Ulasan member akan tampil setelah sesi selesai dan member check-out.
                </div>
            @else
                <div class="rating-summary-box">
                    <div class="rating-score">
                        <strong>{{ number_format((float) $ratingSummary->average_rating, 1, ',', '.') }}</strong>
                        @include('trainer.partials.rating-stars', ['rating' => $ratingSummary->average_rating, 'size' => 'lg', 'showValue' => false])
                        <span>{{ (int) $ratingSummary->total_reviews }} ulasan</span>
                    </div>
                    <div class="rating-bars">
                        @include('trainer.partials.rating-distribution', ['distribution' => $ratingDistribution])
                    </div>
                </div>

                @if ($latestReviews->isNotEmpty())
                    <div class="review-list" style="margin-top:18px;">
                        @foreach ($latestReviews as $review)
                            @include('trainer.partials.review-card', ['review' => $review])
                        @endforeach
                    </div>
                @endif
            @endif
        </section>

        <section class="card" aria-labelledby="today-title">
            <div class="section-head">
                <h2 id="today-title">Jadwal Hari Ini</h2>
                <span class="pill {{ $todaySchedules->count() > 0 ? 'today' : 'future' }}">{{ $todaySchedules->count() }} sesi</span>
            </div>

            @forelse ($todaySchedules as $schedule)
                @include('trainer.partials.session-row', ['schedule' => $schedule, 'showDate' => false, 'isToday' => true])
            @empty
                <div class="empty">
                    <span class="emoji" aria-hidden="true">☕</span>
                    <strong>Tidak ada sesi hari ini</strong>
                    Nikmati waktu istirahat Anda. Jadwal berikutnya ada di bawah.
                </div>
            @endforelse
        </section>

        <section class="card" aria-labelledby="upcoming-title">
            <div class="section-head">
                <h2 id="upcoming-title">Jadwal Berikutnya</h2>
                <span class="hint">Diurutkan dari tanggal terdekat</span>
            </div>

            @php($future = $upcoming->reject(fn ($schedule) => $schedule->schedule_date?->isSameDay($today)))
            @if ($future->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">🗓️</span>
                    <strong>Belum ada jadwal berikutnya</strong>
                    Jadwal baru akan muncul di sini setelah ditambahkan admin.
                </div>
            @else
                @foreach ($future->groupBy(fn ($schedule) => $schedule->schedule_date?->format('Y-m-d') ?? 'tanpa-tanggal') as $dayKey => $daySchedules)
                    @php($dayDate = $daySchedules->first()->schedule_date)
                    <div class="day-group">
                        <div class="day-heading">
                            {{ $dayDate?->locale('id')->isoFormat('dddd, D MMM YYYY') ?? 'Tanggal belum diisi' }}
                        </div>
                        @foreach ($daySchedules as $schedule)
                            @include('trainer.partials.session-row', ['schedule' => $schedule, 'showDate' => false, 'isToday' => false])
                        @endforeach
                    </div>
                @endforeach
            @endif
        </section>

        <section class="card" aria-labelledby="history-title">
            <div class="section-head">
                <h2 id="history-title">Riwayat Terakhir</h2>
                <span class="hint">15 sesi terakhir</span>
            </div>

            @if ($history->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">📋</span>
                    <strong>Belum ada riwayat sesi</strong>
                    Riwayat mengajar akan tampil di sini.
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Member</th>
                                <th>Rating</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $schedule)
                                <tr>
                                    <td>{{ $schedule->schedule_date?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $schedule->schedule_label ?: ($schedule->classModel?->class_name ?: '-') }}</td>
                                    <td>{{ $schedule->class_time ? substr((string) $schedule->class_time, 0, 5) : '-' }}</td>
                                    <td>{{ (int) ($schedule->booked_count ?? 0) }} / {{ $schedule->capacity ?: '∞' }}</td>
                                    <td>
                                        @if ((int) ($schedule->ratings_count ?? 0) > 0)
                                            @include('trainer.partials.rating-stars', ['rating' => $schedule->ratings_avg_rating, 'size' => 'sm'])
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;"><a href="{{ route('trainer.schedule', $schedule) }}" class="btn ghost">Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
@endsection