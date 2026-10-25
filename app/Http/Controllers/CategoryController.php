<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {
        abort_unless($category->is_active, 404);

        $products = Product::with('images')
            ->active()
            ->where('category_id', $category->id)
            ->filter($request->only('sort'))
            ->paginate(config('shop.per_page'))
            ->withQueryString();

        return view('categories.show', [
            'category' => $category,
            'products' => $products,
            'sorts' => Product::SORTS,
        ]);
    }
}
