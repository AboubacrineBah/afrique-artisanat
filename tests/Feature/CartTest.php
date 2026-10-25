<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_add_a_product_to_the_cart(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame([$product->id => 2], session('cart'));
    }

    public function test_quantity_is_capped_by_available_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 5])
            ->assertSessionHas('warning');

        $this->assertSame([$product->id => 3], session('cart'));
    }

    public function test_an_out_of_stock_product_cannot_be_added(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertSessionHas('error');

        $this->assertNull(session('cart'));
    }

    public function test_an_inactive_product_cannot_be_added(): void
    {
        $product = Product::factory()->inactive()->create(['stock' => 5]);

        $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertNotFound();
    }

    public function test_setting_the_quantity_to_zero_removes_the_line(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->withSession(['cart' => [$product->id => 2]])
            ->patch(route('cart.update', $product->id), ['quantity' => 0])
            ->assertRedirect(route('cart.index'));

        $this->assertSame([], session('cart'));
    }
}
