<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use DomainException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->withCount('items')
            ->withStatus($request->query('status'))
            ->when($request->query('q'), function ($query, string $term) {
                $like = '%' . addcslashes($term, '%_\\') . '%';
                $query->where(fn ($w) => $w->where('number', 'like', $like)->orWhere('shipping_name', 'like', $like));
            })
            ->latest()
            ->paginate(config('shop.admin_per_page'))
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(OrderStatusRequest $request, Order $order, OrderService $orders)
    {
        try {
            $orders->changeStatus($order, $request->target());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
