<x-filament::page>
    <div class="max-w-7xl mx-auto">
        {{-- ===== HEADER MODE SELECTOR ===== --}}
        <div class="mb-6">
            <div class="flex rounded-lg overflow-hidden border-2 border-light-pink/50 bg-white shadow-sm">
                {{-- Check-in Tab --}}
                <button 
                    wire:click="toggleCheckOutMode"
                    class="flex-1 py-4 px-6 flex items-center justify-center gap-3 transition-all duration-200 {{ !$isCheckOutMode ? 'bg-accent text-white' : 'bg-white text-dark/70 hover:bg-cream' }}"
                    {{ !$isCheckOutMode ? 'disabled' : '' }}
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <div class="text-left">
                        <span class="block font-bold text-lg">CHECK-IN</span>
                        <span class="block text-xs {{ !$isCheckOutMode ? 'text-emerald-100' : 'text-dark/40' }}">Member Masuk</span>
                    </div>
                </button>
                
                {{-- Check-out Tab --}}
                <button 
                    wire:click="toggleCheckOutMode"
                    class="flex-1 py-4 px-6 flex items-center justify-center gap-3 transition-all duration-200 border-l-2 border-light-pink/50 {{ $isCheckOutMode ? 'bg-secondary text-white' : 'bg-white text-dark/70 hover:bg-cream' }}"
                    {{ $isCheckOutMode ? 'disabled' : '' }}
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <div class="text-left">
                        <span class="block font-bold text-lg">CHECK-OUT</span>
                        <span class="block text-xs {{ $isCheckOutMode ? 'text-orange-100' : 'text-dark/40' }}">Member Keluar</span>
                    </div>
                </button>
            </div>
        </div>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="grid grid-cols-12 gap-6">
            
            {{-- LEFT: Form & Results (8 cols) --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">
                
                {{-- FORM CARD --}}
                <div class="bg-white rounded-lg shadow-sm border border-light-pink/50 overflow-hidden">
                    {{-- Form Header --}}
                    <div class="px-6 py-4 {{ $isCheckOutMode ? 'bg-secondary' : 'bg-accent' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                                @if($isCheckOutMode)
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ $isCheckOutMode ? 'Form Check-out Member' : 'Form Check-in Member' }}
                                </h2>
                                <p class="text-sm {{ $isCheckOutMode ? 'text-orange-100' : 'text-emerald-100' }}">
                                    {{ $isCheckOutMode ? 'Catat waktu keluar member dari gym' : 'Catat waktu masuk member ke gym' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Form Body --}}
                    <div class="p-6">
                        <form wire:submit.prevent="{{ $isCheckOutMode ? 'submitCheckOut' : 'submitScan' }}">
                            {{-- Input Field --}}
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-dark mb-2">
                                    Member ID / Kode Order
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.live="qrToken"
                                    placeholder="Ketik ID member atau kode order..."
                                    autofocus
                                    class="w-full h-14 px-4 text-lg font-mono border-2 rounded-lg transition-all {{ $errorMessage ? 'border-red-500 bg-light-pink/30' : ($isCheckOutMode ? 'border-orange-300 focus:border-orange-500 focus:ring-orange-200' : 'border-emerald-300 focus:border-emerald-500 focus:ring-emerald-200') }} focus:ring-4 focus:outline-none"
                                />
                                
                                {{-- Error Message --}}
                                @if($errorMessage)
                                    <div class="mt-3 p-3 bg-light-pink/30 border border-secondary/30 rounded-lg flex items-center gap-2">
                                        <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium text-secondary">{{ $errorMessage }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-3">
                                <button 
                                    type="submit"
                                    class="flex-1 h-14 rounded-lg font-bold text-white text-lg transition-all flex items-center justify-center gap-2 {{ $isCheckOutMode ? 'bg-secondary hover:bg-orange-700' : 'bg-accent hover:bg-emerald-700' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $isCheckOutMode ? 'PROSES CHECK-OUT' : 'PROSES CHECK-IN' }}
                                </button>
                                <button 
                                    type="button"
                                    wire:click="resetForm"
                                    class="h-14 px-6 rounded-lg font-semibold text-dark bg-cream hover:bg-light-pink/30 transition-all"
                                >
                                    Reset
                                </button>
                            </div>
                        </form>
                        
                        {{-- Format Hint --}}
                        <div class="mt-6 p-4 bg-cream rounded-lg border border-light-pink/50">
                            <p class="text-xs font-semibold text-cream0 uppercase tracking-wide mb-2">Format yang diterima:</p>
                            <div class="flex flex-wrap gap-2">
                                <code class="px-2 py-1 bg-white border border-light-pink/60 rounded text-sm text-dark">0034</code>
                                <code class="px-2 py-1 bg-white border border-light-pink/60 rounded text-sm text-dark">34</code>
                                <code class="px-2 py-1 bg-white border border-light-pink/60 rounded text-sm text-dark">ORD-2026-001</code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CLASS SELECTOR (shown when member has 2+ bookings today) --}}
                @if($showScheduleSelector && count($todaySchedules) > 1)
                <div class="bg-white rounded-lg shadow-sm border-2 border-blue-400 overflow-hidden">
                    <div class="px-6 py-4 bg-primary">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">Pilih Kelas untuk Check-in</h3>
                                <p class="text-blue-100 text-sm">Member memiliki {{ count($todaySchedules) }} booking hari ini. Pilih kelas yang sesuai.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($todaySchedules as $s)
                        <button
                            wire:click="confirmScheduleSelection({{ $s['schedule_id'] }})"
                            class="w-full text-left p-4 rounded-lg border-2 border-light-pink/50 hover:border-blue-400 hover:bg-light-pink/30 transition-all group"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-light-pink/50 group-hover:bg-light-pink flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-dark">{{ $s['class_name'] }}</p>
                                        <p class="text-sm text-cream0">
                                            <span class="font-semibold text-primary">{{ $s['class_time'] }}</span>
                                            &bull; {{ $s['instructor'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($s['is_exclusive'])
                                        <span class="px-2 py-1 bg-light-pink/50 text-secondary text-xs font-semibold rounded-full">Exclusive</span>
                                    @else
                                        <span class="px-2 py-1 bg-grounded-green/40 text-springs-ivy text-xs font-semibold rounded-full">Regular</span>
                                    @endif
                                    <svg class="w-5 h-5 text-dark/40 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        @endforeach
                        <button
                            wire:click="resetForm"
                            class="w-full mt-2 py-3 rounded-lg text-sm font-semibold text-cream0 bg-cream hover:bg-light-pink/30 transition-all"
                        >
                            Batal / Scan Ulang
                        </button>
                    </div>
                </div>
                @endif

                {{-- SUCCESS: Check-in Result --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'success')
                <div class="bg-white rounded-lg shadow-sm border-2 border-emerald-500 overflow-hidden">
                    <div class="px-6 py-4 bg-grounded-green/200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">CHECK-IN BERHASIL</h3>
                                <p class="text-emerald-100 text-sm">Member telah tercatat masuk</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-dark">{{ $scanResults['member_name'] }}</p>
                                <p class="text-sm text-cream0">ID: {{ $scanResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Kelas / Paket</p>
                                <p class="font-bold text-dark">{{ $scanResults['class_name'] ?? $scanResults['program'] ?? '-' }}</p>
                                <p class="text-sm text-cream0 flex items-center gap-1">
                                    {{ $scanResults['package_name'] }}
                                    @if(!empty($scanResults['is_exclusive']))
                                        <span class="px-1.5 py-0.5 bg-light-pink/50 text-secondary text-xs font-semibold rounded">Exclusive</span>
                                    @endif
                                </p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Waktu Check-in</p>
                                <p class="font-bold text-dark">{{ $scanResults['check_in_time'] }}</p>
                                <p class="text-sm text-cream0">{{ $scanResults['check_in_date'] }}
                                    @if(!empty($scanResults['schedule_time']) && $scanResults['schedule_time'] !== '-')
                                        &bull; Kelas: {{ $scanResults['schedule_time'] }}
                                    @endif
                                </p>
                            </div>
                            <div class="p-4 rounded-lg border-2 {{ !empty($scanResults['is_exclusive']) ? 'bg-light-pink/30 border-primary/30' : 'bg-grounded-green/20 border-emerald-200' }}">
                                <p class="text-xs uppercase mb-1 {{ !empty($scanResults['is_exclusive']) ? 'text-primary' : 'text-accent' }}">Sisa Kuota</p>
                                @if(!empty($scanResults['is_exclusive']))
                                    <p class="font-bold text-secondary text-lg">&infin; Unlimited</p>
                                    <p class="text-xs text-primary">Paket Exclusive</p>
                                @else
                                    <p class="font-bold text-springs-ivy text-2xl">{{ $scanResults['remaining_quota'] }} <span class="text-sm font-normal">/ {{ $scanResults['total_quota'] }}</span></p>
                                @endif
                            </div>
                        </div>
                        @if(empty($scanResults['is_exclusive']) && ($scanResults['total_quota'] ?? 0) > 0)
                        <div class="mt-4 bg-cream rounded-lg p-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-dark/70">Progress Kuota</span>
                                <span class="font-semibold">{{ round(($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100) }}%</span>
                            </div>
                            <div class="h-3 bg-light-pink/40 rounded-full overflow-hidden">
                                <div class="h-full bg-grounded-green/200 rounded-full" style="width: {{ ($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- ALREADY ACTIVE: Member Sedang Latihan --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'already_active')
                <div class="bg-white rounded-lg shadow-sm border-2 border-blue-500 overflow-hidden">
                    <div class="px-6 py-4 bg-primary">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m0 0l-2-1m2 1v2.5M14 4l-2 1m0 0l-2-1m2 1v2.5"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">MEMBER SEDANG LATIHAN</h3>
                                <p class="text-blue-100 text-sm">Sudah check-in, belum check-out</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-dark">{{ $scanResults['member_name'] }}</p>
                                <p class="text-sm text-cream0">ID: {{ $scanResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Kelas</p>
                                <p class="font-bold text-dark">{{ $scanResults['class_name'] }}</p>
                                <p class="text-sm text-cream0">Sudah {{ $scanResults['elapsed_minutes'] }} menit</p>
                            </div>
                            <div class="p-4 bg-light-pink/30 rounded-lg border-2 border-primary/30">
                                <p class="text-xs text-primary uppercase mb-1">Waktu Check-in</p>
                                <p class="font-bold text-blue-900">{{ $scanResults['check_in_time'] }}</p>
                                <p class="text-sm text-primary">Durasi: {{ $scanResults['elapsed_minutes'] }} menit</p>
                            </div>
                            <div class="p-4 bg-light-pink/30 rounded-lg border-2 border-light-pink">
                                <p class="text-xs text-secondary uppercase mb-1 font-semibold">⏱️ Auto-Checkout Dalam</p>
                                <p class="font-bold text-secondary text-2xl">{{ $scanResults['auto_checkout_in'] }} min</p>
                                <p class="text-xs text-secondary mt-1">Jika tidak manual checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- AUTO-CHECKOUT PERFORMED: Sesi Berakhir --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'auto_checkout')
                <div class="bg-white rounded-lg shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="px-6 py-4 bg-secondary">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">AUTO-CHECKOUT - SESI BERAKHIR</h3>
                                <p class="text-orange-100 text-sm">Member telah otomatis checkout setelah 60 menit</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-dark">{{ $scanResults['member_name'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Waktu Masuk</p>
                                <p class="font-bold text-dark">{{ $scanResults['check_in_time'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Waktu Keluar (Auto)</p>
                                <p class="font-bold text-dark">{{ $scanResults['auto_checkout_time'] }}</p>
                            </div>
                        </div>
                        <div class="p-6 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg text-center text-white">
                            <p class="text-sm uppercase tracking-wide opacity-80">Durasi Latihan</p>
                            <p class="text-4xl font-black mt-1">{{ $scanResults['duration'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- SUCCESS: Check-in Result (DEPRECATED - kept for backward compat) --}}
                @if(!empty($scanResults) && $scanResults['success'])
                @endif

                {{-- SUCCESS: Check-out Result --}}
                @if(!empty($checkOutResults) && $checkOutResults['success'])
                <div class="bg-white rounded-lg shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="px-6 py-4 bg-light-pink/300">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">CHECK-OUT BERHASIL</h3>
                                <p class="text-orange-100 text-sm">Member telah selesai latihan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-dark">{{ $checkOutResults['member_name'] }}</p>
                                <p class="text-sm text-cream0">ID: {{ $checkOutResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Paket</p>
                                <p class="font-bold text-dark">{{ $checkOutResults['package_name'] }}</p>
                                <p class="text-sm text-cream0">{{ $checkOutResults['program'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Waktu Masuk</p>
                                <p class="font-bold text-dark">{{ $checkOutResults['check_in_time'] }}</p>
                            </div>
                            <div class="p-4 bg-cream rounded-lg">
                                <p class="text-xs text-cream0 uppercase mb-1">Waktu Keluar</p>
                                <p class="font-bold text-dark">{{ $checkOutResults['check_out_time'] }}</p>
                            </div>
                        </div>
                        
                        {{-- Duration --}}
                        <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg text-center text-white">
                            <p class="text-sm uppercase tracking-wide opacity-80">Durasi Latihan</p>
                            <p class="text-4xl font-black mt-1">{{ $checkOutResults['duration'] }}</p>
                            <p class="text-sm opacity-80 mt-1">({{ $checkOutResults['duration_minutes'] }} menit)</p>
                        </div>
                        
                        <div class="mt-4 p-4 bg-light-pink/30 rounded-lg border border-primary/30 text-center">
                            <p class="text-xs text-primary uppercase mb-1">Sisa Kuota</p>
                            <p class="font-bold text-secondary text-xl">{{ $checkOutResults['remaining_quota'] }} / {{ $checkOutResults['total_quota'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- RECENT ACTIVITY --}}
                <div class="bg-white rounded-lg shadow-sm border border-light-pink/50 overflow-hidden">
                    <div class="px-6 py-4 bg-cream border-b border-light-pink/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-light-pink/50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-dark">Aktivitas Hari Ini</h3>
                            </div>
                            <span class="text-xs text-cream0 bg-light-pink/30 px-2 py-1 rounded">{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @if(!empty($recentScans))
                            @foreach($recentScans as $scan)
                            <div class="px-6 py-4 border-b border-light-pink/30 hover:bg-cream transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full {{ $scan['status'] === 'completed' ? 'bg-grounded-green/40' : 'bg-grounded-green/40' }} flex items-center justify-center flex-shrink-0">
                                        @if($scan['status'] === 'completed')
                                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-springs-ivy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-dark">{{ $scan['member'] }}</p>
                                        <p class="text-sm text-cream0">
                                            <span class="text-primary font-medium">{{ $scan['class_name'] ?? '-' }}</span>
                                            &bull; Masuk: {{ $scan['time'] }}
                                            @if($scan['status'] === 'completed')
                                                &bull; Keluar: {{ $scan['check_out_time'] }} &bull; <span class="text-primary font-medium">{{ $scan['duration'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $scan['status'] === 'completed' ? 'bg-grounded-green/40 text-springs-ivy' : 'bg-grounded-green/40 text-springs-ivy' }}">
                                        {{ $scan['status'] === 'completed' ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="px-6 py-16 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-cream flex items-center justify-center">
                                    <svg class="w-8 h-8 text-dark/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-cream0 font-medium">Belum ada aktivitas</p>
                                <p class="text-dark/40 text-sm">Aktivitas check-in/check-out akan tampil di sini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Stats & Guide (4 cols) --}}
            <div class="col-span-12 lg:col-span-4 space-y-6">
                
                {{-- STATISTICS --}}
                <div class="bg-white rounded-lg shadow-sm border border-light-pink/50 overflow-hidden">
                    <div class="px-5 py-4 bg-cream border-b border-light-pink/50">
                        <h3 class="font-bold text-dark flex items-center gap-2">
                            <svg class="w-5 h-5 text-dark/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistik Hari Ini
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="bg-primary rounded-lg p-5 text-center text-white mb-4">
                            <p class="text-blue-100 text-sm uppercase tracking-wide">Total Check-in</p>
                            <p class="text-5xl font-black">{{ $todayStats['total'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-grounded-green/20 border border-emerald-200 rounded-lg p-4 text-center">
                                <p class="text-accent text-xs font-semibold uppercase">Sukses</p>
                                <p class="text-3xl font-black text-accent">{{ $todayStats['success'] }}</p>
                            </div>
                            <div class="bg-light-pink/30 border border-secondary/30 rounded-lg p-4 text-center">
                                <p class="text-secondary text-xs font-semibold uppercase">Gagal</p>
                                <p class="text-3xl font-black text-secondary">{{ $todayStats['error'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GUIDE --}}
                <div class="bg-white rounded-lg shadow-sm border border-light-pink/50 overflow-hidden">
                    <div class="px-5 py-4 {{ $isCheckOutMode ? 'bg-light-pink/30 border-b border-light-pink' : 'bg-grounded-green/20 border-b border-emerald-200' }}">
                        <h3 class="font-bold {{ $isCheckOutMode ? 'text-orange-900' : 'text-emerald-900' }} flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isCheckOutMode ? 'Panduan Check-out' : 'Panduan Check-in' }}
                        </h3>
                    </div>
                    <div class="p-5">
                        <ul class="space-y-3 text-sm text-dark/70">
                            @if(!$isCheckOutMode)
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Gunakan barcode scanner untuk efisiensi
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tekan Enter untuk submit otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Member harus punya booking hari ini
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-springs-ivy flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Check-in dibuka &plusmn;60 menit dari jam kelas
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    1x check-in per kelas per booking per hari
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Paket Exclusive: kuota tidak berkurang
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Notifikasi WhatsApp dikirim otomatis
                                </li>
                            @else
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Hanya member yang sudah check-in hari ini
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tekan Enter untuk submit otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Durasi latihan dihitung otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Notifikasi durasi dikirim ke member
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- QUICK INFO --}}
                <div class="bg-dark rounded-lg p-5 text-white">
                    <h4 class="font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Shortcut
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-dark/40">Submit</span>
                            <kbd class="px-2 py-1 bg-dark rounded text-xs">Enter</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-dark/40">Reset Form</span>
                            <kbd class="px-2 py-1 bg-dark rounded text-xs">Esc</kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SCHEDULE SELECTOR MODAL ===== --}}
    @if($showScheduleSelector && !empty($todaySchedules))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
            {{-- Header --}}
            <div class="mb-6 border-b pb-4">
                <h3 class="text-2xl font-bold text-dark flex items-center gap-2">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pilih Kelas untuk Check-in
                </h3>
                <p class="text-sm text-cream0 mt-1">Member memiliki {{ count($todaySchedules) }} kelas hari ini</p>
            </div>

            {{-- Class List --}}
            <div class="space-y-3">
                @foreach($todaySchedules as $schedule)
                <button 
                    wire:click="confirmScheduleSelection({{ $schedule['schedule_id'] }})"
                    class="w-full text-left border-2 border-light-pink/50 rounded-xl p-4 hover:border-indigo-500 hover:bg-light-pink/30 transition-all group">
                    
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            {{-- Class Name + Time Window Badge --}}
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-lg font-bold text-dark group-hover:text-primary">
                                    {{ $schedule['class_name'] }}
                                </h4>
                                @if($schedule['is_within_window'])
                                <span class="px-2 py-1 bg-grounded-green/40 text-springs-ivy text-xs font-bold rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Time Window Aktif
                                </span>
                                @endif
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-3 text-sm text-dark/70">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ $schedule['class_time'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $schedule['location'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $schedule['instructor'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>{{ $schedule['booked'] }} / {{ $schedule['capacity'] }} peserta</span>
                                </div>
                            </div>
                        </div>

                        {{-- Arrow Icon --}}
                        <div class="ml-4 flex items-center text-dark/40 group-hover:text-primary transition-transform group-hover:translate-x-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Cancel Button --}}
            <div class="mt-6 pt-4 border-t">
                <button 
                    wire:click="resetForm"
                    class="w-full px-4 py-3 bg-cream hover:bg-light-pink/30 text-dark font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</x-filament::page>
