@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex">
    
    <!-- ========================================
         SIDEBAR
    ======================================== -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <a href="{{ route('member.profile') }}" class="px-6 py-5 text-xl font-bold border-b border-white/20 hover:bg-slate-800 transition inline-block w-full">
            FTM SOCIETY
        </a>

        <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
            <a href="{{ route('member.dashboard') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>

            <a href="{{ route('member.packages.index') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-box mr-2"></i>My Packages
            </a>

            <a href="{{ route('member.book') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-calendar-plus mr-2"></i>Book Class
            </a>

            <a href="{{ route('member.my-classes') }}"
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-dumbbell mr-2"></i>My Classes
            </a>

            <a href="{{ route('member.transactions') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-receipt mr-2"></i>Transactions
            </a>

            <a href="{{ route('member.attendance') }}" 
               class="block px-4 py-2 rounded bg-indigo-600 text-white font-medium">
                <i class="fas fa-calendar-check mr-2"></i>Attendance
            </a>

            <a href="{{ route('member.account') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-user mr-2"></i>Profile
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
            &copy; {{ date('Y') }} FTM Society
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 overflow-auto">
        <!-- HEADER SECTION -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="ri-calendar-check-line text-red-600 mr-2"></i>Attendance History
                        </h1>
                        <p class="text-gray-600 mt-2">Monitor your training consistency</p>
                    </div>
                    <a href="{{ route('member.dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition-colors duration-200">
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
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-900">Attendance Records</h2>
                <div class="w-full sm:w-auto">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search..." 
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-colors"
                               id="searchInput">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            @if($totalAttendances > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full" id="attendanceTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Date</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Program</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Check-In</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Check-Out</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Duration</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Type</th>
                                <th class="text-left px-6 py-3 text-gray-600 font-semibold text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="attendanceTableBody">
                            @foreach($user->attendances()->latest()->paginate(20) as $index => $attendance)
                                <tr class="hover:bg-gray-50 transition-colors attendance-row">
                                    <td class="px-6 py-4 text-sm">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $attendance->created_at->format('d M Y') }}</div>
                                            <div class="text-gray-500">{{ $attendance->created_at->format('l') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->program)
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 border border-red-200 text-red-700 rounded-lg font-medium">
                                                <i class="ri-dumbbell-line"></i>
                                                {{ $attendance->program }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 border border-gray-200 text-gray-500 rounded-lg font-medium">
                                                <i class="ri-dumbbell-line"></i>
                                                General Fitness
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm {{ $attendance->check_out_at ? 'text-green-600 font-medium' : 'text-orange-600' }}">
                                        {{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $duration = $attendance->getFormattedDuration();
                                            $durationMins = $attendance->getDurationInMinutes();
                                            $isActive = $attendance->check_out_at === null;
                                        @endphp
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 border border-orange-200 text-orange-600 rounded-lg text-xs font-semibold">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                                </span>
                                                Sedang Latihan
                                            </span>
                                        @elseif($durationMins !== null)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                                    {{ $durationMins >= 60 ? 'bg-emerald-100 text-emerald-600' : ($durationMins >= 30 ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500') }}">
                                                    <i class="fas fa-stopwatch text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">{{ $duration }}</p>
                                                    @if($durationMins >= 60)
                                                        <p class="text-xs text-emerald-600 font-medium">Great session!</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->check_in_type === 'qr')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                                <i class="ri-qr-code-line"></i>QR Scan
                                            </span>
                                        @elseif($attendance->check_in_type === 'manual')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                                <i class="ri-user-line"></i>Manual
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                                {{ ucfirst($attendance->check_in_type ?? 'system') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($attendance->attendance_status === 'present')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                                <i class="ri-checkbox-circle-line"></i>Present
                                            </span>
                                        @elseif($attendance->attendance_status === 'late')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">
                                                <i class="ri-alarm-warning-line"></i>Late
                                            </span>
                                        @elseif($attendance->attendance_status === 'absent')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
                                                <i class="ri-close-circle-line"></i>Absent
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">
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
                <div class="md:hidden divide-y divide-gray-200" id="attendanceCards">
                    @foreach($user->attendances()->latest()->paginate(20) as $attendance)
                        <div class="p-4 hover:bg-gray-50 transition-colors attendance-row">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $attendance->created_at->format('d M Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $attendance->created_at->format('l, H:i') }}</p>
                                </div>
                                @if($attendance->attendance_status === 'present')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                        <i class="ri-checkbox-circle-line"></i>Present
                                    </span>
                                @elseif($attendance->attendance_status === 'late')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">
                                        <i class="ri-alarm-warning-line"></i>Late
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">
                                        <i class="ri-close-circle-line"></i>Absent
                                    </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Program</p>
                                    <p class="font-semibold text-gray-900">{{ $attendance->program ?? 'General Fitness' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Check-In</p>
                                    <p class="font-semibold text-gray-900">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Check-Out</p>
                                    <p class="font-semibold {{ $attendance->check_out_at ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $attendance->check_out_at?->format('H:i') ?? 'Aktif' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Duration</p>
                                    @php
                                        $mobDuration = $attendance->getFormattedDuration();
                                        $mobDurationMins = $attendance->getDurationInMinutes();
                                        $mobIsActive = $attendance->check_out_at === null;
                                    @endphp
                                    @if($mobIsActive)
                                        <p class="font-semibold text-orange-600 flex items-center gap-1">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                            </span>
                                            Sedang Latihan
                                        </p>
                                    @elseif($mobDurationMins !== null)
                                        <p class="font-semibold text-gray-900">{{ $mobDuration }}</p>
                                    @else
                                        <p class="font-semibold text-gray-400">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-5 border-t border-gray-200 bg-gray-50">
                    {{ $user->attendances()->latest()->paginate(20)->links() }}
                </div>
                
            @else
                <!-- Empty State -->
                <div class="py-16 text-center px-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-lg mb-4">
                        <i class="ri-calendar-x-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Records Yet</h3>
                    <p class="text-gray-500 mb-6">Your attendance records will appear here once you check in.</p>
                    <a href="{{ route('member.dashboard') }}" 
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200">
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
@endsection