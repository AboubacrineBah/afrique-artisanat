<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Exceptions\CheckoutException;
use App\Http\Requests\CheckoutRequest;
use App\Mail\NewOrderPlaced;
use App\Mail\OrderConfirmation;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckoutController extends Controller
{
    public function create(Request $request, CartService $cart)
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.create', [
            'cart' => $cart->summary(),
            'addresses' => $request->user()->addresses,
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }

    public function store(CheckoutRequest $request, CartService $cart, OrderService $orders, PaymentManager $payments)
    {
        try {
            $order = $orders->place($request->user(), $request->validated(), $request->paymentMethod());
        } catch (CheckoutException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $payments->process($order);
        $cart->clear();

        // Un échec d'envoi d'e-mail ne doit jamais annuler une commande déjà enregistrée
        try {
            Mail::to($request->user())->send(new OrderConfirmation($order->fresh('items')));
        } catch (Throwable $e) {
            report($e);
        }

        try {
            Mail::to(config('shop.admin_email'))->send(new NewOrderPlaced($order));
        } catch (Throwable $e) {
            report($e);
        }

        return redirect()->route('account.orders.show', $order)
            ->with('success', "Merci ! Votre commande {$order->number} est enregistrée.");
    }
}
