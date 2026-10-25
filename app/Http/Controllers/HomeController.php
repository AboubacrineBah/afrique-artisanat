<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'categories' => Category::active()
                ->withCount(['products' => fn ($q) => $q->active()])
                ->orderBy('name')
                ->get(),
            'featuredProducts' => Product::with('images')->active()->featured()->inStock()->latest()->take(8)->get(),
            'newProducts' => Product::with('images')->active()->latest()->take(8)->get(),
            'artisans' => Artisan::inRandomOrder()->take(3)->get(),
        ]);
    }
}
