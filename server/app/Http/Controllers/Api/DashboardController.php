<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Progress;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_revenue' => Payment::where('status', 'success')->sum('amount'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
        ]);
    }

    public function instructor(Request $request)
    {
        $userId = $request->user()->id;
        $courses = Course::where('instructor_id', $userId)
            ->withCount('enrollments')
            ->with('category:id,name')
            ->get();

        return response()->json([
            'total_courses'  => $courses->count(),
            'total_students' => $courses->sum('enrollments_count'),
            'published'      => $courses->where('status', 'published')->count(),
            'draft'          => $courses->where('status', 'draft')->count(),
            'courses'        => $courses,
        ]);
    }


    public function student(Request $request)
    {
        $user = $request->user();
        $enrollments = $user->enrollments()
            ->with('course.category:id,name')
            ->get();

        // Hitung progress untuk semua enrollment sekaligus
        $enrollments->each(function ($enrollment) use ($user) {
            $totalLessons = Lesson::whereHas('module', fn($q) =>
                $q->where('course_id', $enrollment->course_id)
            )->count();

            $completed = Progress::where('user_id', $user->id)
                ->whereHas('lesson.module', fn($q) =>
                    $q->where('course_id', $enrollment->course_id)
                )->count();

            $enrollment->progress = [
                'total'      => $totalLessons,
                'completed'  => $completed,
                'percentage' => $totalLessons ? round($completed / $totalLessons * 100) : 0,
            ];
        });

        return response()->json([
            'enrollments'       => $enrollments,
            'total_enrolled'    => $enrollments->count(),
            'total_completed'   => $enrollments->where('status', 'completed')->count(),
            'has_subscription'  => $user->hasActiveSubscription(),
        ]);
    }

}
