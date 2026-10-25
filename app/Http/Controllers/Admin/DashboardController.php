<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $cancelled = OrderStatus::Cancelled->value;

        $stats = [
            'orders_total' => Order::count(),
            'orders_pending' => Order::where('status', OrderStatus::Pending->value)->count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            // Chiffre d'affaires : commandes non annulées, en FCFA
            'revenue' => (int) Order::where('status', '!=', $cancelled)->sum('total'),
            'products_active' => Product::active()->count(),
            'customers' => User::where('role', UserRole::Customer->value)->count(),
            'messages_unread' => ContactMessage::unread()->count(),
        ];

        $topProducts = OrderItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', fn ($q) => $q->where('status', '!=', $cancelled))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'latestOrders' => Order::with('user')->latest()->take(8)->get(),
            'lowStock' => Product::active()
                ->where('stock', '<=', config('shop.low_stock_threshold'))
                ->orderBy('stock')
                ->take(6)
                ->get(),
            'topProducts' => $topProducts,
        ]);
    }
}
