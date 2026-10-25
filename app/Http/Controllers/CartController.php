<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('cart.index', ['cart' => $cart->summary()]);
    }

    public function store(CartRequest $request, CartService $cart)
    {
        $product = Product::active()->findOrFail($request->integer('product_id'));
        $requested = $request->integer('quantity');
        $previous = $cart->raw()[$product->id] ?? 0;

        $quantity = $cart->add($product, $requested);

        if ($quantity === 0) {
            return back()->with('error', "« {$product->name} » est en rupture de stock.");
        }

        if ($quantity < $previous + max($requested, 1)) {
            return back()->with('warning', "Quantité limitée à {$quantity} pour « {$product->name} » (stock disponible).");
        }

        return back()->with('success', "« {$product->name} » a été ajouté au panier.");
    }

    public function update(CartRequest $request, Product $product, CartService $cart)
    {
        $requested = $request->integer('quantity');
        $quantity = $cart->update($product, $requested);

        if ($requested > 0 && $quantity < $requested) {
            return redirect()->route('cart.index')
                ->with('warning', "Quantité limitée à {$quantity} pour « {$product->name} » (stock disponible).");
        }

        return redirect()->route('cart.index')->with('success', 'Panier mis à jour.');
    }

    public function destroy(Product $product, CartService $cart)
    {
        $cart->remove($product);

        return redirect()->route('cart.index')->with('success', "« {$product->name} » a été retiré du panier.");
    }

    public function clear(CartService $cart)
    {
        $cart->clear();

        return redirect()->route('cart.index')->with('success', 'Votre panier a été vidé.');
    }
}
