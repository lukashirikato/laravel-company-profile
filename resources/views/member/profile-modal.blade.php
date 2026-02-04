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
        <div class="px-6 py-5 text-xl font-bold border-b border-white/20">
            FTM SOCIETY
        </div>

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

            <!-- Profile -->
            <a href="{{ route('member.profile') }}" 
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
            <!-- Membership Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 font-medium">Membership</p>
                    <i class="fas fa-id-card text-indigo-500 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $customer->membership ?? 'Active Member' }}
                </p>
            </div>

            <!-- Remaining Quota Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 font-medium">Remaining Quota</p>
                    <i class="fas fa-ticket-alt text-purple-500 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $customer->quota }}
                </p>
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

        <!-- PROFILE + ATTENDANCE -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <!-- PROFILE CARD -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <!-- Avatar -->
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-3xl font-bold text-indigo-600 shadow-lg">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <!-- Name & Email -->
                <h2 class="mt-4 font-bold text-lg text-gray-800">
                    {{ $customer->name }}
                </h2>
                <p class="text-sm text-gray-500 mb-4">
                    {{ $customer->email }}
                </p>

                <!-- Phone Info -->
                <div class="bg-gray-50 rounded-lg p-3 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            Phone
                        </span>
                        <span class="text-gray-800 font-medium">
                            {{ $customer->phone_number }}
                        </span>
                    </div>
                </div>

                <!-- Check-In/Out Buttons -->
                <div class="space-y-3">
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-qrcode"></i>
                        Scan Check-In
                    </button>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        Scan Check-Out
                    </button>
                </div>
            </div>

            <!-- ATTENDANCE HISTORY -->
            <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
                <h3 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-600"></i>
                    Attendance History
                </h3>

                <div class="divide-y divide-gray-100">
                    @forelse($attendances as $a)
                        <div class="py-4 flex justify-between items-center hover:bg-gray-50 px-2 rounded transition">
                            <div class="flex items-center gap-3">
                                @if($a->status == 'check-in')
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-sign-in-alt text-green-600"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-sign-out-alt text-blue-600"></i>
                                    </div>
                                @endif
                                <span class="capitalize text-gray-800 font-semibold">
                                    {{ $a->status }}
                                </span>
                            </div>
                            <span class="text-gray-500 text-sm">
                                {{ $a->created_at->format('d M Y H:i') }}
                            </span>
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
                                Your check-in history will appear here
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </main>
</div>

</body>
</html>