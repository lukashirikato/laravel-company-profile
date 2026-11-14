@php
    $value = max(0, min(5, (float) ($rating ?? 0)));
    $percent = $value / 5 * 100;
    $size = $size ?? 'md';
    $showValue = $showValue ?? true;
@endphp
<span class="rating">
    <span class="stars {{ $size }}" role="img" aria-label="Rating {{ number_format($value, 1) }} dari 5">
        <span class="stars-track" aria-hidden="true">★★★★★</span>
        <span class="stars-fill" style="width: {{ $percent }}%" aria-hidden="true">★★★★★</span>
    </span>
    @if ($showValue)
        <span class="stars-value">{{ number_format($value, 1) }}</span>
    @endif
</span>
