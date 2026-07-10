<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructor:id,name', 'category:id,name'])
            ->withCount('enrollments')
            ->where('status', 'published')
            ->when($request->category_id, fn($q, $id) => $q->where('category_id', $id))
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->type, fn($q, $t) => $q->where('type', $t));
        $courses = $query->paginate($request->per_page ?? 10);

        // Tambahkan info enrollment jika user sudah login
        if ($user = $request->user()) {
            $enrolledCourseIds = $user->enrollments()->pluck('course_id')->toArray();
            $courses->getCollection()->transform(function ($course) use ($enrolledCourseIds) {
                $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
                return $course;
            });
        }

        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class); 

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:free,paid',
            'price' => 'required_if:type,paid|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('lms/courses', 'cloudinary');
            $validated['thumbnail_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['thumbnail']);

        $course = Course::create([...$validated, 'instructor_id' => $request->user()->id]);
        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json($course->load(['instructor', 'category', 'modules.lessons']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'sometimes|in:free,paid',
            'price'       => 'required_if:type,paid|nullable|numeric|min:0',
            'status'      => 'sometimes|in:draft,published',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('lms/courses', 'cloudinary');
            $validated['thumbnail_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['thumbnail']);

        $course->update($validated);
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return response()->json(null, 204);
    }

    public function myCourses(Request $request)
    {
        return response()->json(
            Course::where('instructor_id', $request->user()->id)
                ->with(['category:id,name'])
                ->withCount(['modules', 'enrollments'])
                ->latest()
                ->paginate($request->per_page ?? 10)
        );
    }

    /**
     * Tampilkan course yang sudah di-soft-delete (arsip) milik instructor.
     */
    public function trashed(Request $request)
    {
        return response()->json(
            Course::onlyTrashed()
                ->where('instructor_id', $request->user()->id)
                ->paginate($request->per_page ?? 10)
        );
    }

    /**
     * Pulihkan course yang sudah diarsipkan.
     */
    public function restore(Request $request, int $id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        $this->authorize('update', $course);
        $course->restore();
        return response()->json($course);
    }
}