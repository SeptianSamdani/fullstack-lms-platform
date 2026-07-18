<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     * Konten (content_url & content) hanya ditampilkan untuk yang berhak akses
     * (owner/instructor/admin atau student yang sudah enroll di course berbayar).
     * Selain itu, hanya metadata lesson (title, duration, order, dst) yang terlihat.
     */
    public function index(Request $request, Module $module)
    {
        $module->loadMissing('course');
        $canViewContent = $module->course->isContentAccessibleBy($request->user());

        $lessons = $module->lessons()->orderBy('order')->get();

        if (!$canViewContent) {
            $lessons = $lessons->map(function ($lesson) {
                return collect($lesson)->except(['content_url', 'content'])->all();
            });
        }

        return response()->json($lessons);
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
            'content_url' => 'nullable|url',
            'content' => 'required_if:content_type,text,mixed|nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:102400',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $this->validateVideoSource($request, $validated['content_type']);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('lms/lessons', 'cloudinary');
            $validated['content_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['video']);

        return response()->json($module->lessons()->create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Lesson $lesson)
    {
        $lesson->loadMissing('module.course');
        $course = $lesson->module->course;

        abort_unless(
            $course->isContentAccessibleBy($request->user()),
            403, 'Anda harus enroll terlebih dahulu.'
        );

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
            'content_url' => 'nullable|url',
            'content' => 'required_if:content_type,text,mixed|nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:102400',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $contentType = $validated['content_type'] ?? $lesson->content_type;
        if (!$request->hasFile('video')) {
            $this->validateVideoSource($request, $contentType, $lesson->content_url);
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('lms/lessons', 'cloudinary');
            $validated['content_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['video']);

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

    /**
     * Pastikan lesson video/mixed punya sumber video:
     * baik dari file upload, content_url (link), atau nilai lama saat update.
     */
    private function validateVideoSource(Request $request, string $contentType, ?string $existingUrl = null): void
    {
        if (!in_array($contentType, ['video', 'mixed'])) {
            return;
        }

        $hasSource = $request->hasFile('video') || $request->filled('content_url') || $existingUrl;

        if (!$hasSource) {
            throw ValidationException::withMessages([
                'video' => 'Wajib mengisi content_url (link video) atau upload file video.',
            ]);
        }
    }
}