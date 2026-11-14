@extends('trainer.layout')

@section('title', 'Kinerja & Penilaian')

@section('content')
    @php
        $averageRating = (float) ($ratingSummary->average_rating ?? 0);
        $totalReviews = (int) ($ratingSummary->total_reviews ?? 0);
    @endphp

    <main class="shell">
        <section class="hero">
            <div class="eyebrow">Kinerja Pelatih</div>
            <h1>Kinerja & Penilaian</h1>
            <p class="muted lead" style="margin:0;">Pantau performa mengajar, kehadiran member, dan ulasan yang masuk setelah sesi selesai.</p>

            <div class="hero-meta">
                <span class="chip">
                    @include('trainer.partials.rating-stars', ['rating' => $averageRating, 'size' => 'sm', 'showValue' => false])
                    {{ number_format($averageRating, 1, ',', '.') }} dari 5
                </span>
                <span class="chip">{{ $totalReviews }} ulasan</span>
                <span class="chip">{{ $sessionsCompleted }} sesi selesai</span>
            </div>
        </section>

        <nav class="filter-tabs" aria-label="Filter periode kinerja">
            <a href="{{ route('trainer.performance', ['period' => 'all']) }}" @class(['active' => $period === 'all']) @if ($period === 'all') aria-current="page" @endif>Semua waktu</a>
            <a href="{{ route('trainer.performance', ['period' => '30']) }}" @class(['active' => $period === '30']) @if ($period === '30') aria-current="page" @endif>30 hari</a>
            <a href="{{ route('trainer.performance', ['period' => '90']) }}" @class(['active' => $period === '90']) @if ($period === '90') aria-current="page" @endif>90 hari</a>
            <a href="{{ route('trainer.performance', ['period' => '365']) }}" @class(['active' => $period === '365']) @if ($period === '365') aria-current="page" @endif>1 tahun</a>
        </nav>

        <section class="stats" aria-label="Ringkasan kinerja">
            <div class="stat">
                <div class="stat-icon pink" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3 6.4 20.2l1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg>
                </div>
                <div>
                    <span>Rating Rata-rata</span>
                    <strong>{{ number_format($averageRating, 1, ',', '.') }}</strong>
                    <small class="stat-note">dari 5 bintang</small>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"/></svg>
                </div>
                <div>
                    <span>Total Ulasan</span>
                    <strong>{{ $totalReviews }}</strong>
                    <small class="stat-note">{{ round($reviewRate) }}% member mengulas</small>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon green" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m5 13 4 4L19 7"/></svg>
                </div>
                <div>
                    <span>Tingkat Kehadiran</span>
                    <strong>{{ round($attendanceRate) }}%</strong>
                    <small class="stat-note">{{ $attendedTotal }} dari {{ $bookedTotal }} booking</small>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon amber" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 7v5l3 2"/><circle cx="12" cy="12" r="9"/></svg>
                </div>
                <div>
                    <span>Sesi Selesai</span>
                    <strong>{{ $sessionsCompleted }}</strong>
                    <small class="stat-note">{{ $checkedOutTotal }} check-out</small>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <span>Member Diajar</span>
                    <strong>{{ $uniqueMembers }}</strong>
                    <small class="stat-note">{{ round($fillRate) }}% kursi terisi</small>
                </div>
            </div>
        </section>

        <div class="grid-2">
            <section class="card" aria-labelledby="rating-breakdown-title">
                <div class="section-head">
                    <h2 id="rating-breakdown-title">Sebaran Rating</h2>
                    <span class="hint">{{ $totalReviews }} ulasan</span>
                </div>

                @if ($totalReviews === 0)
                    <div class="empty">
                        <span class="emoji" aria-hidden="true">★</span>
                        <strong>Belum ada ulasan</strong>
                        Penilaian member muncul di sini setelah sesi selesai dan member check-out.
                    </div>
                @else
                    <div class="rating-summary-box">
                        <div class="rating-score">
                            <strong>{{ number_format($averageRating, 1, ',', '.') }}</strong>
                            @include('trainer.partials.rating-stars', ['rating' => $averageRating, 'size' => 'lg', 'showValue' => false])
                            <span>{{ $totalReviews }} ulasan</span>
                        </div>
                        <div class="rating-bars">
                            @include('trainer.partials.rating-distribution', ['distribution' => $ratingDistribution])
                        </div>
                    </div>
                @endif
            </section>

            <section class="card" aria-labelledby="trend-title">
                <div class="section-head">
                    <h2 id="trend-title">Tren 6 Bulan</h2>
                    <span class="hint">Rating dan sesi per bulan</span>
                </div>

                <div class="trend" role="list" aria-label="Tren rating dan sesi enam bulan terakhir">
                    @foreach ($trend as $item)
                        @php($height = $item->rating ? max(6, $item->rating / 5 * 100) : 0)
                        <div class="trend-col" role="listitem">
                            <span class="trend-value">{{ $item->rating ? number_format($item->rating, 1, ',', '.') : '—' }}</span>
                            <div class="trend-bar-wrap" aria-hidden="true">
                                <span class="trend-bar" style="height: {{ $height }}%"></span>
                            </div>
                            <span class="trend-label">{{ $item->label }}</span>
                            <span class="trend-meta">{{ $item->reviews }} ulasan · {{ $item->sessions }} sesi</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="card" aria-labelledby="class-performance-title">
            <div class="section-head">
                <h2 id="class-performance-title">Kinerja per Kelas</h2>
                <span class="hint">Periode {{ $period === 'all' ? 'semua waktu' : $period . ' hari' }}</span>
            </div>

            @if ($classPerformance->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">📊</span>
                    <strong>Belum ada data kinerja</strong>
                    Data muncul setelah ada sesi yang selesai pada periode ini.
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Sesi</th>
                                <th>Kehadiran</th>
                                <th>Rating</th>
                                <th style="text-align:right;">Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classPerformance as $class)
                                <tr>
                                    <td><strong>{{ $class->name }}</strong></td>
                                    <td>{{ $class->sessions }}</td>
                                    <td>
                                        <div class="attendance-cell">
                                            <span>{{ $class->attended }} / {{ $class->booked }}</span>
                                            <div class="progress" role="img" aria-label="{{ round($class->attendance_rate) }}% member hadir">
                                                <i style="width: {{ $class->attendance_rate }}%"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($class->rating)
                                            @include('trainer.partials.rating-stars', ['rating' => $class->rating, 'size' => 'sm'])
                                        @else
                                            <span class="muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">{{ $class->reviews }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="card" aria-labelledby="recent-sessions-title">
            <div class="section-head">
                <h2 id="recent-sessions-title">Sesi Terakhir</h2>
                <span class="hint">10 sesi selesai terbaru</span>
            </div>

            @if ($recentSessions->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">🗓️</span>
                    <strong>Belum ada sesi selesai</strong>
                    Sesi dan penilaian akan tampil setelah kelas berjalan.
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Kehadiran</th>
                                <th>Rating</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSessions as $session)
                                <tr>
                                    <td>{{ $session->schedule->schedule_date?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $session->schedule->schedule_label ?: ($session->schedule->classModel?->class_name ?: 'Kelas') }}</td>
                                    <td>
                                        <div class="attendance-cell">
                                            <span>{{ $session->attended }} / {{ $session->booked }}</span>
                                            <div class="progress" role="img" aria-label="{{ round($session->attendance_rate) }}% member hadir">
                                                <i style="width: {{ $session->attendance_rate }}%"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($session->reviews > 0)
                                            @include('trainer.partials.rating-stars', ['rating' => $session->rating, 'size' => 'sm'])
                                        @else
                                            <span class="muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('trainer.schedule', $session->schedule) }}" class="btn ghost">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="card" aria-labelledby="reviews-title">
            <div class="section-head">
                <h2 id="reviews-title">Ulasan Member</h2>
                <span class="hint">{{ $reviews->total() }} ulasan pada periode ini</span>
            </div>

            @if ($reviews->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">💬</span>
                    <strong>Belum ada ulasan</strong>
                    Ulasan member akan tampil di sini setelah mereka menyelesaikan kelas.
                </div>
            @else
                <div class="review-list">
                    @foreach ($reviews as $review)
                        @include('trainer.partials.review-card', ['review' => $review])
                    @endforeach
                </div>

                @if ($reviews->hasPages())
                    <nav class="pager" aria-label="Navigasi ulasan">
                        @if ($reviews->onFirstPage())
                            <span class="pager-link disabled">Sebelumnya</span>
                        @else
                            <a href="{{ $reviews->previousPageUrl() }}" class="pager-link">Sebelumnya</a>
                        @endif

                        <span class="pager-info">Halaman {{ $reviews->currentPage() }} dari {{ $reviews->lastPage() }}</span>

                        @if ($reviews->hasMorePages())
                            <a href="{{ $reviews->nextPageUrl() }}" class="pager-link">Berikutnya</a>
                        @else
                            <span class="pager-link disabled">Berikutnya</span>
                        @endif
                    </nav>
                @endif
            @endif
        </section>
    </main>
@endsection
