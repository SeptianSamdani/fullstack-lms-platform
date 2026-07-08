<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module)
    {
        return response()->json($module->lessons()->orderBy('order')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        $this->authorize('create', [Lesson::class, $module]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:video,text,mixed',
            'content_url' => 'required_if:content_type,video,mixed|nullable|url',
            'content' => 'required_if:content_type,text,mixed|nullable|string',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        return response()->json($module->lessons()->create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Lesson $lesson)
    {
        $lesson->loadMissing('module.course');
        $course = $lesson->module->course;
        // Kursus gratis: bisa diakses siapa saja
        // Kursus berbayar: harus enrolled
        if ($course->type === 'paid') {
            $user = $request->user();
            abort_unless(
                $user && Enrollment::where('user_id', $user->id)
                    ->where('course_id', $course->id)->exists(),
                403, 'Anda harus enroll terlebih dahulu.'
            );
        }
        return response()->json($lesson->load('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content_type' => 'sometimes|in:video,text,mixed',
            'content_url' => 'required_if:content_type,video,mixed|nullable|url',
            'content' => 'required_if:content_type,text,mixed|nullable|string',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $lesson->update($validated);
        return response()->json($lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('delete', $lesson);
        $lesson->delete();
        return response()->json(null, 204);
    }
}
