@php
    $date = $schedule->schedule_date;
    $booked = (int) ($schedule->booked_count ?? 0);
    $capacity = (int) ($schedule->capacity ?? 0);
    $percent = $capacity > 0 ? min(100, (int) round($booked / $capacity * 100)) : 0;
    $isFull = $capacity > 0 && $booked >= $capacity;
    $time = $schedule->class_time ? substr((string) $schedule->class_time, 0, 5) : null;
@endphp
<div class="session">
    @if ($showDate)
        <div class="date-badge">
            <strong>{{ $date?->format('d') ?? '-' }}</strong>
            <span>{{ $date?->locale('id')->isoFormat('MMM') ?? '-' }}</span>
        </div>
    @else
        <div class="time-block">
            <strong>{{ $time ?? '--:--' }}</strong>
            <span>{{ $time ? 'WIB' : 'Jam' }}</span>
        </div>
    @endif

    <div class="session-main">
        <strong>{{ $schedule->schedule_label ?: ($schedule->classModel?->class_name ?: 'Kelas') }}</strong>
        <div class="session-meta">
            @if ($showDate)
                <span>{{ $date?->locale('id')->isoFormat('dddd, D MMM YYYY') ?? 'Tanggal belum diisi' }}</span>
                <span class="sep" aria-hidden="true"></span>
                <span>{{ $time ? $time . ' WIB' : 'Jam belum diisi' }}</span>
            @else
                <span>{{ $time ? $time . ' WIB' : 'Jam belum diisi' }}</span>
            @endif
            @if ($schedule->classGroup)
                <span class="sep" aria-hidden="true"></span>
                <span>{{ $schedule->classGroup->name }}</span>
            @endif
        </div>

        <div class="progress {{ $isFull ? 'full' : '' }}" role="img" aria-label="{{ $booked }} dari {{ $capacity > 0 ? $capacity : 'tanpa batas' }} member">
            <i style="width: {{ $isFull ? 100 : $percent }}%"></i>
        </div>
    </div>

    <div class="session-actions">
        <span class="pill {{ $isToday ? 'today' : ($isFull ? 'full' : 'ok') }}">
            {{ $booked }} / {{ $capacity > 0 ? $capacity : '∞' }} member
        </span>
        <a href="{{ route('trainer.schedule', $schedule) }}" class="btn ghost">Detail</a>
    </div>
</div>