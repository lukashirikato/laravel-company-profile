<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cream min-h-screen flex items-center justify-center px-4">
    <div class="bg-white p-6 rounded shadow max-w-4xl w-full">
        <h2 class="text-2xl font-bold mb-4 text-secondary">Tambah Customer</h2>

        @if ($errors->any())
            <div class="mb-4 text-secondary">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Informasi Member -->
            <input type="text" name="name" placeholder="Nama" class="w-full border px-3 py-2 rounded" value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" class="w-full border px-3 py-2 rounded" value="{{ old('email') }}">
            <input type="text" name="phone_number" placeholder="No HP" class="w-full border px-3 py-2 rounded" value="{{ old('phone_number') }}">
            <input type="number" name="credit" min="0" placeholder="Credit" class="w-full border px-3 py-2 rounded" value="{{ old('credit') }}">

            <!-- Membership -->
            <select name="membership" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Pilih Membership --</option>
                <option value="Exclusive Class Program">Exclusive Class Program</option>
                <option value="Single Visit Class">Single Visit Class</option>
            </select>

            <!-- Preferred Membership -->
            <select name="preferred_membership" class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Preferred --</option>
                <option value="Not sure">Not sure</option>
            </select>

<!-- Jadwal Latihan -->
<div class="mt-6">
    <h3 class="text-lg font-semibold text-primary mb-4">Jadwal Latihan Member</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-primary/30 text-sm">
            <thead class="bg-light-pink/50">
                <tr>
                    <th class="p-2 text-center">Visit</th>
                    <th class="p-2 text-center">Tanggal</th>
                    <th class="p-2 text-center">Kelas/Program</th>
                    <th class="p-2 text-center">Jam</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 8; $i++)
                    <tr>
                        <td class="p-2 text-center font-semibold text-secondary">Visit {{ $i + 1 }}</td>
                        <td class="p-2">
                            <input type="date" name="schedule_date[]" class="w-full border border-blue-300 rounded px-2 py-1">
                        </td>
                        <td class="p-2">
                            <input type="text" name="schedule_program[]" placeholder="Contoh: Muaythai, Pilates" class="w-full border border-blue-300 rounded px-2 py-1">
                        </td>
                        <td class="p-2">
                            <input type="time" name="schedule_time[]" class="w-full border border-blue-300 rounded px-2 py-1">
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<!-- Tombol Aksi -->
<div class="flex justify-between pt-4">
    <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-light-pink/40 rounded hover:bg-gray-400">Cancel</a>
    <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary">Simpan</button>
</div>

</body>
</html>
