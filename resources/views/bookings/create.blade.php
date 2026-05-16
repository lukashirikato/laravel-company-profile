<!-- filepath: create.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Latihan Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center">
    <div class="max-w-4xl w-full bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-secondary mb-6 text-center">
    @if(isset($customer))
        Booking Latihan untuk <span class="text-blue-900">{{ $customer->name }}</span>
    @else
        Booking Latihan Umum
    @endif
</h2>

        @if(session('error'))
            <div class="mb-4 text-secondary text-center font-semibold">{{ session('error') }}</div>
        @endif

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            @if(isset($customer))
    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
@endif

            <div class="overflow-x-auto">
                <table class="min-w-full border border-primary/30 rounded-lg text-sm">
                    <thead class="bg-light-pink/50">
                        <tr>
                            <th class="p-2 text-center">Visit</th>
                            <th class="p-2 text-center">Tanggal Latihan</th>
                            <th class="p-2 text-center">Program/Kelas</th>
                            <th class="p-2 text-center">Jam Latihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < 8; $i++)
                        <tr>
                            <td class="p-2 text-center font-semibold text-secondary">Visit {{ $i+1 }}</td>
                            <td class="p-2">
                                <input type="date" name="schedule_date[]" id="schedule_date_{{ $i }}" class="w-full border border-blue-300 rounded px-2 py-1">
                            </td>
                            <td class="p-2">
                                <select name="schedule_program[]" id="schedule_program_{{ $i }}" class="w-full border border-blue-300 rounded px-2 py-1">
                                    <option value="">Pilih Program</option>
                                    <option value="Muaythai">Muaythai</option>
                                    <option value="Body Shaping">Body Shaping</option>
                                    <option value="Mat Pilates">Mat Pilates</option>
                                    <option value="Reformer Pilates">Reformer Pilates</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="time" name="schedule_time[]" id="schedule_time_{{ $i }}" class="w-full border border-blue-300 rounded px-2 py-1">
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <button type="submit" class="w-full mt-6 py-2 bg-primary text-white font-semibold rounded hover:bg-secondary transition">Booking</button>

        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('customers.index') }}" class="text-primary hover:underline text-sm">Kembali ke Daftar Member</a>
        </div>
    </div>
</body>
</html>
