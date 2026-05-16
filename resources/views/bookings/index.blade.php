<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\bookings\index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Booking Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-secondary mb-6 text-center">Daftar Booking Member</h2>

        <!-- Tombol kembali ke data customer -->
        <a href="http://127.0.0.1:8000/adm/customers"
           class="inline-block mb-6 px-4 py-2 bg-light-pink/40 text-dark rounded hover:bg-gray-400 font-semibold text-sm shadow">
            &larr; Kembali ke Data Customer
        </a>

        <!-- Panel atas: Filter, Spreadsheet, Hapus Terpilih -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <!-- Filter tanggal -->
            <form method="GET" action="{{ route('bookings.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ request('date') }}" class="border border-blue-300 rounded px-2 py-1" required>
                <button type="submit" class="px-3 py-1 bg-primary text-white rounded hover:bg-secondary text-sm">Filter</button>
                @if(request('date'))
                    <a href="{{ route('bookings.index') }}" class="ml-2 text-primary text-xs underline">Reset</a>
                @endif
            </form>
            <!-- Tombol Spreadsheet -->
            <a href="https://docs.google.com/spreadsheets/d/ID_SHEET_ANDA" target="_blank"
                class="px-4 py-2 bg-accent text-white rounded hover:bg-springs-ivy font-semibold text-sm shadow flex items-center">
                <svg class="inline w-4 h-4 mr-1 -mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 16h8M8 12h8m-8-4h8M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z"/></svg>
                Lihat di Google Spreadsheet
            </a>
            <!-- Form hapus terpilih -->

        </div>

        <!-- Tabel Booking -->
        <!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\bookings\index.blade.php -->
<div class="overflow-x-auto">
    <form id="bulk-delete-form-table" action="{{ route('bookings.bulkDelete') }}" method="POST">
        @csrf
        @method('DELETE')
        <table class="min-w-full border border-light-pink/60 rounded-lg shadow text-sm bg-light-pink/30">
            <thead class="bg-light-pink/50">
                <tr>
                    <th class="p-3 text-center">
                        <input type="checkbox" id="check-all">
                    </th>
                    <th class="p-3 text-center">Nama Member</th>
                    <th class="p-3 text-center">Membership</th>
                    <th class="p-3 text-center">Program</th>
                    <th class="p-3 text-center">Tanggal</th>
                    <th class="p-3 text-center">Jam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-t hover:bg-light-pink/50">
                        <td class="p-3 text-center">
                            <input type="checkbox" name="ids[]" value="{{ $booking->id }}" class="check-item">
                        </td>
                        <td class="p-3 text-center">{{ $booking->customer->name }}</td>
                        <td class="p-3 text-center">{{ $booking->customer->membership ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $booking->program ?? '-' }}</td>
                        <td class="p-3 text-center">{{ $booking->schedule_date }}</td>
                        <td class="p-3 text-center">{{ $booking->schedule_time }}</td>
                        <td class="p-3 text-center">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-3 text-dark/40">Belum ada booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Tombol hapus terpilih harus di dalam form -->
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded hover:bg-secondary font-semibold text-sm shadow">
                Hapus Terpilih
            </button>
        </div>
    </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 untuk hapus satuan
        document.querySelectorAll('.form-hapus').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin menghapus jadwal ini?',
                    text: "Data yang dihapus tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert2 untuk hapus terpilih (bulk)
        document.querySelectorAll('#bulk-delete-form, #bulk-delete-form-table').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin menghapus semua booking yang dipilih?',
                    text: "Data yang dihapus tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Checkbox master
        document.getElementById('check-all').addEventListener('change', function() {
            document.querySelectorAll('.check-item').forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>