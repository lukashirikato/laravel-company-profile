<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Customer;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman landing dengan hanya jadwal yang ditandai tampil.
     */
    public function landing()
    {
        $schedules = Schedule::where('show_on_landing', 1)
                             ->orderBy('day')
                             ->get();

        $customers = Customer::all();

        return view('welcome', compact('schedules', 'customers'));
    }

    /**
     * Menampilkan semua jadwal di dashboard admin.
     */
    public function index()
    {
        $schedules = Schedule::orderBy('day')->get();
        return view('schedule', compact('schedules'));
    }

    /**
     * Menyimpan jadwal baru dari form admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'day'        => 'required|string|max:20',
            'class_time' => 'required|string|max:20',
            'instructor' => 'nullable|string|max:255',
        ]);

        Schedule::create([
            'class_name'      => $request->class_name,
            'day'             => $request->day,
            'class_time'      => $request->class_time,
            'instructor'      => $request->instructor,
            'show_on_landing' => $request->input('show_on_landing', 0),
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit jadwal.
     */
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $classes = ['Muaythai', 'Body Shaping', 'Mat Pilates', 'Reformer Pilates'];

        return view('schedules.edit', compact('schedule', 'classes'));
    }

    /**
     * Memperbarui data jadwal.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'day'        => 'required|string|max:20',
            'class_time' => 'required|string|max:20',
            'instructor' => 'required|string|max:255',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'class_name'      => $request->class_name,
            'day'             => $request->day,
            'class_time'      => $request->class_time,
            'instructor'      => $request->instructor,
            'show_on_landing' => $request->input('show_on_landing', 0),
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Menghapus jadwal.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Ambil jadwal berdasarkan nama kelas (untuk AJAX)
     */
    public function getByClassName($name)
{
    // Trim dan decode nama kelas
    $name = trim(urldecode($name));

    $schedules = Schedule::where('class_name', $name)
                         ->orderBy('day')
                         ->get();

    // Jika tidak ada jadwal, kembalikan array kosong
    if ($schedules->isEmpty()) {
        return response()->json([]);
    }

    // Mapping hasil
    $result = $schedules->map(function ($sch) {
        return [
            'id'         => $sch->id,
            'day'        => $sch->day,
            'class_time' => $sch->class_time,
            'instructor' => $sch->instructor, 
        ];
    });

    return response()->json($result);
}
}