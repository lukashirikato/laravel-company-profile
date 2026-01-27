<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>

    @vite('resources/css/app.css')

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <div class="px-6 py-5 text-xl font-bold border-b border-white/20">
            FTM SOCIETY
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
            <a class="block px-4 py-2 rounded bg-indigo-600 text-white font-medium">
                Dashboard
            </a>
            <a href="{{ route('member.book') }}"
   class="block px-4 py-2 rounded hover:bg-white/10">
    Book Class
</a>
         <a href="{{ route('member.my-classes') }}"
         class="block px-4 py-2 rounded hover:bg-white/10">My Classes</a>

            <a class="block px-4 py-2 rounded hover:bg-white/10">
                Transactions
            </a>
            <a class="block px-4 py-2 rounded hover:bg-white/10">
                Attendance
            </a>
            <a class="block px-4 py-2 rounded hover:bg-white/10">
                Profile
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
            © {{ date('Y') }} FTM Society
        </div>
    </aside>

    <!-- MAIN -->
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

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <p class="text-sm text-gray-500">Membership</p>
                <p class="text-xl font-semibold mt-2">
                    {{ $customer->membership ?? 'Active Member' }}
                </p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <p class="text-sm text-gray-500">Remaining Quota</p>
                <p class="text-xl font-semibold mt-2">
                    {{ $customer->quota }}
                </p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-xl font-semibold mt-2 text-green-600">
                    Active
                </p>
            </div>
        </div>

        <!-- PROFILE + TRANSACTIONS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <!-- PROFILE -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-indigo-100
            flex items-center justify-center
            text-3xl font-bold text-indigo-600">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>

                <h2 class="mt-4 font-semibold text-gray-800">
                    {{ $customer->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $customer->email }}
                </p>

                <div class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="text-gray-800">
                            {{ $customer->phone_number }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <button class="w-full bg-green-600 hover:bg-green-700
                                   text-white py-2 rounded transition">
                        Scan Check-In
                    </button>
                    <button class="w-full bg-blue-600 hover:bg-blue-700
                                   text-white py-2 rounded transition">
                        Scan Check-Out
                    </button>
                </div>
            </div>

            <!-- TRANSACTIONS -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold mb-4 text-gray-800">
                    Transaction History
                </h3>

                <div class="divide-y">
                    @forelse($transactions as $t)
                        <div class="py-4 flex justify-between">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $t->package_name ?? 'Package' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $t->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <span class="font-semibold text-emerald-600">
                                Rp {{ number_format($t->amount) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 py-4">
                            No transactions found.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ATTENDANCE -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold mb-4 text-gray-800">
                Attendance History
            </h3>

            <div class="divide-y text-sm">
                @forelse($attendances as $a)
                    <div class="py-3 flex justify-between">
                        <span class="capitalize text-gray-800">
                            {{ $a->status }}
                        </span>
                        <span class="text-gray-500">
                            {{ $a->created_at->format('d M Y H:i') }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 py-4">
                        No attendance yet.
                    </p>
                @endforelse
            </div>
        </div>

    </main>
</div>

</body>
</html>
