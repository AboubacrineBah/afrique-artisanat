<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(['is_active' => true])]);
    }

    public function store(CategoryRequest $request)
    {
        Category::create($this->categoryData($request));

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($this->categoryData($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->withTrashed()->exists()) {
            return back()->with('error', 'Cette catégorie contient des produits : déplacez-les avant de la supprimer.');
        }

        $this->deleteFile($category->image_path);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }

    private function categoryData(CategoryRequest $request, ?Category $category = null): array
    {
        $data = collect($request->validated())->except('image')->all();

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        if ($request->hasFile('image')) {
            $this->deleteFile($category?->image_path);
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        return $data;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
