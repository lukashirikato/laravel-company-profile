<?php

namespace App\Http\Controllers;

use App\Models\PackageSchedule;
use App\Models\CustomerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberBookingController extends Controller
{
    /**
     * =========================
     * HALAMAN BOOK CLASS
     * =========================
     */
    public function index()
    {
        /** @var \App\Models\Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        // Guard safety
        if (!$customer || !$customer->package_id) {
            return view('member.book-class', [
                'customer'  => $customer,
                'schedules' => collect(),
            ]);
        }

        // Ambil schedule yang TERKAIT dengan package customer
        $packageSchedules = PackageSchedule::with([
                'schedule.classModel'
            ])
            ->where('package_id', $customer->package_id)
            ->get();

        if ($packageSchedules->isEmpty()) {
            return view('member.book-class', [
                'customer'  => $customer,
                'schedules' => collect(),
            ]);
        }

        // Normalisasi data schedule
        $schedules = $packageSchedules
            ->pluck('schedule')
            ->filter() // buang null
            ->sortBy(fn ($s) => $s->day . ' ' . $s->class_time)
            ->groupBy('day');

        return view('member.book-class', compact('customer', 'schedules'));
    }

    /**
     * =========================
     * PROSES BOOKING CLASS
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
        ]);

        /** @var \App\Models\Customer $customer */
        $customer = Auth::guard('customer')->user();

        // Safety check
        if (!$customer) {
            return back()->with('error', 'Silakan login ulang');
        }

        // Quota habis
        if ($customer->quota <= 0) {
            return back()->with('error', 'Quota kamu sudah habis');
        }

        // Cegah double booking
        $alreadyBooked = CustomerSchedule::where('customer_id', $customer->id)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Kamu sudah booking kelas ini');
        }

        /**
         * =========================
         * TRANSACTION (WAJIB)
         * =========================
         * Supaya:
         * - Booking & quota selalu konsisten
         * - Tidak race condition
         */
        DB::transaction(function () use ($customer, $request) {

            CustomerSchedule::create([
                'customer_id' => $customer->id,
                'schedule_id' => $request->schedule_id,
                'status' => 'confirmed',
            ]);

            // Kurangi quota
            $customer->update([
                'quota' => $customer->quota - 1,
            ]);
        });

        

        return back()->with('success', 'Class berhasil dibooking');
    }
}
