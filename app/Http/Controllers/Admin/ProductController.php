<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'images'])
            ->when($request->query('q'), fn ($q, $term) => $q->where('name', 'like', '%' . addcslashes($term, '%_\\') . '%'))
            ->when($request->query('category'), fn ($q, $id) => $q->where('category_id', $id))
            ->when($request->query('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->query('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->query('stock') === 'low', fn ($q) => $q->where('stock', '<=', config('shop.low_stock_threshold')))
            ->latest()
            ->paginate(config('shop.admin_per_page'))
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', $this->formData(new Product(['is_active' => true])));
    }

    public function store(ProductRequest $request)
    {
        $data = $this->productData($request);
        $product = Product::create($data);

        $this->storeImages($product, $request->file('images', []));

        return redirect()->route('admin.products.edit', $product)->with('success', 'Produit créé.');
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view('admin.products.form', $this->formData($product));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $files = $request->file('images', []);

        if ($product->images()->count() + count($files) > config('shop.max_images_per_product')) {
            return back()->withInput()->with('error', 'Nombre maximum d’images atteint (' . config('shop.max_images_per_product') . ' par produit).');
        }

        $product->update($this->productData($request));
        $this->storeImages($product, $files);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Produit mis à jour.');
    }

    /** Suppression logique : le produit disparaît du site mais reste visible dans l'historique des commandes. */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé.');
    }

    private function formData(Product $product): array
    {
        return [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'artisans' => Artisan::orderBy('name')->get(),
        ];
    }

    /** Données du produit sans les images ; un slug vide est laissé à la génération automatique. */
    private function productData(ProductRequest $request): array
    {
        $data = collect($request->validated())->except('images')->all();

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        return $data;
    }

    /** @param  \Illuminate\Http\UploadedFile[]  $files */
    private function storeImages(Product $product, array $files): void
    {
        $order = (int) $product->images()->max('sort_order');
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
                'alt' => $product->name,
                'sort_order' => ++$order,
                'is_primary' => ! $hasPrimary,
            ]);

            $hasPrimary = true;
        }
    }
}
