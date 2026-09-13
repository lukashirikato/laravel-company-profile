<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class CheckInCheckOutController extends Controller
{
    public function checkIn(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Cek apakah user sudah check-in tapi belum check-out
        $active = Attendance::where('customer_id', $customer->id)
            ->whereNull('check_out_at')
            ->first();

        if ($active) {
            return back()->with('error', 'Anda masih dalam sesi latihan.');
        }

        // Cegah check-in ganda di hari yang sama
        $alreadyToday = Attendance::where('customer_id', $customer->id)
            ->whereDate('check_in_at', now()->toDateString())
            ->first();

        if ($alreadyToday) {
            return back()->with('error', 'Anda sudah check-in hari ini.');
        }

        Attendance::create([
            'customer_id' => $customer->id,
            'program' => $customer->program, // atau pilihan program jika ada dropdown
            'check_in_at' => now(),
        ]);

        return back()->with('success', 'Check-In berhasil!');
    }

    public function checkOut()
    {
        $customer = Auth::guard('customer')->user();

        $active = Attendance::where('customer_id', $customer->id)
            ->whereNull('check_out_at')
            ->first();

        if (!$active) {
            return back()->with('error', 'Anda belum check-in.');
        }

        $active->update([
            'check_out_at' => now(),
        ]);

        return back()->with('success', 'Check-Out berhasil!');
    }
}
