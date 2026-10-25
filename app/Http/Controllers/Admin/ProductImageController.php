<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);

        $wasPrimary = $image->is_primary;

        // Les images de démonstration (public/images) ne sont pas dans le disque "public"
        if (! str_starts_with($image->path, 'images/')) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        if ($wasPrimary) {
            $product->images()->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image supprimée.');
    }

    public function makePrimary(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Image principale mise à jour.');
    }
}
