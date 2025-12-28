<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Class</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    @if($schedules->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Tidak ada jadwal untuk paket kamu.
    </div>
@endif


<div class="max-w-6xl mx-auto p-8">
    <h1 class="text-2xl font-bold mb-2">Book Class</h1>
    <p class="text-sm text-gray-500 mb-6">
        Remaining quota: <b>{{ $customer->quota }}</b>
    </p>

    @foreach($schedules as $day => $items)
        <div class="mb-8">
            <h2 class="font-semibold text-lg mb-3">{{ $day }}</h2>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($items as $s)
                    <form method="POST" action="{{ route('member.book.store') }}"
                          class="bg-white p-4 rounded-lg shadow-sm">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $s->id }}">

                        <p class="font-medium">
                            {{ substr($s->class_time, 0, 5) }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Coach {{ $s->instructor }}
                        </p>

                        <button
                            class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700
                                   text-white py-2 rounded">
                            Book
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
