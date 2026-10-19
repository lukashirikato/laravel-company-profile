<x-filament::page>
    @php
        $pageWireId  = $_instance->id ?? null;
        $insideMembers = collect($recentScans)->where('status', 'active')->values();
    @endphp

    <script>
        (function () {
            function focusScanner() {
                setTimeout(function () {
                    var el = document.getElementById('qr-token-input');
                    if (!el) return;
                    el.focus();
                    if (el.value) el.select();
                }, 60);
            }

            if (!window.__ftmQrFocusBound) {
                window.__ftmQrFocusBound = true;
                window.addEventListener('scanner-focus', focusScanner);
            }

            if (!window.__ftmQrKeysBound) {
                window.__ftmQrKeysBound = true;
                document.addEventListener('keydown', function (e) {
                    var page = document.querySelector('.qr-scanner-page');
                    if (!page) return;

                    var inPage = page.contains(e.target) || e.target === document.body;
                    if (!inPage) return;

                    if (e.key === 'Escape') {
                        e.preventDefault();
                        var wireId = page.getAttribute('data-wire-id');
                        if (wireId && window.Livewire) {
                            var component = window.Livewire.find(wireId);
                            if (component) component.call('resetForm');
                        }
                        return;
                    }

                    if (e.key === '/' && !e.target.matches('input, textarea, select')) {
                        e.preventDefault();
                        focusScanner();
                    }
                });
            }
        })();

        function ftmScanner() {
            return {
                hh: '--',
                mm: '--',
                ss: '--',
                date: '',
                init() {
                    var tick = function () {
                        var d = new Date();
                        var p = function (n) { return String(n).padStart(2, '0'); };
                        this.hh = p(d.getHours());
                        this.mm = p(d.getMinutes());
                        this.ss = p(d.getSeconds());
                        this.date = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    }.bind(this);

                    tick();
                    setInterval(tick, 1000);

                    this.$nextTick(function () {
                        window.dispatchEvent(new Event('scanner-focus'));
                    });
                },
            };
        }
    </script>

    <div class="qr-scanner-page grid grid-cols-1 gap-5 lg:grid-cols-3" data-wire-id="{{ $pageWireId }}" x-data="ftmScanner()">

        {{-- ============ KIRI: konsol scan + hasil ============ --}}
        <div class="space-y-5 lg:col-span-2">
            {{-- Topbar info: mode + jam --}}
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-primary-500/20 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-check-circle class="h-6 w-6 text-primary-500" />
                    <div>
                        <span class="text-sm font-semibold text-gray-900">FTM Society — Meja check-in</span>
                        <div class="text-xs text-gray-500">
                            Mode {{ $isCheckOutMode ? 'Keluar' : 'Masuk' }}
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500" x-text="date">&nbsp;</div>
                    <div class="font-mono text-xl font-semibold tabular-nums text-gray-900">
                        <span x-text="hh">--</span>:<span x-text="mm">--</span>:<span x-text="ss">--</span>
                    </div>
                </div>
            </div>

            {{-- Mode switch --}}
            <div class="mb-4 grid grid-cols-2 gap-2">
                <button
                    type="button"
                    @if(!$isCheckOutMode) disabled @endif
                    wire:click="setMode('in')"
                    class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold transition
                        {{ !$isCheckOutMode
                            ? 'bg-primary-600 text-white shadow-sm'
                            : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}"
                >
                    Check-in · masuk
                </button>
                <button
                    type="button"
                    @if($isCheckOutMode) disabled @endif
                    wire:click="setMode('out')"
                    class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold transition
                        {{ $isCheckOutMode
                            ? 'bg-primary-600 text-white shadow-sm'
                            : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}"
                >
                    Check-out · keluar
                </button>
            </div>

            {{-- Form scan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form wire:submit.prevent="{{ $isCheckOutMode ? 'submitCheckOut' : 'submitScan' }}">
                    <label for="qr-token-input" class="mb-2 block text-sm font-medium text-gray-700">
                        {{ $isCheckOutMode ? 'Barcode / ID member (untuk check-out)' : 'Barcode / ID member' }}
                    </label>

                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-identification class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                            id="qr-token-input"
                            type="text"
                            wire:model.defer="qrToken"
                            autofocus
                            autocomplete="off"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            placeholder="Contoh: 0034 · ORD-2026-001 · qr_token"
                            x-on:focus="$event.target.select()"
                            class="block w-full rounded-lg border-0 py-3 pl-10 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm"
                        />
                    </div>

                    @if($errorMessage)
                        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-exclamation-circle class="h-5 w-5 shrink-0 text-red-500" />
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-700">{{ $errorMessage }}</p>
                                    <p class="mt-1 text-xs text-red-500">Input sudah dikosongkan — scan ulang.</p>
                                </div>
                                <button type="button" wire:click="dismissResults" class="text-xs font-semibold text-red-600 hover:text-red-500">tutup</button>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submitScan,submitCheckOut"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="submitScan,submitCheckOut">
                                {{ $isCheckOutMode ? 'Proses check-out' : 'Proses check-in' }}
                            </span>
                            <span wire:loading wire:target="submitScan,submitCheckOut">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Memproses
                                </span>
                            </span>
                        </button>
                        <button type="button" wire:click="resetForm" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                            Reset <kbd class="ml-1 rounded border border-gray-300 px-1 text-xs">Esc</kbd>
                        </button>
                    </div>

                    <div class="mt-4 text-xs text-gray-500">
                        Format diterima: <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-gray-700">0034</code>
                        <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-gray-700">ORD-2026-001</code>
                        <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-gray-700">qr_token</code>
                        <span class="ml-2"><kbd class="rounded border border-gray-300 px-1 text-xs">/</kbd> fokus ke input</span>
                    </div>
                </form>
            </div>

            {{-- HASIL: check-in --}}
            @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'success')
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                            <x-heroicon-o-check class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Check-in berhasil</h3>
                            <p class="text-xs text-gray-500">Member tercatat masuk ke lantai gym</p>
                        </div>
                    </div>
                </div>
                <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Member</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['member_name'] }}</dd>
                        <dd class="text-xs text-gray-500">ID {{ $scanResults['member_id'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['class_name'] ?? $scanResults['program'] ?? '-' }}</dd>
                        <dd class="text-xs text-gray-500">
                            {{ $scanResults['package_name'] }}
                            @if(!empty($scanResults['is_exclusive']))
                                <span class="ml-1 inline-flex items-center rounded-md bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700">Exclusive</span>
                            @endif
                        </dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Masuk</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $scanResults['check_in_time'] }}</dd>
                        <dd class="text-xs text-gray-500">
                            {{ $scanResults['check_in_date'] }}
                            @if(!empty($scanResults['schedule_time']) && $scanResults['schedule_time'] !== '-')
                                · kelas {{ $scanResults['schedule_time'] }}
                            @endif
                        </dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Sisa kuota</dt>
                        @if(!empty($scanResults['is_exclusive']))
                            <dd class="mt-1 text-sm font-semibold text-gray-900">&infin;</dd>
                            <dd class="text-xs text-gray-500">Paket exclusive — kuota tidak berkurang</dd>
                        @else
                            <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">
                                {{ $scanResults['remaining_quota'] }} <span class="text-xs font-normal text-gray-500">/ {{ $scanResults['total_quota'] }}</span>
                            </dd>
                            @if(($scanResults['total_quota'] ?? 0) > 0)
                                <dd class="text-xs text-gray-500">{{ round(($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100) }}% tersisa</dd>
                            @endif
                        @endif
                    </div>
                </dl>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-6 py-3">
                    <p class="text-xs text-gray-500">Input sudah dikosongkan untuk scan berikutnya.</p>
                    <button type="button" wire:click="dismissResults" class="rounded-lg border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Scan berikutnya →
                    </button>
                </div>
            </div>
            @endif

            {{-- HASIL: member sedang latihan --}}
            @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'already_active')
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                            <x-heroicon-o-information-circle class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Member sedang latihan</h3>
                            <p class="text-xs text-gray-500">Sudah check-in, belum check-out</p>
                        </div>
                    </div>
                </div>
                <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Member</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['member_name'] }}</dd>
                        <dd class="text-xs text-gray-500">ID {{ $scanResults['member_id'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['class_name'] }}</dd>
                        <dd class="text-xs text-gray-500">Sudah {{ $scanResults['elapsed_minutes'] ?? '-' }} menit di dalam</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Masuk</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $scanResults['check_in_time'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Auto-checkout</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">
                            {{ $scanResults['auto_checkout_in'] ?? '-' }} <span class="text-xs font-normal text-gray-500">mnt</span>
                        </dd>
                        <dd class="text-xs text-gray-500">Jika tidak checkout manual</dd>
                    </div>
                </dl>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-6 py-3">
                    <p class="text-xs text-gray-500">Scan ulang juga akan mencatat check-out.</p>
                    <button type="button" wire:click="dismissResults" class="rounded-lg border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Mengerti →
                    </button>
                </div>
            </div>
            @endif

            {{-- HASIL: auto-checkout --}}
            @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'auto_checkout')
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                            <x-heroicon-o-check class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Auto-checkout — sesi berakhir</h3>
                            <p class="text-xs text-gray-500">Member ditutup otomatis melewati batas sesi</p>
                        </div>
                    </div>
                </div>
                <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-4 sm:divide-x sm:divide-y-0">
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Member</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['member_name'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Kelas</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $scanResults['class_name'] ?? '-' }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Masuk</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $scanResults['check_in_time'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Keluar (auto)</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $scanResults['auto_checkout_time'] }}</dd>
                    </div>
                </dl>
                <div class="border-t border-gray-100 bg-gray-50 px-6 py-3 text-center">
                    <p class="text-sm font-medium text-gray-700">Durasi latihan</p>
                    <p class="text-xl font-semibold tabular-nums text-gray-900">{{ $scanResults['duration'] }}</p>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-6 py-3">
                    <p class="text-xs text-gray-500">Sesi ditutup otomatis, tidak perlu scan lagi.</p>
                    <button type="button" wire:click="dismissResults" class="rounded-lg border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Scan berikutnya →
                    </button>
                </div>
            </div>
            @endif

            {{-- HASIL: check-out --}}
            @if(!empty($checkOutResults) && $checkOutResults['success'])
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-white px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                            <x-heroicon-o-check class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Check-out berhasil</h3>
                            <p class="text-xs text-gray-500">Latihan member sudah ditutup</p>
                        </div>
                    </div>
                </div>
                <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Member</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $checkOutResults['member_name'] }}</dd>
                        <dd class="text-xs text-gray-500">ID {{ $checkOutResults['member_id'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Paket</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $checkOutResults['package_name'] }}</dd>
                        <dd class="text-xs text-gray-500">{{ $checkOutResults['program'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Masuk</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $checkOutResults['check_in_time'] }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Keluar</dt>
                        <dd class="mt-1 text-sm font-semibold tabular-nums text-gray-900">{{ $checkOutResults['check_out_time'] }}</dd>
                    </div>
                </dl>
                <div class="border-t border-gray-100 bg-gray-50 px-6 py-3 text-center">
                    <p class="text-sm font-medium text-gray-700">Durasi latihan</p>
                    <p class="text-xl font-semibold tabular-nums text-gray-900">{{ $checkOutResults['duration'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $checkOutResults['duration_minutes'] }} menit · kuota sisa {{ $checkOutResults['remaining_quota'] }}/{{ $checkOutResults['total_quota'] }}</p>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-6 py-3">
                    <p class="text-xs text-gray-500">Notifikasi durasi dikirim ke member via WhatsApp.</p>
                    <button type="button" wire:click="dismissResults" class="rounded-lg border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Scan berikutnya →
                    </button>
                </div>
            </div>
            @endif

            {{-- RIWAYAT --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Riwayat hari ini</h3>
                    <span class="text-xs text-gray-500">{{ now()->format('d/m/Y') }} · {{ count($recentScans) }} entri</span>
                </div>
                @if(!empty($recentScans))
                    <ul class="divide-y divide-gray-100">
                        @foreach($recentScans as $scan)
                        <li class="flex items-center gap-4 px-6 py-3">
                            <span class="w-14 shrink-0 font-mono text-xs tabular-nums text-gray-500">{{ $scan['time'] }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium text-gray-900">{{ $scan['member'] }}</span>
                                <span class="block truncate text-xs text-gray-500">
                                    {{ $scan['class_name'] ?? '-' }}
                                    @if($scan['status'] === 'completed')
                                        · keluar {{ $scan['check_out_time'] }} · {{ $scan['duration'] }}
                                    @endif
                                </span>
                            </span>
                            <span class="shrink-0">
                                @if($scan['status'] === 'completed')
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Selesai</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-medium text-primary-700">Di dalam</span>
                                @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-medium text-gray-700">Belum ada scan hari ini</p>
                        <p class="mt-1 text-xs text-gray-500">Aktivitas muncul di sini sejak scan pertama.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============ KANAN: papan okupansi + aturan ============ --}}
        <div class="space-y-5">
            {{-- PAPAN: DI GYM SEKARANG --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Di gym sekarang</h3>
                        <p class="text-xs text-gray-500">Belum check-out</p>
                    </div>
                    <span class="text-3xl font-semibold tabular-nums text-primary-600">{{ $insideMembers->count() }}</span>
                </div>

                @if($insideMembers->isNotEmpty())
                    <ul class="max-h-72 divide-y divide-gray-100 overflow-y-auto">
                        @foreach($insideMembers as $member)
                        <li class="flex items-center gap-3 px-6 py-3">
                            <span class="w-14 shrink-0 font-mono text-xs tabular-nums text-primary-600">{{ $member['time'] }}</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-medium text-gray-900">{{ $member['member'] }}</span>
                                <span class="block truncate text-xs text-gray-500">{{ $member['class_name'] ?? '-' }}</span>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-medium text-gray-700">Ruang kosong</p>
                        <p class="mt-1 text-xs text-gray-500">Belum ada yang check-in. Scan member pertama.</p>
                    </div>
                @endif

                <div class="grid grid-cols-3 divide-x divide-gray-100 border-t border-gray-100 bg-gray-50 text-center">
                    <div class="px-4 py-3">
                        <p class="text-lg font-semibold tabular-nums text-gray-900">{{ $todayStats['total'] }}</p>
                        <p class="text-[10px] font-medium uppercase tracking-wide text-gray-500">Total scan</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-lg font-semibold tabular-nums text-emerald-600">{{ $todayStats['success'] }}</p>
                        <p class="text-[10px] font-medium uppercase tracking-wide text-gray-500">Berhasil</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-lg font-semibold tabular-nums text-red-500">{{ $todayStats['error'] }}</p>
                        <p class="text-[10px] font-medium uppercase tracking-wide text-gray-500">Gagal</p>
                    </div>
                </div>
            </div>

            {{-- ATURAN --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">{{ $isCheckOutMode ? 'Aturan check-out' : 'Aturan check-in' }}</h3>
                </div>
                <ul class="space-y-2 px-6 py-4 text-sm text-gray-600">
                    @if(!$isCheckOutMode)
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Scan QR member atau ketik ID, lalu tekan Enter.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Check-in dibuka &plusmn;60 menit dari jam kelas.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Satu check-in per kelas per hari — scan ulang ditolak.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Kuota paket berkurang tiap check-in, kecuali paket exclusive.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Member dengan 2+ kelas hari ini diminta memilih kelas dulu.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>WhatsApp notifikasi terkirim otomatis ke member.</li>
                    @else
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Scan member yang masih di dalam — atau biarkan auto-checkout.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>Durasi latihan dihitung otomatis dari jam masuk.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500"></span>WhatsApp berisi durasi dikirim ke member setelah keluar.</li>
                    @endif
                </ul>
                <div class="flex flex-wrap gap-2 border-t border-gray-100 bg-gray-50 px-6 py-3 text-xs text-gray-500">
                    <span><kbd class="rounded border border-gray-300 bg-white px-1.5 py-0.5 text-xs">Enter</kbd> proses</span>
                    <span><kbd class="rounded border border-gray-300 bg-white px-1.5 py-0.5 text-xs">Esc</kbd> reset</span>
                    <span><kbd class="rounded border border-gray-300 bg-white px-1.5 py-0.5 text-xs">/</kbd> fokus input</span>
                </div>
            </div>
        </div>

        {{-- MODAL PILIH KELAS --}}
        @if($showScheduleSelector && !empty($todaySchedules))
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="desk-pick-title">
            <div class="absolute inset-0 bg-gray-900/50"></div>
            <div class="relative w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-2xl">
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                    <div>
                        <h3 id="desk-pick-title" class="text-base font-semibold text-gray-900">Pilih kelas</h3>
                        <p class="text-xs text-gray-500">{{ count($todaySchedules) }} booking hari ini — kelas siap check-in ditandai biru.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-medium text-primary-700">Check-in</span>
                </div>

                <div class="max-h-[70vh] space-y-3 overflow-y-auto px-6 py-5">
                    @foreach($todaySchedules as $schedule)
                    <button
                        type="button"
                        wire:click="confirmScheduleSelection({{ $schedule['schedule_id'] }})"
                        class="block w-full rounded-lg border px-5 py-4 text-left transition
                            {{ ($schedule['is_within_window'] ?? false)
                                ? 'border-primary-500 bg-primary-50 hover:bg-primary-100'
                                : 'border-gray-200 bg-white hover:bg-gray-50' }}"
                    >
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-gray-900">{{ $schedule['class_name'] }}</span>
                            @if($schedule['is_within_window'] ?? false)
                                <span class="inline-flex items-center rounded-full bg-primary-600 px-2 py-0.5 text-xs font-medium text-white">Siap check-in</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Di luar waktu</span>
                            @endif
                            @if($schedule['is_exclusive'] ?? false)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Exclusive</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span><span class="font-semibold text-gray-700">{{ $schedule['class_time'] }}</span> jam kelas</span>
                            <span>{{ $schedule['instructor'] }}</span>
                            <span>{{ $schedule['location'] }}</span>
                            <span><span class="font-semibold text-gray-700">{{ $schedule['booked'] }}/{{ $schedule['capacity'] }}</span> peserta</span>
                        </div>
                    </button>
                    @endforeach
                </div>

                <div class="flex items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-6 py-3">
                    <span class="text-xs text-gray-500">Tekan <kbd class="rounded border border-gray-300 bg-white px-1.5 py-0.5 text-xs">Esc</kbd> untuk batal</span>
                    <button type="button" wire:click="resetForm" class="rounded-lg border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal / scan ulang
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament::page>