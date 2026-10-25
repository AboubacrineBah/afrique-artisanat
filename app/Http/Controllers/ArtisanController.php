<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Product;

class ArtisanController extends Controller
{
    public function index()
    {
        return view('artisans.index', [
            'artisans' => Artisan::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function show(Artisan $artisan)
    {
        $products = Product::with('images')
            ->active()
            ->where('artisan_id', $artisan->id)
            ->latest()
            ->paginate(config('shop.per_page'));

        return view('artisans.show', compact('artisan', 'products'));
    }
}
