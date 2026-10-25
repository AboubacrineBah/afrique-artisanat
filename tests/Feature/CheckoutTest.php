<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Mail\NewOrderPlaced;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['shop.shipping_fee' => 2000, 'shop.free_shipping_threshold' => 50000]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'shipping_name' => 'Client Démo',
            'shipping_phone' => '+223 70 00 00 00',
            'shipping_city' => 'Bamako',
            'shipping_address' => 'Hamdallaye ACI 2000',
            'payment_method' => 'cod',
            'accept_terms' => '1',
        ], $overrides);
    }

    public function test_a_guest_is_redirected_to_login(): void
    {
        $this->post(route('checkout.store'), $this->payload())->assertRedirect(route('login'));
    }

    public function test_a_customer_can_place_an_order(): void
    {
        Mail::fake();

        config(['shop.admin_email' => 'admin@example.test']);

        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 10000, 'stock' => 5]);

        $response = $this->actingAs($user)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('checkout.store'), $this->payload());

        $order = Order::firstOrFail();

        $response->assertRedirect(route('account.orders.show', $order));

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame(OrderStatus::Pending, $order->status);
        $this->assertSame(PaymentStatus::Pending, $order->payment_status);
        $this->assertSame(20000, $order->subtotal);
        $this->assertSame(2000, $order->shipping_fee);
        $this->assertSame(22000, $order->total);
        $this->assertCount(1, $order->items);
        $this->assertSame(3, $product->fresh()->stock);

        $response->assertSessionMissing('cart');
        Mail::assertSent(OrderConfirmation::class);
        Mail::assertSent(NewOrderPlaced::class, function (NewOrderPlaced $mail) use ($order, $product): bool {
            $mail->assertSeeInHtml($order->number);
            $mail->assertSeeInHtml($product->name);

            return $mail->hasTo(config('shop.admin_email')) && $mail->order->is($order);
        });
    }

    public function test_shipping_is_free_above_the_threshold(): void
    {
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 30000, 'stock' => 5]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('checkout.store'), $this->payload());

        $order = Order::firstOrFail();

        $this->assertSame(0, $order->shipping_fee);
        $this->assertSame(60000, $order->total);
    }

    public function test_mobile_money_demo_marks_the_order_as_paid(): void
    {
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 10000, 'stock' => 5]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->payload(['payment_method' => 'orange_money_demo']));

        $order = Order::firstOrFail();

        $this->assertSame(PaymentStatus::Paid, $order->payment_status);
        $this->assertStringStartsWith('DEMO-OM-', $order->payment_reference);
    }

    public function test_an_order_is_refused_when_stock_is_insufficient(): void
    {
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => 3]])
            ->post(route('checkout.store'), $this->payload())
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(1, $product->fresh()->stock);
        Mail::assertNothingSent();
    }

    public function test_the_terms_must_be_accepted(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)
            ->withSession(['cart' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->payload(['accept_terms' => null]))
            ->assertSessionHasErrors('accept_terms');

        $this->assertSame(0, Order::count());
    }

    public function test_a_customer_cannot_view_someone_elses_order(): void
    {
        Mail::fake();

        /** @var User $owner */
        $owner = User::factory()->create();
        /** @var User $other */
        $other = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($owner)
            ->withSession(['cart' => [$product->id => 1]])
            ->post(route('checkout.store'), $this->payload());

        $order = Order::firstOrFail();

        $this->actingAs($other)->get(route('account.orders.show', $order))->assertForbidden();
    }
}
