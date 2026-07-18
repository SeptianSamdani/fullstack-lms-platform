<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use AuthorizesRequests; 
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Course $course)
    {
        $canViewContent = $course->isContentAccessibleBy($request->user());

        $modules = $course->modules()->with('lessons')->orderBy('order')->get();

        if (!$canViewContent) {
            $modules->each(function ($module) {
                $module->setRelation('lessons', $module->lessons->map(function ($lesson) {
                    return collect($lesson)->except(['content_url', 'content'])->all();
                }));
            });
        }

        return response()->json($modules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('create', [Module::class, $course]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        return response()->json($course->modules()->create($validated), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Module $module)
    {
        $module->loadMissing('lessons');
        $course = Course::findOrFail($module->course_id);
        $canViewContent = $course->isContentAccessibleBy($request->user());

        if (!$canViewContent) {
            $module->setRelation('lessons', $module->lessons->map(function ($lesson) {
                return collect($lesson)->except(['content_url', 'content'])->all();
            }));
        }

        return response()->json($module);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize('update', $module);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $module->update($validated);
        return response()->json($module);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $this->authorize('delete', $module);
        $module->delete();
        return response()->json(null, 204);
    }
}
