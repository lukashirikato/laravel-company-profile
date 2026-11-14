@extends('trainer.layout')

@section('title', 'Detail Sesi')

@section('content')
    @php
        $booked = $schedule->customerSchedules->count();
        $capacity = (int) ($schedule->capacity ?? 0);
        $isToday = $schedule->schedule_date?->isToday() ?? false;
        $isPast = $schedule->schedule_date ? $schedule->schedule_date->lt(today()) : false;
        $statusText = $isToday ? 'Hari ini' : ($isPast ? 'Selesai' : 'Akan datang');
        $statusClass = $isToday ? 'today' : ($isPast ? 'future' : 'ok');
    @endphp

    <main class="shell">
        <section class="hero">
            <a href="{{ route('trainer.dashboard') }}" class="back">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke jadwal
            </a>
            <div class="eyebrow">Detail Sesi</div>
            <h1>{{ $schedule->schedule_label ?: ($schedule->classModel?->class_name ?: 'Kelas') }}</h1>
            <p class="muted" style="margin:0;">
                {{ $schedule->schedule_date?->locale('id')->isoFormat('dddd, D MMMM YYYY') ?? 'Tanggal belum diisi' }}
                · {{ $schedule->class_time ? substr((string) $schedule->class_time, 0, 5) . ' WIB' : 'Jam belum diisi' }}
            </p>
            <div class="hero-meta">
                <span class="pill {{ $statusClass }}">{{ $statusText }}</span>
                <span class="chip">{{ $booked }} / {{ $capacity > 0 ? $capacity : '∞' }} member</span>
            </div>
        </section>

        <section class="card" aria-label="Informasi sesi">
            <div class="detail-grid">
                <div class="detail-item">
                    <span>Pelatih</span>
                    <strong>{{ $instructor->name }}</strong>
                </div>
                <div class="detail-item">
                    <span>Group</span>
                    <strong>{{ $schedule->classGroup?->name ?? '-' }}</strong>
                </div>
                <div class="detail-item">
                    <span>Kapasitas</span>
                    <strong>{{ $booked }} / {{ $capacity > 0 ? $capacity : '∞' }} member</strong>
                </div>
                <div class="detail-item">
                    <span>Package</span>
                    <strong>{{ $schedule->packageNames ?: '-' }}</strong>
                </div>
            </div>
        </section>

        <section class="card" aria-labelledby="session-performance-title">
            <div class="section-head">
                <h2 id="session-performance-title">Kinerja Sesi</h2>
                <span class="hint">{{ round($attendanceStats['review_rate']) }}% member yang check-out memberi ulasan</span>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <span>Booking</span>
                    <strong>{{ $attendanceStats['booked'] }} member</strong>
                </div>
                <div class="detail-item">
                    <span>Check-in</span>
                    <strong>{{ $attendanceStats['checked_in'] }} member</strong>
                </div>
                <div class="detail-item">
                    <span>Check-out</span>
                    <strong>{{ $attendanceStats['checked_out'] }} member</strong>
                </div>
                <div class="detail-item">
                    <span>Tingkat Kehadiran</span>
                    <strong>{{ round($attendanceStats['attendance_rate']) }}%</strong>
                </div>
                <div class="detail-item">
                    <span>Ulasan Masuk</span>
                    <strong>{{ (int) ($ratingSummary->total_reviews ?? 0) }}</strong>
                </div>
                <div class="detail-item">
                    <span>Rating Sesi</span>
                    <strong>
                        @if ((int) ($ratingSummary->total_reviews ?? 0) > 0)
                            {{ number_format((float) $ratingSummary->average_rating, 1, ',', '.') }} / 5
                        @else
                            Belum ada
                        @endif
                    </strong>
                </div>
            </div>

            <div class="progress wide" role="img" aria-label="{{ round($attendanceStats['attendance_rate']) }}% member hadir">
                <i style="width: {{ $attendanceStats['attendance_rate'] }}%"></i>
            </div>
        </section>

        <section class="card" aria-labelledby="member-title">
            <div class="section-head">
                <h2 id="member-title">Daftar Member</h2>
                <span class="pill ok">{{ $booked }} terdaftar</span>
            </div>

            @if ($schedule->customerSchedules->isEmpty())
                <div class="empty">
                    <span class="emoji" aria-hidden="true">👥</span>
                    <strong>Belum ada member</strong>
                    Belum ada member yang booking sesi ini.
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:48px;">#</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Booking</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule->customerSchedules as $index => $booking)
                                @php($customer = $booking->customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="member">
                                            <span class="avatar" aria-hidden="true">{{ mb_substr(trim($customer?->name ?? 'M'), 0, 2) }}</span>
                                            <span>{{ $customer?->name ?? 'Member' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $customer?->phone_number ?? '-' }}</td>
                                    <td>{{ $booking->joined_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td style="text-align:right;">
                                        @if ($customer?->getWhatsAppLink())
                                            <a href="{{ $customer->getWhatsAppLink() }}" target="_blank" rel="noopener" class="btn ghost">WhatsApp</a>
                                        @else
                                            <span class="muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="card" aria-labelledby="session-reviews-title">
            <div class="section-head">
                <h2 id="session-reviews-title">Penilaian Member</h2>
                <span class="hint">{{ (int) ($ratingSummary->total_reviews ?? 0) }} ulasan</span>
            </div>

            @if ((int) ($ratingSummary->total_reviews ?? 0) === 0)
                <div class="empty">
                    <span class="emoji" aria-hidden="true">★</span>
                    <strong>Belum ada ulasan untuk sesi ini</strong>
                    Member dapat memberi rating setelah menyelesaikan kelas dan check-out.
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

                <div class="review-list" style="margin-top:18px;">
                    @foreach ($schedule->ratings as $review)
                        @include('trainer.partials.review-card', ['review' => $review])
                    @endforeach
                </div>
            @endif
        </section>
    </main>
@endsection