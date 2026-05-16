<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\schedule.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Jadwal Kelas | FTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen" x-data="{ showDelete: false, deleteId: null }">

    <!-- NAVIGATION -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-secondary">FTM Admin Panel</h1>
            <div class="flex gap-3">
                <a href="{{ route('admin.home') }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-secondary transition">Home</a>
                <a href="{{ route('customers.index') }}" class="bg-light-pink/300 text-white px-4 py-2 rounded hover:bg-primary transition">Customers</a>
                <a href="{{ route('feedback.index') }}" class="bg-grounded-green/200 text-white px-4 py-2 rounded hover:bg-accent transition">Feedback</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-blue-800 text-center mb-8">Kelola Jadwal Kelas</h2>

        <!-- ALERT -->
        @if(session('success'))
            <div id="success-alert" class="bg-grounded-green/40 border border-green-300 text-springs-ivy px-4 py-3 rounded mb-6 text-center shadow">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('success-alert')?.remove();
                }, 3000);
            </script>
        @endif

        <!-- FORM TAMBAH JADWAL -->
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-xl mx-auto mb-10">
            <h3 class="text-xl font-semibold text-secondary mb-4">Tambah Jadwal Baru</h3>
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="class_name" class="block text-sm font-medium text-dark mb-1">Nama Kelas</label>
                    <input type="text" id="class_name" name="class_name" placeholder="Contoh: Reformer Pilates" value="{{ old('class_name') }}"
                        class="w-full border border-light-pink/60 rounded p-2 focus:ring-2 focus:ring-primary/40">
                    @error('class_name') <p class="text-secondary text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="day" class="block text-sm font-medium text-dark mb-1">Hari</label>
                    <input type="text" id="day" name="day" placeholder="Contoh: Senin & Rabu" value="{{ old('day') }}"
                        class="w-full border border-light-pink/60 rounded p-2 focus:ring-2 focus:ring-primary/40">
                    @error('day') <p class="text-secondary text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="class_time" class="block text-sm font-medium text-dark mb-1">Jam</label>
                    <input type="text" id="class_time" name="class_time" placeholder="Contoh: 10.00 - 11.00" value="{{ old('class_time') }}"
                        class="w-full border border-light-pink/60 rounded p-2 focus:ring-2 focus:ring-primary/40">
                    @error('class_time') <p class="text-secondary text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="instructor" class="block text-sm font-medium text-dark mb-1">Instruktur</label>
                    <input type="text" id="instructor" name="instructor" placeholder="Contoh: Bu Siti" value="{{ old('instructor') }}"
                        class="w-full border border-light-pink/60 rounded p-2 focus:ring-2 focus:ring-primary/40">
                    @error('instructor') <p class="text-secondary text-sm">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center space-x-2">
                    <input type="hidden" name="show_on_landing" value="0">
                    <input type="checkbox" name="show_on_landing" id="show_on_landing" value="1">
                    <label for="show_on_landing">Tampilkan di Landing Page</label>
                </div>
                <button type="submit" class="bg-secondary text-white w-full py-2 rounded hover:bg-blue-800 font-semibold transition">Simpan Jadwal</button>
            </form>
        </div>

        <!-- TABEL JADWAL -->
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-5xl mx-auto">
            <h3 class="text-xl font-semibold text-secondary mb-4">Daftar Jadwal</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-light-pink/60 text-sm">
                    <thead class="bg-light-pink/50">
                        <tr>
                            <th class="px-4 py-2 border text-left">Kelas</th>
                            <th class="px-4 py-2 border text-left">Hari</th>
                            <th class="px-4 py-2 border text-left">Jam</th>
                            <th class="px-4 py-2 border text-left">Instruktur</th>
                            <th class="px-4 py-2 border text-center">Landing?</th>
                            <th class="px-4 py-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-light-pink/30">
                                <td class="px-4 py-2 border">{{ $schedule->class_name }}</td>
                                <td class="px-4 py-2 border">{{ $schedule->day }}</td>
                                <td class="px-4 py-2 border">{{ $schedule->class_time }}</td>
                                <td class="px-4 py-2 border">{{ $schedule->instructor }}</td>
                                <td class="px-4 py-2 border text-center">
                                    {!! $schedule->show_on_landing ? '<span class="text-accent">✅</span>' : '<span class="text-dark/40">❌</span>' !!}
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="bg-yellow-400 hover:bg-grounded-green/200 text-white px-3 py-1 rounded transition">Edit</a>
                                        <button type="button"
                                            @click="showDelete = true; deleteId = {{ $schedule->id }}"
                                            class="bg-light-pink/300 hover:bg-secondary text-white px-3 py-1 rounded transition">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-cream0 py-4">Belum ada jadwal ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL KONFIRMASI HAPUS -->
        <div x-show="showDelete"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            style="display: none;"
            @keydown.escape.window="showDelete = false">
            <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-7 flex flex-col items-center">
                <div class="bg-light-pink/50 rounded-full p-3 mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-secondary mb-2 text-center">Konfirmasi Hapus Jadwal</h3>
                <p class="mb-6 text-dark text-center">
                    Anda yakin ingin menghapus jadwal ini?<br>
                    <span class="text-xs text-cream0">Tindakan ini tidak dapat dibatalkan.</span>
                </p>
                <div class="flex flex-col gap-3 w-full mt-2">
                    <button @click="showDelete = false"
                        class="w-full px-4 py-2 rounded-lg bg-light-pink/30 text-dark font-semibold text-base hover:bg-light-pink/40 transition">
                        Batal
                    </button>
                    <form :action="'{{ route('schedules.destroy', '') }}/' + deleteId" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 rounded-lg bg-secondary text-white font-semibold text-base hover:bg-secondary transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>