<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function checkIn(Request $request)
    {
        CheckIn::create([
            'customer_id' => Auth::id(),
            'program' => $request->program,
            'check_in' => now(),
        ]);

        return back()->with('success', 'Berhasil Check-In');
    }

    public function checkOut()
    {
        $checkin = CheckIn::where('customer_id', Auth::id())
            ->whereNull('check_out')
            ->latest()
            ->first();

        if (!$checkin) {
            return back()->with('error', 'Tidak ada sesi aktif!');
        }

        $checkin->update([
            'check_out' => now(),
        ]);

        return back()->with('success', 'Berhasil Check-Out');
    }
}
