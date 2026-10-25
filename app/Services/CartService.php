<?php

namespace App\Services;

use App\Models\Product;
use App\Support\CartItem;
use Illuminate\Support\Collection;

/**
 * Panier stocké en session sous la forme [id_produit => quantité].
 * Les prix et le stock sont toujours relus en base : le panier ne conserve que des quantités.
 */
class CartService
{
    private const KEY = 'cart';

    /** @return array<int,int> */
    public function raw(): array
    {
        return session()->get(self::KEY, []);
    }

    /** Nombre total d'articles (somme des quantités), sans requête SQL. */
    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    public function isEmpty(): bool
    {
        return $this->raw() === [];
    }

    /**
     * Lignes du panier avec leurs produits.
     * Les produits supprimés depuis l'ajout sont retirés automatiquement du panier.
     *
     * @return Collection<int, CartItem>
     */
    public function items(): Collection
    {
        $raw = $this->raw();

        if ($raw === []) {
            return collect();
        }

        $products = Product::with('images')->whereIn('id', array_keys($raw))->get()->keyBy('id');

        $missing = array_diff(array_keys($raw), $products->keys()->all());
        if ($missing !== []) {
            $this->save(array_diff_key($raw, array_flip($missing)));
            $raw = $this->raw();
        }

        return collect($raw)
            ->map(fn(int $quantity, int|string $id) => new CartItem($products->get($id), $quantity))
            ->values();
    }

    /**
     * Ajoute une quantité, plafonnée par le stock et par la limite par ligne.
     * Retourne la quantité présente dans le panier après l'opération (0 si rien n'a pu être ajouté).
     */
    public function add(Product $product, int $quantity = 1): int
    {
        $cart = $this->raw();
        $newQuantity = min(($cart[$product->id] ?? 0) + max($quantity, 1), $this->maxFor($product));

        if ($newQuantity <= 0) {
            return 0;
        }

        $cart[$product->id] = $newQuantity;
        $this->save($cart);

        return $newQuantity;
    }

    /** Fixe la quantité d'une ligne (0 ou moins supprime la ligne). Retourne la quantité retenue. */
    public function update(Product $product, int $quantity): int
    {
        $newQuantity = min($quantity, $this->maxFor($product));

        if ($newQuantity <= 0) {
            $this->remove($product);

            return 0;
        }

        $cart = $this->raw();
        $cart[$product->id] = $newQuantity;
        $this->save($cart);

        return $newQuantity;
    }

    public function remove(Product $product): void
    {
        $cart = $this->raw();
        unset($cart[$product->id]);
        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    public function subtotal(): int
    {
        return $this->items()->sum(fn(CartItem $item) => $item->lineTotal());
    }

    /** Frais de livraison forfaitaires, offerts au-delà du seuil configuré. */
    public function shippingFee(int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return $subtotal >= (int) config('shop.free_shipping_threshold') ? 0 : (int) config('shop.shipping_fee');
    }

    /** Résumé complet en une seule lecture en base : lignes, sous-total, livraison, total. */
    public function summary(): array
    {
        $items = $this->items();
        $subtotal = (int) $items->sum(fn(CartItem $item) => $item->lineTotal());
        $shipping = $this->shippingFee($subtotal);

        return [
            'items' => $items,
            'count' => $items->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    private function maxFor(Product $product): int
    {
        return $product->is_active
            ? min($product->stock, (int) config('shop.max_quantity_per_line'))
            : 0;
    }

    private function save(array $cart): void
    {
        session()->put(self::KEY, $cart);
    }
}
