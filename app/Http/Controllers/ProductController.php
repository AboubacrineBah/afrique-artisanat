<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /** Catalogue : recherche, filtres (catégorie, artisan, matière, prix, stock), tri et pagination. */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:120'],
            'artisan' => ['nullable', 'string', 'max:140'],
            'material' => ['nullable', 'string', 'max:100'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'in_stock' => ['nullable', 'boolean'],
            'sort' => ['nullable', Rule::in(array_keys(Product::SORTS))],
        ]);

        $products = Product::with(['images', 'category'])
            ->active()
            ->filter($filters)
            ->paginate(config('shop.per_page'))
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'filters' => $filters,
            'sorts' => Product::SORTS,
            'categories' => Category::active()->orderBy('name')->get(),
            'artisans' => Artisan::orderBy('name')->get(),
            'materials' => Product::active()->whereNotNull('material')->distinct()->orderBy('material')->pluck('material'),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['images', 'category', 'artisan']);

        $related = Product::with('images')
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
