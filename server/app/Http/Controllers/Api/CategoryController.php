<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        return response()->json(
            Category::withCount(['courses' => function ($q) {
                $q->where('status', 'published');
            }])->get()
        );
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('lms/categories', 'cloudinary');
            $validated['icon_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['icon']);

        return response()->json(Category::create($validated), 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('lms/categories', 'cloudinary');
            $validated['icon_url'] = Storage::disk('cloudinary')->url($path);
        }
        unset($validated['icon']);

        $category->update($validated);
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', Category::class);
        $category->delete();
        return response()->json(null, 204);
    }
}