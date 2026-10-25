<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    /** Commande de 2 articles à 1 000 FCFA, stock déjà décrémenté (5 → 3). */
    private function makeOrder(Product $product): Order
    {
        $order = Order::create([
            'user_id' => User::factory()->create()->id,
            'status' => OrderStatus::Pending,
            'payment_method' => PaymentMethod::CashOnDelivery,
            'payment_status' => PaymentStatus::Pending,
            'subtotal' => 2000,
            'shipping_fee' => 0,
            'total' => 2000,
            'shipping_name' => 'Client Démo',
            'shipping_phone' => '+223 70 00 00 00',
            'shipping_city' => 'Bamako',
            'shipping_address' => 'Rue de test',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 1000,
            'quantity' => 2,
            'line_total' => 2000,
        ]);

        $product->decrement('stock', 2);

        return $order;
    }

    public function test_cancelling_an_order_restocks_the_products(): void
    {
        $product = Product::factory()->create(['stock' => 5, 'price' => 1000]);
        $order = $this->makeOrder($product);

        app(OrderService::class)->changeStatus($order, OrderStatus::Cancelled);

        $this->assertSame(OrderStatus::Cancelled, $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_an_invalid_transition_is_refused(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $order = $this->makeOrder($product);

        $this->expectException(DomainException::class);

        app(OrderService::class)->changeStatus($order, OrderStatus::Delivered);
    }

    public function test_a_shipped_order_cannot_be_cancelled(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $order = $this->makeOrder($product);

        $service = app(OrderService::class);
        $service->changeStatus($order, OrderStatus::Confirmed);
        $service->changeStatus($order, OrderStatus::Shipped);

        $this->expectException(DomainException::class);

        $service->changeStatus($order, OrderStatus::Cancelled);
    }

    public function test_delivering_a_cash_on_delivery_order_marks_it_as_paid(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $order = $this->makeOrder($product);

        $service = app(OrderService::class);
        $service->changeStatus($order, OrderStatus::Confirmed);
        $service->changeStatus($order, OrderStatus::Shipped);
        $service->changeStatus($order, OrderStatus::Delivered);

        $order = $order->fresh();

        $this->assertSame(OrderStatus::Delivered, $order->status);
        $this->assertSame(PaymentStatus::Paid, $order->payment_status);
    }
}
