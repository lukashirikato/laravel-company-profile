<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\feedback.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTM - Customer Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <!-- Header Menu -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-secondary">FTM Admin Panel</h1>
            <div class="flex gap-4">
                <a href="{{ route('admin.home') }}" class="px-4 py-2 bg-light-pink/300 text-white rounded hover:bg-primary transition">Home</a>
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-grounded-green/200 text-white rounded hover:bg-accent transition">Customers</a>
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary transition">Schedules</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold text-secondary mb-8 text-center">Customer Feedback</h1>
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
            @if($feedbacks->isEmpty())
                <p class="text-cream0 text-center">No feedback available.</p>
            @else
                <ul class="divide-y divide-light-pink/40">
                    @foreach($feedbacks as $feedback)
                        <li class="py-4">
                            <div class="flex items-center mb-2">
                                <div class="bg-light-pink text-secondary rounded-full w-10 h-10 flex items-center justify-center font-bold mr-3">
                                    {{ strtoupper(substr($feedback->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-secondary">{{ $feedback->name }}</p>
                                    <p class="text-xs text-cream0">{{ $feedback->email }}</p>
                                    <p class="text-xs text-dark/40 italic">{{ $feedback->subject }}</p>
                                </div>
                            </div>
                            <p class="text-dark italic pl-12">"{{ $feedback->message }}"</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>