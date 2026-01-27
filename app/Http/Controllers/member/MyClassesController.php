<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerSchedule;

class MyClassesController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $myClasses = CustomerSchedule::with([
                'schedule.classModel'
            ])
            ->where('customer_id', $customer->id)
            ->where('status', 'confirmed')
            ->orderByRaw("
                FIELD(schedules.day,
                    'Monday','Tuesday','Wednesday',
                    'Thursday','Friday','Saturday','Sunday'
                )
            ")
            ->orderBy('schedules.class_time')
            ->join('schedules', 'schedules.id', '=', 'customer_schedules.schedule_id')
            ->select('customer_schedules.*')
            ->get();

        return view('member.my-classes', compact('myClasses'));
    }
}

