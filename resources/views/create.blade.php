<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-secondary mb-6 text-center">Add Customer</h2>
        @if ($errors->any())
            <div class="mb-4 text-secondary">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-dark mb-2" for="name">Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="Customer Name">
            </div>
            <div class="mb-4">
                <label class="block text-dark mb-2" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="customer@email.com">
            </div>
            <div class="mb-6">
                <label class="block text-dark mb-2" for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="08xxxxxxxxxx">
            </div>
            <div class="flex justify-between">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-light-pink/40 text-dark rounded hover:bg-gray-400 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary transition font-semibold">
                    Save
                </button>
            </div>
        </form>
    </div>
</body>
</html>