@extends('layouts.app')

@section('content')
<style>
    /* ═══════════════════════════════════════════ RESPONSIVE SIDEBAR ═══════════════════════════════════════════ */
    .sidebar {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 20;
        backdrop-filter: blur(4px);
    }

    .hamburger-btn {
        display: none !important;
        position: fixed !important;
        top: 1rem !important;
        left: 1rem !important;
        z-index: 9999 !important;
        width: 3rem !important;
        height: 3rem !important;
        background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%) !important;
        color: white !important;
        border: none !important;
        border-radius: 0.5rem !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 12px rgba(122, 43, 74, 0.35) !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        font-size: 1.25rem !important;
    }

    .hamburger-btn:hover {
        background: linear-gradient(135deg, #5A1F3A 0%, #B83863 100%) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(122, 43, 74, 0.45) !important;
    }

    .hamburger-btn:active {
        transform: translateY(0) !important;
    }

    @media (max-width: 768px) {
        .hamburger-btn {
            display: flex !important;
        }

        .sidebar-overlay.active {
            display: block !important;
        }
    }
</style>

<div class="min-h-screen bg-cream flex">
    
    @include('partials.member-sidebar')

    <!-- MAIN CONTENT -->
    <!-- Mobile Sidebar Overlay -->
    <!-- Mobile Sidebar Overlay removed to avoid dark backdrop -->

    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="flex-1 overflow-auto">
        <!-- HEADER SECTION -->
        <div class="bg-white border-b border-light-pink/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-dark">
                            <i class="ri-calendar-check-line text-secondary mr-2"></i>Attendance History
                        </h1>
                        <p class="text-dark/70 mt-2">Monitor your training consistency</p>
                    </div>
                    <a href="{{ route('member.dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-dark hover:bg-dark text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="ri-arrow-left-line"></i> 
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @php
            $user = auth('customer')->user();
            $totalAttendances = $user->attendances()->count();
            $monthAttendances = $user->attendances()->whereDate('created_at', '>=', now()->startOfMonth())->count();
            $weekAttendances = $user->attendances()->whereDate('created_at', '>=', now()->startOfWeek())->count();
            $memberMonths = max(1, now()->diffInMonths($user->created_at) + 1);
            $monthlyAverage = $totalAttendances > 0 ? round($totalAttendances / $memberMonths) : 0;
            $weekStreak = $user->attendances()->whereDate('created_at', '>=', now()->subDays(7))->count();

            // Duration stats
            $totalDurationMinutes = $user->attendances()->whereNotNull('check_out_at')->sum('duration_minutes') ?? 0;
            $avgDurationMinutes = $user->attendances()->whereNotNull('check_out_at')->avg('duration_minutes') ?? 0;
            $totalHours = intdiv((int)$totalDurationMinutes, 60);
            $totalMins = (int)$totalDurationMinutes % 60;
            $avgMins = round($avgDurationMinutes);
            $monthDurationMinutes = $user->attendances()->whereNotNull('check_out_at')->whereDate('created_at', '>=', now()->startOfMonth())->sum('duration_minutes') ?? 0;
            $monthDurHours = intdiv((int)$monthDurationMinutes, 60);
            $monthDurMins = (int)$monthDurationMinutes % 60;
        @endphp

        

        <!-- ATTENDANCE RECORDS -->
        <div class="bg-white rounded-lg border border-light-pink/50 overflow-hidden shadow-sm">
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-light-pink/50 bg-cream flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h2 class="text-lg font-bold text-dark">Attendance Records</h2>
                <div class="w-full sm:w-auto">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search..." 
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-light-pink/60 rounded-lg text-dark placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-colors"
                               id="searchInput">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-dark/40"></i>
                    </div>
                </div>
            </div>

            @if($totalAttendances > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full" id="attendanceTable">
                        <thead>
                            <tr class="bg-cream border-b border-light-pink/50">
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Date</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Program</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Check-In</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Check-Out</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Duration</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Type</th>
                                <th class="text-left px-6 py-3 text-dark/70 font-semibold text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light-pink/40" id="attendanceTableBody">
                            @foreach($user->attendances()->latest()->paginate(20) as $index => $attendance)
                                <tr class="hover:bg-cream transition-colors attendance-row">
                                    <td class="px-6 py-4 text-sm">
                                        <div>
                                            <div class="font-semibold text-dark">{{ $attendance->created_at->format('d M Y') }}</div>
                                            <div class="text-cream0">{{ $attendance->created_at->format('l') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->program)
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-light-pink/30 border border-secondary/30 text-secondary rounded-lg font-medium">
                                                <i class="ri-dumbbell-line"></i>
                                                {{ $attendance->program }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-cream border border-light-pink/50 text-cream0 rounded-lg font-medium">
                                                <i class="ri-dumbbell-line"></i>
                                                General Fitness
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-dark font-medium">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm {{ $attendance->check_out_at ? 'text-accent font-medium' : 'text-secondary' }}">
                                        {{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $duration = $attendance->getFormattedDuration();
                                            $durationMins = $attendance->getDurationInMinutes();
                                            $isActive = $attendance->check_out_at === null;
                                        @endphp
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-light-pink/30 border border-light-pink text-secondary rounded-lg text-xs font-semibold">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-light-pink"></span>
                                                </span>
                                                Sedang Latihan
                                            </span>
                                        @elseif($durationMins !== null)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                                    {{ $durationMins >= 60 ? 'bg-grounded-green/40 text-accent' : ($durationMins >= 30 ? 'bg-light-pink/50 text-primary' : 'bg-cream text-cream0') }}">
                                                    <i class="fas fa-stopwatch text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-dark text-sm">{{ $duration }}</p>
                                                    @if($durationMins >= 60)
                                                        <p class="text-xs text-accent font-medium">Great session!</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-dark/40">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->check_in_type === 'qr')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-light-pink/50 text-secondary rounded text-xs font-medium">
                                                <i class="ri-qr-code-line"></i>QR Scan
                                            </span>
                                        @elseif($attendance->check_in_type === 'manual')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-light-pink/50 text-secondary rounded text-xs font-medium">
                                                <i class="ri-user-line"></i>Manual
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-cream text-dark rounded text-xs font-medium">
                                                {{ ucfirst($attendance->check_in_type ?? 'system') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->attendance_status === 'present')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-grounded-green/40 text-springs-ivy rounded text-xs font-medium">
                                                <i class="ri-checkbox-circle-line"></i>Present
                                            </span>
                                        @elseif($attendance->attendance_status === 'late')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-grounded-green/40 text-springs-ivy rounded text-xs font-medium">
                                                <i class="ri-alarm-warning-line"></i>Late
                                            </span>
                                        @elseif($attendance->attendance_status === 'absent')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-light-pink/50 text-secondary rounded text-xs font-medium">
                                                <i class="ri-close-circle-line"></i>Absent
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-cream text-dark rounded text-xs font-medium">
                                                {{ ucfirst($attendance->attendance_status ?? 'unknown') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-light-pink/40" id="attendanceCards">
                    @foreach($user->attendances()->latest()->paginate(20) as $attendance)
                        <div class="p-4 hover:bg-cream transition-colors attendance-row">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-semibold text-dark">{{ $attendance->created_at->format('d M Y') }}</p>
                                    <p class="text-sm text-cream0">{{ $attendance->created_at->format('l, H:i') }}</p>
                                </div>
                                @if($attendance->attendance_status === 'present')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-grounded-green/40 text-springs-ivy rounded text-xs font-medium">
                                        <i class="ri-checkbox-circle-line"></i>Present
                                    </span>
                                @elseif($attendance->attendance_status === 'late')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-grounded-green/40 text-springs-ivy rounded text-xs font-medium">
                                        <i class="ri-alarm-warning-line"></i>Late
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-light-pink/50 text-secondary rounded text-xs font-medium">
                                        <i class="ri-close-circle-line"></i>Absent
                                    </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-cream0">Program</p>
                                    <p class="font-semibold text-dark">{{ $attendance->program ?? 'General Fitness' }}</p>
                                </div>
                                <div>
                                    <p class="text-cream0">Check-In</p>
                                    <p class="font-semibold text-dark">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-cream0">Check-Out</p>
                                    <p class="font-semibold {{ $attendance->check_out_at ? 'text-accent' : 'text-secondary' }}">
                                        {{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-cream0">Duration</p>
                                    @php
                                        $mobDuration = $attendance->getFormattedDuration();
                                        $mobDurationMins = $attendance->getDurationInMinutes();
                                        $mobIsActive = $attendance->check_out_at === null;
                                    @endphp
                                    @if($mobIsActive)
                                        <p class="font-semibold text-secondary flex items-center gap-1">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-light-pink"></span>
                                            </span>
                                            Sedang Latihan
                                        </p>
                                    @elseif($mobDurationMins !== null)
                                        <p class="font-semibold text-dark">{{ $mobDuration }}</p>
                                    @else
                                        <p class="font-semibold text-dark/40">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5 border-t border-light-pink/50 bg-cream">
                    {{ $user->attendances()->latest()->paginate(20)->links() }}
                </div>
                
            @else
                <!-- Empty State -->
                <div class="py-16 text-center px-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-cream rounded-lg mb-4">
                        <i class="ri-calendar-x-line text-3xl text-dark/40"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-dark mb-2">No Records Yet</h3>
                    <p class="text-cream0 mb-6">Your attendance records will appear here once you check in.</p>
                    <a href="{{ route('member.dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary text-white font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200">
                        <i class="ri-dashboard-line"></i>
                        <span>Go to Dashboard</span>
                    </a>
                </div>
            @endif
        </div>

        

<!-- Scripts -->
<script>
    // Export CSV Function
    function exportCSV() {
        const table = document.getElementById('attendanceTable');
        if (!table) {
            alert('No data to export');
            return;
        }
        
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const csvRow = [];
            cols.forEach(col => {
                let text = col.innerText.replace(/\s+/g, ' ').trim();
                csvRow.push('"' + text.replace(/"/g, '""') + '"');
            });
            if (csvRow.length > 0) {
                csv.push(csvRow.join(','));
            }
        });
        
        const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'attendance-' + new Date().toISOString().split('T')[0] + '.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Print Records Function
    function printRecords() {
        window.print();
    }
    
    // Search Functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.attendance-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        
        #attendanceTable,
        #attendanceTable * {
            visibility: visible;
        }
        
        #attendanceTable {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        button, input, a {
            display: none !important;
        }
    }
</style>

<script>
// ===== SIDEBAR TOGGLE FUNCTION =====
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    if (!sidebar) return;

    const willOpen = !sidebar.classList.contains('active') && !sidebar.classList.contains('open');
    sidebar.classList.toggle('active');
    sidebar.classList.toggle('open');

    if (willOpen) {
        document.body.classList.add('sidebar-open');
        document.body.style.overflow = 'hidden';
        if (hamburger) hamburger.style.display = 'none';
        document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
    } else {
        document.body.classList.remove('sidebar-open');
        document.body.style.overflow = '';
        if (hamburger) { hamburger.style.display = ''; hamburger.innerHTML = '<i class="fas fa-bars"></i>'; }
        document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
    }
}

// Close sidebar when clicking on a nav link
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up sidebar');
    
    const navLinks = document.querySelectorAll('#sidebar nav a');
    console.log('Found nav links:', navLinks.length);
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            }
        });
    });
});

// Reset sidebar on window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('active');
        if (hamburger) hamburger.style.display = '';
        if (hamburger) hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.style.overflow = '';
    }
});
</script>

@endsection