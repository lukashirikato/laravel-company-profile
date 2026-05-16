<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTM Administration - Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <!-- Header Menu -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-secondary">FTM Admin Panel</h1>
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary transition">Home</a>
                <a href="{{ route('feedback.index') }}" class="px-4 py-2 bg-grounded-green/200 text-white rounded hover:bg-accent transition">Feedback</a>
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary transition">Schedules</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-secondary">Customers Data</h2>
                <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-accent text-white rounded hover:bg-springs-ivy transition text-sm shadow">+ Add Customer</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-light-pink/40">
                    <thead class="bg-light-pink">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-bold text-secondary uppercase tracking-wider">Name</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-secondary uppercase tracking-wider">Email</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-secondary uppercase tracking-wider">Phone Number</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-secondary uppercase tracking-wider">Created At</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-secondary uppercase tracking-wider">Updated At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-light-pink/30 transition">
                                <td class="py-3 px-6">{{ $customer->name }}</td>
                                <td class="py-3 px-6">{{ $customer->email }}</td>
                                <td class="py-3 px-6">{{ $customer->phone_number }}</td>
                                <td class="py-3 px-6">{{ $customer->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-6">{{ $customer->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-6 text-center text-cream0">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="mt-10 text-center text-cream0 text-sm">
        &copy; {{ date('Y') }} Gym Admin. All rights reserved.
    </footer>
</body>
</html>