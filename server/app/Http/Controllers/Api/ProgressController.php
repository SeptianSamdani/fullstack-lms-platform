<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{

    public function markComplete(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        // Eager load relasi sekaligus dalam 1 query
        $lesson->loadMissing('module.course');
        $course = $lesson->module->course;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        abort_unless($enrollment, 403, 'Anda belum enroll di kursus ini.');

        $progress = Progress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed_at' => now()]
        );

        // Auto-complete enrollment jika semua lesson sudah selesai
        $courseCompleted = false;
        if ($enrollment->status !== 'completed') {
            $totalLessons = Lesson::whereHas(
                'module', fn($q) => $q->where('course_id', $course->id)
            )->count();

            $completedCount = Progress::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->whereHas('lesson.module', fn($q) => $q->where('course_id', $course->id))
                ->count();

            if ($totalLessons > 0 && $completedCount >= $totalLessons) {
                $enrollment->update(['status' => 'completed']);
                $courseCompleted = true;
            }
        }

        return response()->json([
            'progress'         => $progress,
            'course_completed' => $courseCompleted,
        ]);
    }

    public function courseProgress(Request $request, Course $course)
    {
        $user = $request->user();

        // Pastikan user sudah enroll sebelum bisa melihat progress
        abort_unless(
            Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists(),
            403, 'Anda belum enroll di kursus ini.'
        );

        $totalLessons = Lesson::whereHas(
            'module', fn($q) => $q->where('course_id', $course->id)
        )->count();

        $completed = Progress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereHas('lesson.module', fn($q) => $q->where('course_id', $course->id))
            ->count();

        return response()->json([
            'total_lessons' => $totalLessons,
            'completed'     => $completed,
            'percentage'    => $totalLessons ? round($completed / $totalLessons * 100) : 0,
        ]);
    }
}
