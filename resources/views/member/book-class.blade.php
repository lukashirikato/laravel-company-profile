<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Class</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto p-8">

    <h1 class="text-2xl font-bold mb-2">Book Class</h1>

    <p class="text-sm text-gray-500 mb-4">
        Remaining quota: <b>{{ $customer->quota }}</b>
    </p>

    {{-- FLASH MESSAGE (WAJIB DI SINI) --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 text-red-800 p-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- TIDAK ADA JADWAL --}}
    @if($schedules->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Tidak ada jadwal untuk paket kamu.
        </div>
    @endif

    {{-- JADWAL --}}
    @foreach($schedules as $day => $items)
        <div class="mb-10">
            <h2 class="font-semibold text-lg mb-3">{{ $day }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($items as $s)
                    <div class="bg-white p-4 rounded-lg shadow-sm">

                        <p class="font-semibold">
                            {{ $s->classModel->class_name ?? 'Class' }}
                        </p>

                        <p class="text-lg font-medium">
                            {{ \Carbon\Carbon::parse($s->class_time)->format('H:i') }}
                        </p>

                        <p class="text-sm text-gray-500">
                            Coach {{ $s->instructor ?? '-' }}
                        </p>

                        {{-- STATUS --}}
                        @if(in_array($s->id, $bookedScheduleIds))
                            <button disabled
                                class="mt-4 w-full bg-green-500 text-white py-2 rounded cursor-not-allowed">
                                ✓ Booked
                            </button>

                        @elseif($customer->quota <= 0)
                            <button disabled
                                class="mt-4 w-full bg-gray-400 text-white py-2 rounded cursor-not-allowed">
                                Quota Habis
                            </button>

                        @else
                            <form method="POST" action="{{ route('member.book.store') }}">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $s->id }}">

                                <button
                                    class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700
                                           text-white py-2 rounded">
                                    Book
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</div>
</body>
</html>
