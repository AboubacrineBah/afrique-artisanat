<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OrderPdfController extends Controller
{
    public function download(Order $order): Response
    {
        Gate::authorize('view', $order);
        $order->load('user', 'items');
        return Pdf::loadView('pdf.orders.show', compact('order'))
            ->download('commande-' . $order->number . '.pdf');
    }
}
