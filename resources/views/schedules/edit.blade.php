<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule - FTM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">

    <!-- Header -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-secondary">FTM Admin Panel</h1>
            <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary transition">Back to Schedules</a>
        </div>
    </nav>

    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold text-secondary mb-8 text-center">Edit Jadwal Kelas</h1>

        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-auto">
            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Kelas -->
                <div class="mb-4">
                    <label for="class_name" class="block text-sm font-medium text-dark mb-1">Nama Kelas</label>
                    <select name="class_name" id="class_name" required class="block w-full border border-light-pink/60 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-primary/30">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ old('class_name', $schedule->class_name) == $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                    @error('class_name')
                        <p class="text-secondary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hari -->
                <div class="mb-4">
                    <label for="day" class="block text-sm font-medium text-dark mb-1">Hari</label>
                    <select name="day" id="day" required class="block w-full border border-light-pink/60 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-primary/30">
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <option value="{{ $day }}" {{ old('day', $schedule->day) == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day')
                        <p class="text-secondary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam -->
                <div class="mb-4">
                    <label for="class_time" class="block text-sm font-medium text-dark mb-1">Jam</label>
                    <input type="time" name="class_time" id="class_time" value="{{ old('class_time', $schedule->class_time) }}" required class="block w-full border border-light-pink/60 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-primary/30">
                    @error('class_time')
                        <p class="text-secondary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instruktur -->
                <div class="mb-4">
                    <label for="instructor" class="block text-sm font-medium text-dark mb-1">Instruktur</label>
                    <input type="text" name="instructor" id="instructor" value="{{ old('instructor', $schedule->instructor) }}" required class="block w-full border border-light-pink/60 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-primary/30">
                    @error('instructor')
                        <p class="text-secondary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tampilkan di Landing Page -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-dark mb-1">Tampilkan di Landing Page</label>
                    <input type="hidden" name="show_on_landing" value="0">
                    <label class="inline-flex items-center space-x-2">
                        <input 
                            type="checkbox" 
                            name="show_on_landing" 
                            value="1" 
                            {{ old('show_on_landing', $schedule->show_on_landing) ? 'checked' : '' }}
                            class="form-checkbox text-primary rounded focus:ring focus:ring-primary/30"
                        >
                        <span class="text-sm text-dark">Tampilkan di Halaman Utama</span>
                    </label>
                </div>

                <!-- Tombol -->
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-secondary transition font-semibold w-full">
                    Simpan Perubahan
                </button>
                <a href="{{ route('schedules.index') }}" class="block text-center mt-4 text-primary hover:underline">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
