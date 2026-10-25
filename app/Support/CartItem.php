<?php

namespace App\Support;

use App\Models\Product;

/**
 * Ligne de panier : un produit, une quantité et le total de la ligne.
 */
class CartItem
{
    public function __construct(
        public readonly Product $product,
        public readonly int $quantity,
    ) {
    }

    public function lineTotal(): int
    {
        return $this->product->price * $this->quantity;
    }

    public function isAvailable(): bool
    {
        return $this->product->is_active && $this->product->stock >= $this->quantity;
    }
}
