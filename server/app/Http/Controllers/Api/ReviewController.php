<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    /**
     * Tampilkan semua review untuk sebuah course.
     */
    public function index(Course $course)
    {
        return response()->json(
            $course->reviews()
                ->with('user:id,name,avatar_url')
                ->latest()
                ->paginate(10)
        );
    }

    /**
     * Buat review baru untuk course (hanya user yang sudah enroll & belum pernah review).
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('create', [Review::class, $course]);

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::create([
            ...$validated,
            'user_id'   => $request->user()->id,
            'course_id' => $course->id,
        ]);

        return response()->json($review->load('user:id,name,avatar_url'), 201);
    }

    /**
     * Update review milik sendiri.
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating'  => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    /**
     * Hapus review (pemilik atau admin).
     */
    public function destroy(Request $request, Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(null, 204);
    }
}