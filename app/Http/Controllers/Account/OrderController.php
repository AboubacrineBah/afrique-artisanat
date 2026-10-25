<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->withCount('items')->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order); // OrderPolicy : propriétaire ou administrateur

        $order->load('items.product');

        return view('account.orders.show', compact('order'));
    }
}
