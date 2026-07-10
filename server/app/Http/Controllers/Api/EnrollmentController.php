<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(
            $request->user()
                ->enrollments()
                ->with('course.category')
                ->latest('enrolled_at')
                ->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $user = $request->user();

        if ($course->status !== 'published') {
            return response()->json(['message' => 'Kursus ini belum tersedia.'], 403);
        }

        if (Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Sudah terdaftar di kursus ini.'], 409);
        }

        if ($course->type === 'paid' && !$user->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Kursus ini memerlukan subscription aktif.',
                'requires_subscription' => true,
                'redirect_to' => '/subscription-plans',
            ], 403);
        }

        try {
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Kamu sudah terdaftar di kursus ini.'], 409);
            }
            throw $e;
        }

        return response()->json($enrollment->load('course'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Enrollment $enrollment)
    {
        abort_if($enrollment->user_id !== $request->user()->id, 403);
    
        // Cegah unenroll jika sudah completed
        if ($enrollment->status === 'completed') {
            return response()->json(['message' => 'Tidak bisa unenroll dari kursus yang sudah selesai.'], 422);
        }
        $enrollment->delete();
        return response()->json(null, 204);
    }
}
