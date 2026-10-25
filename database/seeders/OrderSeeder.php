<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /** Commandes de démonstration réparties sur les 30 derniers jours (tableau de bord et historique client). */
    public function run(): void
    {
        $customers = User::where('role', UserRole::Customer->value)->get();
        $products = Product::active()->where('stock', '>', 0)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = [
            OrderStatus::Delivered, OrderStatus::Delivered, OrderStatus::Shipped,
            OrderStatus::Confirmed, OrderStatus::Pending, OrderStatus::Cancelled,
            OrderStatus::Delivered, OrderStatus::Pending,
        ];
        $methods = PaymentMethod::cases();

        foreach ($statuses as $i => $status) {
            $customer = $customers[$i % $customers->count()];
            $method = $methods[$i % count($methods)];
            $date = now()->subDays(random_int(0, 30));

            $lines = $products->random(random_int(1, 3))->map(function (Product $product) {
                $quantity = random_int(1, 2);

                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'line_total' => $product->price * $quantity,
                ];
            });

            $subtotal = (int) $lines->sum('line_total');
            $shipping = $subtotal >= (int) config('shop.free_shipping_threshold') ? 0 : (int) config('shop.shipping_fee');

            $paid = $method->isMobileMoney() || $status === OrderStatus::Delivered;

            $order = Order::create([
                'user_id' => $customer->id,
                'status' => $status,
                'payment_method' => $method,
                'payment_status' => $paid ? PaymentStatus::Paid : PaymentStatus::Pending,
                'payment_reference' => $method->isMobileMoney() ? 'DEMO-' . strtoupper(substr(md5((string) $i), 0, 8)) : null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total' => $subtotal + $shipping,
                'shipping_name' => $customer->name,
                'shipping_phone' => '+223 00 00 00 00',
                'shipping_city' => 'Bamako',
                'shipping_address' => 'Quartier de démonstration, rue 100',
                'placed_at' => $date,
            ]);

            $order->items()->createMany($lines->all());
            $order->forceFill(['created_at' => $date, 'updated_at' => $date])->saveQuietly();
        }
    }
}
