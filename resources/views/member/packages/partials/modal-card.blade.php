@php
    $price = (int) ($package->price ?? 0);
@endphp
<article class="group bg-white rounded-2xl p-5 border border-rose-100 hover:border-[#be185d] shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
    <div>
        <div class="mb-3">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-[#9d174d] border border-rose-100">
                Reguler
            </span>
        </div>

        <h4 class="text-lg font-bold text-slate-900 group-hover:text-[#9d174d] transition-colors leading-snug">
            {{ $package->name }}
        </h4>

        @if($package->description)
        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
            {{ $package->description }}
        </p>
        @endif

        @if($package->duration_days || $package->quota)
        <div class="flex flex-wrap gap-2 my-3.5">
            @if($package->duration_days)
            <span class="px-2.5 py-1 rounded-lg bg-rose-50/70 border border-rose-100 text-xs font-medium text-slate-700">
                📅 {{ $package->duration_days }} Hari
            </span>
            @endif
            @if($package->quota)
            <span class="px-2.5 py-1 rounded-lg bg-rose-50/70 border border-rose-100 text-xs font-medium text-slate-700">
                🧘 {{ $package->quota }} Sesi
            </span>
            @endif
        </div>
        @endif
    </div>

    <div class="pt-4 border-t border-rose-50 mt-2">
        <div class="flex items-baseline gap-1 mb-3">
            @if($price > 0)
            <span class="text-xs font-semibold text-slate-500">Rp</span>
            <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ number_format($price, 0, ',', '.') }}</span>
            @else
            <span class="text-lg font-bold text-slate-900">Hubungi Kami</span>
            @endif
        </div>
        @if($price > 0)
        <a href="{{ route('join.package', ['package' => $package->slug ?? $package->id]) }}"
           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-[#be185d] hover:text-white text-[#9d174d] font-semibold text-xs sm:text-sm transition-all duration-150 active:scale-95 border border-rose-200 hover:border-[#be185d] min-h-[44px]">
            <span>Beli Sekarang</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
            </svg>
        </a>
        @else
        <a href="{{ $package->whatsapp_inquiry_url }}" target="_blank" rel="noopener"
           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#128C7E] text-white font-semibold text-xs sm:text-sm transition-all duration-150 active:scale-95 border border-[#128C7E] min-h-[44px]">
            <i class="fab fa-whatsapp text-base" aria-hidden="true"></i>
            <span>Hubungi Kami via WhatsApp</span>
        </a>
        @endif
    </div>
</article>
