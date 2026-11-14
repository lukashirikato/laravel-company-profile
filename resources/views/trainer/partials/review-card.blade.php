@php
    $name = trim((string) ($review->customer?->name ?? 'Member'));
    $parts = preg_split('/\s+/', $name) ?: [];
    $firstName = $parts[0] ?? 'Member';
    $lastName = count($parts) > 1 ? end($parts) : null;
    $displayName = $firstName . ($lastName ? ' ' . mb_strtoupper(mb_substr($lastName, 0, 1)) . '.' : '');
    $initial = mb_strtoupper(mb_substr($firstName, 0, 1));
    $className = $review->class_name
        ?: ($review->schedule?->schedule_label
        ?: ($review->schedule?->classModel?->class_name ?: 'Kelas'));
    $reviewDate = $review->created_at;
    $sessionDate = $review->schedule?->schedule_date;
@endphp
<article class="review-card">
    <div class="review-head">
        <span class="avatar" aria-hidden="true">{{ $initial }}</span>
        <div class="review-author">
            <strong>{{ $displayName }}</strong>
            <span>{{ $className }}</span>
        </div>
        <span class="review-rating">
            @include('trainer.partials.rating-stars', ['rating' => $review->rating, 'size' => 'sm'])
        </span>
    </div>

    @if (filled($review->comment))
        <p class="review-comment">{{ $review->comment }}</p>
    @else
        <p class="review-comment muted">Member tidak menuliskan komentar.</p>
    @endif

    <div class="review-meta">
        @if ($sessionDate)
            <span>Sesi {{ $sessionDate->locale('id')->isoFormat('D MMM YYYY') }}</span>
            <span class="sep" aria-hidden="true"></span>
        @endif
        @if ($reviewDate)
            <span>Diulas {{ $reviewDate->locale('id')->isoFormat('D MMM YYYY') }}</span>
        @endif
    </div>
</article>
