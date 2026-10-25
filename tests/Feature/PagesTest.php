<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test de fumée : chaque page s'affiche sans erreur (code 200).
 * Il détecte les fautes dans les vues Blade, les routes ou les variables manquantes.
 */
class PagesTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        $product = Product::factory()->featured()->create(['stock' => 5, 'price' => 12000]);
        $product->images()->create(['path' => 'images/placeholder.svg', 'alt' => 'Test', 'sort_order' => 1, 'is_primary' => true]);

        return $product;
    }

    private function orderFor(User $user, Product $product): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatus::Pending,
            'payment_method' => PaymentMethod::CashOnDelivery,
            'payment_status' => PaymentStatus::Pending,
            'subtotal' => 12000,
            'shipping_fee' => 2000,
            'total' => 14000,
            'shipping_name' => 'Client Démo',
            'shipping_phone' => '+223 70 00 00 00',
            'shipping_city' => 'Bamako',
            'shipping_address' => 'Rue de test',
        ]);
        $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => 12000, 'quantity' => 1, 'line_total' => 12000]);

        return $order;
    }

    public function test_public_pages_render(): void
    {
        $product = $this->product();

        $urls = [
            route('home'),
            route('products.index'),
            route('products.index', ['q' => 'a', 'sort' => 'price_asc', 'in_stock' => 1]),
            route('products.show', $product),
            route('categories.show', $product->category),
            route('artisans.index'),
            route('artisans.show', $product->artisan),
            route('cart.index'),
            route('contact.create'),
            route('login'),
            route('register'),
            route('pages.legal'),
            route('pages.terms'),
            route('pages.privacy'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_the_cart_and_checkout_pages_render_with_items(): void
    {
        $product = $this->product();
        $user = User::factory()->create();

        $this->withSession(['cart' => [$product->id => 2]])->get(route('cart.index'))->assertOk()->assertSee($product->name);

        $this->actingAs($user)->withSession(['cart' => [$product->id => 2]])->get(route('checkout.create'))->assertOk();
    }

    public function test_customer_account_pages_render(): void
    {
        $product = $this->product();
        $user = User::factory()->create();
        $order = $this->orderFor($user, $product);
        $user->addresses()->create(['label' => 'Domicile', 'recipient_name' => 'Client Démo', 'phone' => '+223 70 00 00 00', 'city' => 'Bamako', 'address_line' => 'Rue de test', 'is_default' => true]);

        $this->actingAs($user);

        foreach ([route('account.orders.index'), route('account.orders.show', $order), route('account.profile.edit'), route('account.addresses.index')] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_pages_render(): void
    {
        $product = $this->product();
        $customer = User::factory()->create();
        $order = $this->orderFor($customer, $product);
        $message = ContactMessage::create(['name' => 'Visiteur', 'email' => 'visiteur@example.test', 'subject' => 'Bonjour', 'message' => 'Un message de test suffisamment long.']);

        $this->actingAs(User::factory()->admin()->create());

        $urls = [
            route('admin.dashboard'),
            route('admin.products.index'),
            route('admin.products.create'),
            route('admin.products.edit', $product),
            route('admin.categories.index'),
            route('admin.categories.create'),
            route('admin.categories.edit', $product->category),
            route('admin.artisans.index'),
            route('admin.artisans.create'),
            route('admin.artisans.edit', $product->artisan),
            route('admin.orders.index'),
            route('admin.orders.show', $order),
            route('admin.customers.index'),
            route('admin.messages.index'),
            route('admin.messages.show', $message),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_an_unknown_page_shows_the_custom_404(): void
    {
        $this->get('/page-qui-n-existe-pas')->assertNotFound();
    }
}
