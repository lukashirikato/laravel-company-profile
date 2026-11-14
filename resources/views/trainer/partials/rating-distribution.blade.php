@php
    $total = max(1, (int) $distribution->sum());
@endphp
<div class="distribution">
    @foreach ($distribution as $star => $count)
        @php($percent = $count / $total * 100)
        <div class="distribution-row">
            <span class="distribution-label">
                {{ $star }} <span aria-hidden="true">★</span>
            </span>
            <span class="distribution-track" role="img" aria-label="{{ $count }} ulasan {{ $star }} bintang">
                <span class="distribution-fill" style="width: {{ $percent }}%"></span>
            </span>
            <span class="distribution-count">{{ $count }}</span>
        </div>
    @endforeach
</div>
