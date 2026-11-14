<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CustomerSchedule;
use App\Models\InstructorRating;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $instructor = Auth::guard('trainer')->user()?->instructorProfile;

        if (! $instructor) {
            return view('trainer.no-profile');
        }

        $today = Carbon::today();

        $baseQuery = $instructor->schedules()
            ->with(['classModel', 'packages'])
            ->withCount(['customerSchedules as booked_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        $upcoming = (clone $baseQuery)
            ->whereDate('schedule_date', '>=', $today)
            ->orderBy('schedule_date')
            ->orderBy('class_time')
            ->get();

        $todaySchedules = $upcoming->filter(
            fn (Schedule $schedule) => $schedule->schedule_date?->isSameDay($today)
        )->values();

        $weekEnd = $today->copy()->addDays(6);

        $weekSchedules = $upcoming->filter(function (Schedule $schedule) use ($today, $weekEnd) {
            $date = $schedule->schedule_date;

            return $date !== null && $date->betweenIncluded($today, $weekEnd);
        });

        $history = (clone $baseQuery)
            ->whereDate('schedule_date', '<', $today)
            ->orderByDesc('schedule_date')
            ->orderByDesc('class_time')
            ->limit(15)
            ->get();

        $ratingSummary = $this->getInstructorRatingsQuery($instructor)
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        $ratingDistribution = $this->ratingDistribution($this->getInstructorRatingsQuery($instructor));

        $latestReviews = $this->getInstructorRatingsQuery($instructor)
            ->with(['customer', 'schedule.classModel'])
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $stats = [
            'today' => $todaySchedules->count(),
            'week' => $weekSchedules->count(),
            'upcoming' => $upcoming->count(),
            'completed' => $this->completedSchedulesQuery($instructor)->count(),
        ];

        return view('trainer.dashboard', compact(
            'instructor',
            'todaySchedules',
            'upcoming',
            'weekSchedules',
            'history',
            'stats',
            'today',
            'ratingSummary',
            'ratingDistribution',
            'latestReviews'
        ));
    }

    public function performance(Request $request)
    {
        $instructor = Auth::guard('trainer')->user()?->instructorProfile;

        if (! $instructor) {
            return view('trainer.no-profile');
        }

        $period = (string) $request->query('period', 'all');
        $days = match ($period) {
            '30' => 30,
            '90' => 90,
            '365' => 365,
            default => null,
        };
        $period = $days === null ? 'all' : (string) $days;
        $since = $days ? now()->subDays($days)->startOfDay() : null;

        $ratingsQuery = $this->getInstructorRatingsQuery($instructor)
            ->when($since, fn ($query) => $query->where('created_at', '>=', $since));

        $ratingSummary = (clone $ratingsQuery)
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        $ratingDistribution = $this->ratingDistribution($ratingsQuery);

        $completedQuery = $this->completedSchedulesQuery($instructor, $since);

        $completedScheduleIds = (clone $completedQuery)->pluck('id');

        $bookedTotal = CustomerSchedule::query()
            ->whereIn('schedule_id', $completedScheduleIds)
            ->where('status', 'confirmed')
            ->count();

        $attendanceBySchedule = Attendance::query()
            ->whereIn('schedule_id', $completedScheduleIds)
            ->whereNotNull('check_in_at')
            ->selectRaw('schedule_id, COUNT(DISTINCT customer_id) as attended')
            ->groupBy('schedule_id')
            ->pluck('attended', 'schedule_id');

        $checkedOutBySchedule = Attendance::query()
            ->whereIn('schedule_id', $completedScheduleIds)
            ->whereNotNull('check_out_at')
            ->selectRaw('schedule_id, COUNT(DISTINCT customer_id) as checked_out')
            ->groupBy('schedule_id')
            ->pluck('checked_out', 'schedule_id');

        $attendedTotal = (int) $attendanceBySchedule->sum();
        $checkedOutTotal = (int) $checkedOutBySchedule->sum();
        $uniqueMembers = (int) Attendance::query()
            ->whereIn('schedule_id', $completedScheduleIds)
            ->whereNotNull('check_in_at')
            ->distinct()
            ->count('customer_id');
        $capacityTotal = (int) (clone $completedQuery)->sum('capacity');
        $sessionsCompleted = (clone $completedQuery)->count();

        $attendanceRate = $bookedTotal > 0 ? min(100, $attendedTotal / $bookedTotal * 100) : 0;
        $fillRate = $capacityTotal > 0 ? min(100, $bookedTotal / $capacityTotal * 100) : 0;
        $reviewRate = $attendedTotal > 0 ? min(100, ((int) ($ratingSummary->total_reviews ?? 0)) / $attendedTotal * 100) : 0;

        $completedSchedules = (clone $completedQuery)
            ->with('classModel')
            ->withCount(['customerSchedules as booked_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('schedule_date')
            ->orderByDesc('class_time')
            ->get();

        $classPerformance = $completedSchedules
            ->groupBy(fn (Schedule $schedule) => $schedule->schedule_label ?: ($schedule->classModel?->class_name ?: 'Kelas'))
            ->map(function ($sessions, $name) use ($attendanceBySchedule) {
                $booked = (int) $sessions->sum('booked_count');
                $attended = (int) $sessions->sum(fn (Schedule $schedule) => (int) ($attendanceBySchedule[$schedule->id] ?? 0));
                $reviews = (int) $sessions->sum('ratings_count');
                $ratingWeighted = $sessions->sum(fn (Schedule $schedule) => (float) ($schedule->ratings_avg_rating ?? 0) * (int) $schedule->ratings_count);

                return (object) [
                    'name' => $name,
                    'sessions' => $sessions->count(),
                    'booked' => $booked,
                    'attended' => $attended,
                    'attendance_rate' => $booked > 0 ? min(100, $attended / $booked * 100) : 0,
                    'reviews' => $reviews,
                    'rating' => $reviews > 0 ? $ratingWeighted / $reviews : null,
                ];
            })
            ->sortByDesc('sessions')
            ->values();

        $recentSessions = $completedSchedules->take(10)->map(function (Schedule $schedule) use ($attendanceBySchedule) {
            $booked = (int) ($schedule->booked_count ?? 0);
            $attended = (int) ($attendanceBySchedule[$schedule->id] ?? 0);

            return (object) [
                'schedule' => $schedule,
                'booked' => $booked,
                'attended' => $attended,
                'attendance_rate' => $booked > 0 ? min(100, $attended / $booked * 100) : 0,
                'rating' => $schedule->ratings_avg_rating,
                'reviews' => (int) ($schedule->ratings_count ?? 0),
            ];
        });

        $reviews = (clone $ratingsQuery)
            ->with(['customer', 'schedule.classModel'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $trend = $this->ratingTrend($instructor);

        return view('trainer.performance', compact(
            'instructor',
            'period',
            'ratingSummary',
            'ratingDistribution',
            'bookedTotal',
            'attendedTotal',
            'checkedOutTotal',
            'uniqueMembers',
            'capacityTotal',
            'sessionsCompleted',
            'attendanceRate',
            'fillRate',
            'reviewRate',
            'classPerformance',
            'recentSessions',
            'reviews',
            'trend'
        ));
    }

    public function show(Request $request, Schedule $schedule)
    {
        $instructor = Auth::guard('trainer')->user()?->instructorProfile;

        abort_if(! $instructor || (int) $schedule->instructor_id !== (int) $instructor->id, 403);

        $schedule->load([
            'classModel',
            'classGroup',
            'packages',
            'customerSchedules' => fn ($query) => $query->where('status', 'confirmed')->with('customer'),
            'ratings' => fn ($query) => $query->with('customer')->orderByDesc('created_at'),
        ]);

        $ratingSummary = (object) [
            'total_reviews' => $schedule->ratings->count(),
            'average_rating' => $schedule->ratings->avg('rating'),
        ];

        $ratingDistribution = collect(range(5, 1))->mapWithKeys(fn ($star) => [
            $star => $schedule->ratings->where('rating', $star)->count(),
        ]);

        $booked = $schedule->customerSchedules->count();
        $checkedIn = (int) Attendance::query()
            ->where('schedule_id', $schedule->id)
            ->whereNotNull('check_in_at')
            ->distinct()
            ->count('customer_id');
        $checkedOut = (int) Attendance::query()
            ->where('schedule_id', $schedule->id)
            ->whereNotNull('check_out_at')
            ->distinct()
            ->count('customer_id');

        $attendanceStats = [
            'booked' => $booked,
            'checked_in' => $checkedIn,
            'checked_out' => $checkedOut,
            'attendance_rate' => $booked > 0 ? min(100, $checkedIn / $booked * 100) : 0,
            'review_rate' => $checkedOut > 0 ? min(100, $schedule->ratings->count() / $checkedOut * 100) : 0,
        ];

        return view('trainer.schedule', compact(
            'instructor',
            'schedule',
            'ratingSummary',
            'ratingDistribution',
            'attendanceStats'
        ));
    }

    protected function getInstructorRatingsQuery($instructor)
    {
        return InstructorRating::query()->where(function ($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id)
                ->orWhere(fn ($q) => $q->whereNull('instructor_id')->where('instructor_name', $instructor->name));
        });
    }

    protected function completedSchedulesQuery($instructor, $since = null)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        return $instructor->schedules()
            ->when($since, fn ($query) => $query->whereDate('schedule_date', '>=', $since))
            ->where(function ($query) use ($today, $now) {
                $query->whereDate('schedule_date', '<', $today)
                    ->orWhere(function ($query) use ($today, $now) {
                        $query->whereDate('schedule_date', $today)
                            ->whereTime('class_time', '<=', $now->format('H:i:s'));
                    });
            });
    }

    protected function ratingDistribution($query)
    {
        $counts = (clone $query)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        return collect(range(5, 1))->mapWithKeys(fn ($star) => [
            $star => (int) ($counts[$star] ?? 0),
        ]);
    }

    protected function ratingTrend($instructor)
    {
        $start = now()->subMonths(5)->startOfMonth();

        $ratings = $this->getInstructorRatingsQuery($instructor)
            ->where('created_at', '>=', $start)
            ->get(['rating', 'created_at'])
            ->groupBy(fn (InstructorRating $rating) => $rating->created_at->format('Y-m'));

        $sessions = $this->completedSchedulesQuery($instructor, $start->copy()->startOfDay())
            ->get(['schedule_date'])
            ->groupBy(fn (Schedule $schedule) => $schedule->schedule_date?->format('Y-m'));

        return collect(range(0, 5))->map(function ($offset) use ($start, $ratings, $sessions) {
            $month = $start->copy()->addMonths($offset);

            return (object) [
                'month' => $month,
                'label' => $month->locale('id')->isoFormat('MMM'),
                'rating' => $ratings->get($month->format('Y-m'))?->avg('rating'),
                'reviews' => $ratings->get($month->format('Y-m'))?->count() ?? 0,
                'sessions' => $sessions->get($month->format('Y-m'))?->count() ?? 0,
            ];
        });
    }
}
