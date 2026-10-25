<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPdfTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrder(User $user): Order
    {
        $order = Order::create([
            'user_id' => $user->getKey(),
            'status' => OrderStatus::Pending,
            'payment_method' => PaymentMethod::CashOnDelivery,
            'payment_status' => PaymentStatus::Pending,
            'subtotal' => 2000,
            'shipping_fee' => 500,
            'total' => 2500,
            'shipping_name' => 'Client de test',
            'shipping_phone' => '+223 70 00 00 00',
            'shipping_city' => 'Bamako',
            'shipping_address' => 'Rue de test',
        ]);

        $order->items()->create([
            'product_name' => 'Sculpture de test',
            'unit_price' => 2000,
            'quantity' => 1,
            'line_total' => 2000,
        ]);

        return $order;
    }

    public function test_a_customer_can_download_their_order_pdf(): void
    {
        /** @var User $customer */
        $customer = User::factory()->create();
        $order = $this->makeOrder($customer);

        $response = $this->actingAs($customer)
            ->get(route('account.orders.pdf', $order));

        $response->assertOk()
            ->assertDownload('commande-' . $order->getAttribute('number') . '.pdf');

        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_a_customer_cannot_download_another_customers_order_pdf(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        /** @var User $otherCustomer */
        $otherCustomer = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($otherCustomer)
            ->get(route('account.orders.pdf', $order))
            ->assertForbidden();
    }

    public function test_an_admin_can_download_a_customers_order_pdf(): void
    {
        /** @var User $customer */
        $customer = User::factory()->create();
        /** @var User $admin */
        $admin = User::factory()->admin()->create();
        $order = $this->makeOrder($customer);

        $this->actingAs($admin)
            ->get(route('admin.orders.pdf', $order))
            ->assertOk()
            ->assertDownload('commande-' . $order->getAttribute('number') . '.pdf');
    }

    public function test_a_customer_cannot_download_an_order_through_the_admin_route(): void
    {
        /** @var User $customer */
        $customer = User::factory()->create();
        $order = $this->makeOrder($customer);

        $this->actingAs($customer)
            ->get(route('admin.orders.pdf', $order))
            ->assertForbidden();
    }

    public function test_a_guest_is_redirected_to_login_from_the_pdf_route(): void
    {
        /** @var User $customer */
        $customer = User::factory()->create();
        $order = $this->makeOrder($customer);

        $this->get(route('account.orders.pdf', $order))
            ->assertRedirect(route('login'));
    }
}