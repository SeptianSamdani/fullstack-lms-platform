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
    public function index(Course $course)
    {
        return response()->json($course->modules()->with('lessons')->orderBy('order')->get());
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
    public function show(Module $module)
    {
        return response()->json($module->load('lessons'));

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
