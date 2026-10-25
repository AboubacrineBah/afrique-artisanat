@extends('layouts.account')

@section('title', 'Commande ' . $order->number)
@section('heading', 'Commande ' . $order->number)

@section('account')
    <p class="mb-4">
        <a class="btn btn-outline-primary mb-4" href="{{ route('account.orders.pdf', $order) }}">
            Télécharger la fiche de commande (PDF)
        </a>
    </p>
    @php
        use App\Enums\OrderStatus;
        $steps = [OrderStatus::Pending, OrderStatus::Confirmed, OrderStatus::Shipped, OrderStatus::Delivered];
        $currentIndex = array_search($order->status, $steps, true);
    @endphp

    @if ($order->status === OrderStatus::Cancelled)
        <p><span class="badge {{ $order->status->badge() }} fs-6">{{ $order->status->label() }}</span></p>
    @else
        <ol class="order-steps mb-4" aria-label="Suivi de la commande">
            @foreach ($steps as $i => $step)
                <li @class(['done' => $i < $currentIndex, 'current' => $i === $currentIndex]) @if ($i === $currentIndex)
                aria-current="step" @endif>{{ $step->label() }}</li>
            @endforeach
        </ol>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="panel h-100">
                <h2 class="h5">Livraison</h2>
                {{ $order->shipping_name }}<br>{{ $order->shipping_address }}<br>{{ $order->shipping_city }}<br>{{ $order->shipping_phone }}
                @if ($order->notes)
                <p class="small mt-2 mb-0">Instructions : {{ $order->notes }}</p>@endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel h-100">
                <h2 class="h5">Paiement</h2>
                {{ $order->payment_method->label() }}<br>
                <span class="badge {{ $order->payment_status->badge() }}">{{ $order->payment_status->label() }}</span>
                @if ($order->payment_reference)
                <div class="small mt-2">Référence : {{ $order->payment_reference }}</div>@endif
            </div>
        </div>
    </div>

    <div class="table-responsive panel p-2">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="text-end">Prix</th>
                    <th class="text-end">Qté</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            @if ($item->product && !$item->product->trashed() && $item->product->is_active)
                                <a href="{{ route('products.show', $item->product) }}">{{ $item->product_name }}</a>
                            @else
                                {{ $item->product_name }}
                            @endif
                        </td>
                        <td class="text-end">{{ $item->unit_price_formatted }}</td>
                        <td class="text-end">{{ $item->quantity }}</td>
                        <td class="text-end">{{ $item->line_total_formatted }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end">Livraison</td>
                    <td class="text-end">{{ $order->shipping_fee_formatted }}</td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total</td>
                    <td class="text-end">{{ $order->total_formatted }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection