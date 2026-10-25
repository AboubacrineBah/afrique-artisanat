<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use DomainException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cart)
    {
    }

    /**
     * Transforme le panier en commande dans une transaction :
     * verrouille les produits, contrôle le stock, enregistre la commande et ses lignes, décrémente le stock.
     *
     * @param  array  $shipping  shipping_name, shipping_phone, shipping_city, shipping_address, notes
     *
     * @throws CheckoutException
     */
    public function place(User $user, array $shipping, PaymentMethod $method): Order
    {
        $lines = $this->cart->raw();

        if ($lines === []) {
            throw CheckoutException::emptyCart();
        }

        return DB::transaction(function () use ($user, $shipping, $method, $lines) {
            $products = Product::whereIn('id', array_keys($lines))->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0;
            $items = [];

            foreach ($lines as $productId => $quantity) {
                $product = $products->get($productId);

                if (!$product || !$product->is_active) {
                    throw CheckoutException::unavailable($product?->name ?? 'Produit');
                }

                if ($product->stock < $quantity) {
                    throw CheckoutException::insufficientStock($product->name, $product->stock);
                }

                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }

            $shippingFee = $this->cart->shippingFee($subtotal);

            $order = Order::create(array_merge(
                Arr::only($shipping, ['shipping_name', 'shipping_phone', 'shipping_city', 'shipping_address', 'notes']),
                [
                    'user_id' => $user->id,
                    'status' => OrderStatus::Pending,
                    'payment_method' => $method,
                    'payment_status' => PaymentStatus::Pending,
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total' => $subtotal + $shippingFee,
                ]
            ));

            $order->items()->createMany($items);

            foreach ($items as $item) {
                $products->get($item['product_id'])->decrement('stock', $item['quantity']);
            }

            return $order;
        });
    }

    /**
     * Change le statut en respectant le cycle de vie autorisé (voir OrderStatus::nextStatuses()).
     * - Annulation : le stock est remis en vente (les remboursements Mobile Money ne sont pas gérés en démo).
     * - Livraison : un paiement à la livraison encore "à payer" passe à "payée".
     *
     * @throws DomainException
     */
    public function changeStatus(Order $order, OrderStatus $target): Order
    {
        if (!$order->status->canTransitionTo($target)) {
            throw new DomainException("Passage impossible de « {$order->status->label()} » à « {$target->label()} ».");
        }

        return DB::transaction(function () use ($order, $target) {
            if ($target === OrderStatus::Cancelled) {
                $order->loadMissing('items');

                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::withTrashed()->whereKey($item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }

            if ($target === OrderStatus::Delivered && $order->payment_status === PaymentStatus::Pending) {
                $order->payment_status = PaymentStatus::Paid;
            }

            $order->status = $target;
            $order->save();

            return $order;
        });
    }
}
