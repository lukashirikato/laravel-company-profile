<x-filament::page>
    <div class="max-w-7xl mx-auto">
        {{-- ===== HEADER MODE SELECTOR ===== --}}
        <div class="mb-6">
            <div class="flex rounded-lg overflow-hidden border-2 border-gray-200 bg-white shadow-sm">
                {{-- Check-in Tab --}}
                <button 
                    wire:click="toggleCheckOutMode"
                    class="flex-1 py-4 px-6 flex items-center justify-center gap-3 transition-all duration-200 {{ !$isCheckOutMode ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}"
                    {{ !$isCheckOutMode ? 'disabled' : '' }}
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <div class="text-left">
                        <span class="block font-bold text-lg">CHECK-IN</span>
                        <span class="block text-xs {{ !$isCheckOutMode ? 'text-emerald-100' : 'text-gray-400' }}">Member Masuk</span>
                    </div>
                </button>
                
                {{-- Check-out Tab --}}
                <button 
                    wire:click="toggleCheckOutMode"
                    class="flex-1 py-4 px-6 flex items-center justify-center gap-3 transition-all duration-200 border-l-2 border-gray-200 {{ $isCheckOutMode ? 'bg-orange-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}"
                    {{ $isCheckOutMode ? 'disabled' : '' }}
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <div class="text-left">
                        <span class="block font-bold text-lg">CHECK-OUT</span>
                        <span class="block text-xs {{ $isCheckOutMode ? 'text-orange-100' : 'text-gray-400' }}">Member Keluar</span>
                    </div>
                </button>
            </div>
        </div>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="grid grid-cols-12 gap-6">
            
            {{-- LEFT: Form & Results (8 cols) --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">
                
                {{-- FORM CARD --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Form Header --}}
                    <div class="px-6 py-4 {{ $isCheckOutMode ? 'bg-orange-600' : 'bg-emerald-600' }}">
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Member ID / Kode Order
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.live="qrToken"
                                    placeholder="Ketik ID member atau kode order..."
                                    autofocus
                                    class="w-full h-14 px-4 text-lg font-mono border-2 rounded-lg transition-all {{ $errorMessage ? 'border-red-500 bg-red-50' : ($isCheckOutMode ? 'border-orange-300 focus:border-orange-500 focus:ring-orange-200' : 'border-emerald-300 focus:border-emerald-500 focus:ring-emerald-200') }} focus:ring-4 focus:outline-none"
                                />
                                
                                {{-- Error Message --}}
                                @if($errorMessage)
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-medium text-red-700">{{ $errorMessage }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-3">
                                <button 
                                    type="submit"
                                    class="flex-1 h-14 rounded-lg font-bold text-white text-lg transition-all flex items-center justify-center gap-2 {{ $isCheckOutMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-emerald-600 hover:bg-emerald-700' }}"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $isCheckOutMode ? 'PROSES CHECK-OUT' : 'PROSES CHECK-IN' }}
                                </button>
                                <button 
                                    type="button"
                                    wire:click="resetForm"
                                    class="h-14 px-6 rounded-lg font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all"
                                >
                                    Reset
                                </button>
                            </div>
                        </form>
                        
                        {{-- Format Hint --}}
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Format yang diterima:</p>
                            <div class="flex flex-wrap gap-2">
                                <code class="px-2 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700">0034</code>
                                <code class="px-2 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700">34</code>
                                <code class="px-2 py-1 bg-white border border-gray-300 rounded text-sm text-gray-700">ORD-2026-001</code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CLASS SELECTOR (shown when member has 2+ bookings today) --}}
                @if($showScheduleSelector && count($todaySchedules) > 1)
                <div class="bg-white rounded-lg shadow-sm border-2 border-blue-400 overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600">
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
                            class="w-full text-left p-4 rounded-lg border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all group"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $s['class_name'] }}</p>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-semibold text-blue-600">{{ $s['class_time'] }}</span>
                                            &bull; {{ $s['instructor'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($s['is_exclusive'])
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Exclusive</span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Regular</span>
                                    @endif
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        @endforeach
                        <button
                            wire:click="resetForm"
                            class="w-full mt-2 py-3 rounded-lg text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-all"
                        >
                            Batal / Scan Ulang
                        </button>
                    </div>
                </div>
                @endif

                {{-- SUCCESS: Check-in Result --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'success')
                <div class="bg-white rounded-lg shadow-sm border-2 border-emerald-500 overflow-hidden">
                    <div class="px-6 py-4 bg-emerald-500">
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
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['member_name'] }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $scanResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Kelas / Paket</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['class_name'] ?? $scanResults['program'] ?? '-' }}</p>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    {{ $scanResults['package_name'] }}
                                    @if(!empty($scanResults['is_exclusive']))
                                        <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded">Exclusive</span>
                                    @endif
                                </p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Waktu Check-in</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['check_in_time'] }}</p>
                                <p class="text-sm text-gray-500">{{ $scanResults['check_in_date'] }}
                                    @if(!empty($scanResults['schedule_time']) && $scanResults['schedule_time'] !== '-')
                                        &bull; Kelas: {{ $scanResults['schedule_time'] }}
                                    @endif
                                </p>
                            </div>
                            <div class="p-4 rounded-lg border-2 {{ !empty($scanResults['is_exclusive']) ? 'bg-purple-50 border-purple-200' : 'bg-emerald-50 border-emerald-200' }}">
                                <p class="text-xs uppercase mb-1 {{ !empty($scanResults['is_exclusive']) ? 'text-purple-600' : 'text-emerald-600' }}">Sisa Kuota</p>
                                @if(!empty($scanResults['is_exclusive']))
                                    <p class="font-bold text-purple-700 text-lg">&infin; Unlimited</p>
                                    <p class="text-xs text-purple-500">Paket Exclusive</p>
                                @else
                                    <p class="font-bold text-emerald-700 text-2xl">{{ $scanResults['remaining_quota'] }} <span class="text-sm font-normal">/ {{ $scanResults['total_quota'] }}</span></p>
                                @endif
                            </div>
                        </div>
                        @if(empty($scanResults['is_exclusive']) && ($scanResults['total_quota'] ?? 0) > 0)
                        <div class="mt-4 bg-gray-100 rounded-lg p-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-600">Progress Kuota</span>
                                <span class="font-semibold">{{ round(($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100) }}%</span>
                            </div>
                            <div class="h-3 bg-gray-300 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- ALREADY ACTIVE: Member Sedang Latihan --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'already_active')
                <div class="bg-white rounded-lg shadow-sm border-2 border-blue-500 overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600">
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
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['member_name'] }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $scanResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Kelas</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['class_name'] }}</p>
                                <p class="text-sm text-gray-500">Sudah {{ $scanResults['elapsed_minutes'] }} menit</p>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                                <p class="text-xs text-blue-600 uppercase mb-1">Waktu Check-in</p>
                                <p class="font-bold text-blue-900">{{ $scanResults['check_in_time'] }}</p>
                                <p class="text-sm text-blue-600">Durasi: {{ $scanResults['elapsed_minutes'] }} menit</p>
                            </div>
                            <div class="p-4 bg-orange-50 rounded-lg border-2 border-orange-200">
                                <p class="text-xs text-orange-600 uppercase mb-1 font-semibold">⏱️ Auto-Checkout Dalam</p>
                                <p class="font-bold text-orange-700 text-2xl">{{ $scanResults['auto_checkout_in'] }} min</p>
                                <p class="text-xs text-orange-600 mt-1">Jika tidak manual checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- AUTO-CHECKOUT PERFORMED: Sesi Berakhir --}}
                @if(!empty($scanResults) && $scanResults['success'] && $scanResults['status'] === 'auto_checkout')
                <div class="bg-white rounded-lg shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="px-6 py-4 bg-orange-600">
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
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['member_name'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Waktu Masuk</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['check_in_time'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Waktu Keluar (Auto)</p>
                                <p class="font-bold text-gray-900">{{ $scanResults['auto_checkout_time'] }}</p>
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
                    <div class="px-6 py-4 bg-orange-500">
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
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Nama Member</p>
                                <p class="font-bold text-gray-900">{{ $checkOutResults['member_name'] }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $checkOutResults['member_id'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Paket</p>
                                <p class="font-bold text-gray-900">{{ $checkOutResults['package_name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $checkOutResults['program'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Waktu Masuk</p>
                                <p class="font-bold text-gray-900">{{ $checkOutResults['check_in_time'] }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase mb-1">Waktu Keluar</p>
                                <p class="font-bold text-gray-900">{{ $checkOutResults['check_out_time'] }}</p>
                            </div>
                        </div>
                        
                        {{-- Duration --}}
                        <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg text-center text-white">
                            <p class="text-sm uppercase tracking-wide opacity-80">Durasi Latihan</p>
                            <p class="text-4xl font-black mt-1">{{ $checkOutResults['duration'] }}</p>
                            <p class="text-sm opacity-80 mt-1">({{ $checkOutResults['duration_minutes'] }} menit)</p>
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                            <p class="text-xs text-blue-600 uppercase mb-1">Sisa Kuota</p>
                            <p class="font-bold text-blue-700 text-xl">{{ $checkOutResults['remaining_quota'] }} / {{ $checkOutResults['total_quota'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- RECENT ACTIVITY --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900">Aktivitas Hari Ini</h3>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @if(!empty($recentScans))
                            @foreach($recentScans as $scan)
                            <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full {{ $scan['status'] === 'completed' ? 'bg-emerald-100' : 'bg-amber-100' }} flex items-center justify-center flex-shrink-0">
                                        @if($scan['status'] === 'completed')
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900">{{ $scan['member'] }}</p>
                                        <p class="text-sm text-gray-500">
                                            <span class="text-blue-600 font-medium">{{ $scan['class_name'] ?? '-' }}</span>
                                            &bull; Masuk: {{ $scan['time'] }}
                                            @if($scan['status'] === 'completed')
                                                &bull; Keluar: {{ $scan['check_out_time'] }} &bull; <span class="text-purple-600 font-medium">{{ $scan['duration'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $scan['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $scan['status'] === 'completed' ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="px-6 py-16 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada aktivitas</p>
                                <p class="text-gray-400 text-sm">Aktivitas check-in/check-out akan tampil di sini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Stats & Guide (4 cols) --}}
            <div class="col-span-12 lg:col-span-4 space-y-6">
                
                {{-- STATISTICS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistik Hari Ini
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="bg-blue-600 rounded-lg p-5 text-center text-white mb-4">
                            <p class="text-blue-100 text-sm uppercase tracking-wide">Total Check-in</p>
                            <p class="text-5xl font-black">{{ $todayStats['total'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                                <p class="text-emerald-600 text-xs font-semibold uppercase">Sukses</p>
                                <p class="text-3xl font-black text-emerald-600">{{ $todayStats['success'] }}</p>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <p class="text-red-600 text-xs font-semibold uppercase">Gagal</p>
                                <p class="text-3xl font-black text-red-600">{{ $todayStats['error'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GUIDE --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 {{ $isCheckOutMode ? 'bg-orange-50 border-b border-orange-200' : 'bg-emerald-50 border-b border-emerald-200' }}">
                        <h3 class="font-bold {{ $isCheckOutMode ? 'text-orange-900' : 'text-emerald-900' }} flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $isCheckOutMode ? 'Panduan Check-out' : 'Panduan Check-in' }}
                        </h3>
                    </div>
                    <div class="p-5">
                        <ul class="space-y-3 text-sm text-gray-600">
                            @if(!$isCheckOutMode)
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Gunakan barcode scanner untuk efisiensi
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tekan Enter untuk submit otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Member harus punya booking hari ini
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Check-in dibuka &plusmn;60 menit dari jam kelas
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    1x check-in per kelas per booking per hari
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Paket Exclusive: kuota tidak berkurang
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Notifikasi WhatsApp dikirim otomatis
                                </li>
                            @else
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Hanya member yang sudah check-in hari ini
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tekan Enter untuk submit otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Durasi latihan dihitung otomatis
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Notifikasi durasi dikirim ke member
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- QUICK INFO --}}
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h4 class="font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Shortcut
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Submit</span>
                            <kbd class="px-2 py-1 bg-gray-700 rounded text-xs">Enter</kbd>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Reset Form</span>
                            <kbd class="px-2 py-1 bg-gray-700 rounded text-xs">Esc</kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
