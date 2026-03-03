<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard - FTM Society</title>

    @vite('resources/css/app.css')

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- ========================================
         SIDEBAR
    ======================================== -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <a href="{{ route('member.profile') }}" class="px-6 py-5 text-xl font-bold border-b border-white/20 hover:bg-slate-800 transition inline-block w-full">
            FTM SOCIETY
        </a>

        <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
            <!-- Dashboard - ACTIVE -->
            <a href="{{ route('member.dashboard') }}" 
               class="block px-4 py-2 rounded bg-indigo-600 text-white font-medium">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>

            <!-- My Packages - LINK KE /member/packages -->
            <a href="{{ route('member.packages.index') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-box mr-2"></i>My Packages
            </a>

            <!-- Book Class -->
            <a href="{{ route('member.book') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-calendar-plus mr-2"></i>Book Class
            </a>

            <!-- My Classes -->
            <a href="{{ route('member.my-classes') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-dumbbell mr-2"></i>My Classes
            </a>

            <!-- Transactions -->
            <a href="{{ route('member.transactions') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-receipt mr-2"></i>Transactions
            </a>

            <!-- Attendance -->
            <a href="{{ route('member.attendance') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-calendar-check mr-2"></i>Attendance
            </a>

            <!-- Profile -->
            <a href="{{ route('member.account') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-user mr-2"></i>Profile
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
            © {{ date('Y') }} FTM Society
        </div>
    </aside>

    <!-- ========================================
         MAIN CONTENT
    ======================================== -->
    <main class="flex-1 p-8 overflow-y-auto">

        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Member Dashboard
            </h1>
            <p class="text-sm text-gray-500">
                Welcome back, {{ $customer->name }}
            </p>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Classes Remaining Card (untuk booking) -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 font-medium">Credit</p>
                    <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $remainingClasses }}
                </p>
                <p class="text-xs text-gray-500 mt-1">For booking</p>
            </div>

            <!-- Remaining Quota Card (untuk check-in) -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 font-medium">Remaining Quota</p>
                    <i class="fas fa-ticket-alt text-purple-500 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $remainingQuota }}
                </p>
                <p class="text-xs text-gray-500 mt-1">For check-in/out</p>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 font-medium">Status</p>
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-green-600">
                    Active
                </p>
            </div>
        </div>

        <!-- QR CARD + QUICK ACTIONS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <!-- MEMBER QR CARD -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg p-6 text-center text-white relative overflow-hidden" style="min-height: 400px;">
                <!-- Card Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500 rounded-full"></div>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="text-xs font-bold tracking-widest text-purple-300 mb-1">FTM SOCIETY</div>
                        <div class="text-2xl font-bold">MEMBER CARD</div>
                    </div>

                    <!-- Member Info -->
                    <div class="flex-1 flex flex-col justify-center mb-6">
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-purple-400 to-blue-400 flex items-center justify-center text-3xl font-bold shadow-lg mb-3">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold">{{ $customer->name }}</h3>
                            <p class="text-sm text-gray-300 mt-1">Member ID: <span class="font-mono font-bold">{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span></p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="mb-4 bg-white rounded-lg p-3 flex justify-center" style="height: 140px;">
                        @if($customer->qr_token && $customer->qr_active)
                            <div class="flex items-center justify-center w-full cursor-pointer" onclick="openQRPreview()" title="Click to enlarge QR Code">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode($customer->getQRData()) }}" alt="QR Code" style="max-width: 130px;" class="hover:scale-105 transition-transform duration-200">
                            </div>
                        @else
                            <div class="flex items-center justify-center w-full text-gray-400">
                                <div class="text-center">
                                    <i class="fas fa-qrcode text-4xl mb-2" style="color: #ccc;"></i>
                                    <p class="text-xs">No QR Code</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Tap hint -->
                    @if($customer->qr_token && $customer->qr_active)
                    <p class="text-[10px] text-gray-400 -mt-2 mb-2"><i class="fas fa-expand-alt mr-1"></i>Tap QR to enlarge</p>
                    @endif

                    <!-- Status -->
                    <div class="text-xs text-center text-gray-300 border-t border-white/20 pt-3">
                        @if($customer->qr_active)
                            <span class="inline-block bg-green-500/30 text-green-300 px-3 py-1 rounded-full text-xs font-bold">✓ QR ACTIVE</span>
                        @else
                            <span class="inline-block bg-red-500/30 text-red-300 px-3 py-1 rounded-full text-xs font-bold">✗ QR INACTIVE</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS + ATTENDANCE -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Quick Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('member.account') }}" class="bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-qrcode"></i>
                            <span>My QR Card</span>
                        </a>
                        <a href="{{ route('member.attendance') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-history"></i>
                            <span>History</span>
                        </a>
                        <a href="{{ route('member.book') }}" class="bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Book Now</span>
                        </a>
                    </div>
                </div>

                <!-- QR Info Guide -->
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-xl p-4">
                    <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        How to Use Your QR Card
                    </h4>
                    <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                        <li>Show this QR code to the staff/trainer</li>
                        <li>Staff scans your QR code at the scanner</li>
                        <li>Your attendance is recorded automatically</li>
                        <li>Your quota visit is deducted by 1</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- ATTENDANCE HISTORY -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-indigo-600"></i>
                Recent Attendance
            </h3>

            <div class="divide-y divide-gray-100">
                @forelse($attendances->take(5) as $a)
                    <div class="py-4 flex justify-between items-center hover:bg-gray-50 px-2 rounded transition">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 font-semibold">{{ $a->program ?? 'General' }}</p>
                                <p class="text-xs text-gray-500">{{ $a->check_in_type ?? 'system' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-800 font-semibold">{{ $a->getFormattedDuration() ?? '-' }}</p>
                            <p class="text-gray-500 text-sm">
                                {{ $a->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-list text-4xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">
                            No attendance yet.
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            Scan your QR code to record attendance
                        </p>
                        <a href="{{ route('member.qr.scanner') }}" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                            Start Scanning
                        </a>
                    </div>
                @endforelse
            </div>

            @if($attendances->count() > 5)
                <div class="text-center mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('member.attendance') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm">
                        View All Attendance Records →
                    </a>
                </div>
            @endif
        </div>

    </main>
</div>

<!-- ═══════════════════════════════════════════
     QR CODE PREVIEW MODAL
═══════════════════════════════════════════ -->
@if($customer->qr_token && $customer->qr_active)
<div id="qr-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background: rgba(0,0,0,0.85); backdrop-filter: blur(12px);">
    <!-- Close area (clicking background closes) -->
    <div class="absolute inset-0" onclick="closeQRPreview()"></div>

    <!-- Modal Content -->
    <div class="relative z-10 w-full max-w-md mx-4 animate-scale-in">
        <!-- Close Button -->
        <button onclick="closeQRPreview()" 
                class="absolute -top-3 -right-3 w-10 h-10 bg-slate-800/60 hover:bg-slate-800/80 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-lg transition-all duration-200 border border-white/20 z-20">
            <i class="fas fa-times"></i>
        </button>

        <!-- Card -->
        <div class="rounded-3xl p-8 shadow-2xl relative overflow-hidden" style="background: linear-gradient(160deg, #F9E8F1 0%, #F1CCE3 60%, rgba(210,220,165,0.60) 100%);">
            <!-- Decorative circles -->
            <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full" style="background: rgba(234,105,147,0.25);"></div>
            <div class="absolute -bottom-12 -left-12 w-36 h-36 rounded-full" style="background: rgba(0,116,95,0.18);"></div>

            <!-- Header -->
            <div class="text-center mb-6 relative z-10">
                <p class="text-[10px] font-extrabold tracking-[0.3em] text-indigo-600 uppercase mb-1">FTM Society</p>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-wide">MEMBER CARD</h2>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-6 relative z-10">
                <div class="bg-white rounded-2xl p-5 shadow-lg shadow-black/20">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($customer->getQRData()) }}&bgcolor=ffffff&color=0f172a" 
                         alt="QR Code" 
                         class="block"
                         style="width: 240px; height: 240px;">
                </div>
            </div>

            <!-- Member Info -->
            <div class="text-center relative z-10 mb-5">
                <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-xl font-extrabold text-white shadow-lg mb-3">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <h3 class="text-lg font-bold text-slate-800">{{ $customer->name }}</h3>
                <p class="text-sm text-slate-500 font-mono font-bold mt-1">Member ID: #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- Status badge -->
            <div class="text-center relative z-10 mb-4">
                <span class="inline-flex items-center gap-2 bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 px-4 py-1.5 rounded-full text-xs font-bold">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    QR Active — Ready to Scan
                </span>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-300/40 pt-4 relative z-10">
                <p class="text-center text-[11px] text-slate-500">Show this code to staff for check-in</p>
            </div>
        </div>

        
    </div>
</div>

<style>
    @keyframes scale-in {
        0% { opacity: 0; transform: scale(0.9) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-scale-in {
        animation: scale-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    function openQRPreview() {
        const modal = document.getElementById('qr-preview-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeQRPreview() {
        const modal = document.getElementById('qr-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function downloadQRPreview() {
        const link = document.createElement('a');
        link.href = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ urlencode($customer->getQRData()) }}&bgcolor=ffffff&color=0f172a';
        link.download = 'ftm-society-qr-{{ str_pad($customer->id, 4, "0", STR_PAD_LEFT) }}.png';
        link.click();
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQRPreview();
    });
</script>
@endif

</body>
</html>