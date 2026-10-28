<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CustomerSchedule;
use App\Models\InstructorRating;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorRatingController extends Controller
{
    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule = Schedule::with('classModel')->findOrFail($data['schedule_id']);

        $isBooked = CustomerSchedule::where('customer_id', $customer->id)
            ->where('schedule_id', $schedule->id)
            ->where('status', 'confirmed')
            ->exists();

        if (! $isBooked) {
            return response()->json([
                'message' => 'Kamu belum terdaftar di kelas ini.',
            ], 403);
        }

        $hasCheckedOut = Attendance::where('customer_id', $customer->id)
            ->where('schedule_id', $schedule->id)
            ->whereNotNull('check_out_at')
            ->exists();

        if (! $hasCheckedOut) {
            return response()->json([
                'message' => 'Kelas belum diselesaikan. Lakukan check-out di halaman Attendance terlebih dahulu.',
            ], 422);
        }

        $rating = InstructorRating::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'schedule_id' => $schedule->id,
            ],
            [
                'instructor_name' => $schedule->instructor,
                'class_name' => $schedule->class_name,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Ulasan terkirim. Terima kasih atas masukanmu.',
            'rating' => $rating->rating,
            'comment' => $rating->comment,
        ]);
    }
}