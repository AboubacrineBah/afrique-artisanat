@extends('layouts.admin')

@section('title', 'Commande ' . $order->number)
@section('heading', 'Commande ' . $order->number)
@section('actions')<span
class="badge {{ $order->status->badge() }} fs-6">{{ $order->status->label() }}</span>@endsection

@section('content')
<a class="btn btn-primary btn-sm mb-4" href="{{ route('admin.orders.index') }}">
    ‹ Retour aux commandes
</a>
<a class="btn btn-outline-primary btn-sm mb-4" href="{{ route('admin.orders.pdf', $order) }}">
    Télécharger la commande (PDF)
</a>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="panel h-100">
            <h2 class="h5">Client</h2>
            {{ $order->user->name }}<br>{{ $order->user->email }}<br>
            <span class="small">Commande passée le {{ $order->placed_at?->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel h-100">
            <h2 class="h5">Livraison</h2>
            {{ $order->shipping_name }}<br>{{ $order->shipping_address }}<br>{{ $order->shipping_city }}<br>{{ $order->shipping_phone }}
            @if ($order->notes)
            <p class="small mt-2 mb-0">Instructions : {{ $order->notes }}</p>@endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel h-100">
            <h2 class="h5">Paiement</h2>
            {{ $order->payment_method->label() }}<br>
            <span class="badge {{ $order->payment_status->badge() }}">{{ $order->payment_status->label() }}</span>
            @if ($order->payment_reference)
            <div class="small mt-2">Référence : {{ $order->payment_reference }}</div>@endif
        </div>
    </div>
</div>

<div class="panel p-2 mb-4">
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
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
                        <td>{{ $item->product_name }}@if ($item->product?->trashed()) <span
                        class="badge text-bg-secondary">produit supprimé</span>@endif</td>
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
</div>

<div class="panel" style="max-width: 32rem">
    <h2 class="h5 mb-3">Changer le statut</h2>
    @php($next = $order->status->nextStatuses())
    @if ($next === [])
        <p class="mb-0">Cette commande est <strong>{{ mb_strtolower($order->status->label()) }}</strong> : son statut ne
            peut plus être modifié.</p>
    @else
        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
            @csrf @method('PATCH')
            <x-select name="status" :label="'Statut actuel : ' . $order->status->label()"
                :options="collect($next)->mapWithKeys(fn($s) => [$s->value => $s->label()])" />
            <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
            <p class="form-text mb-0">Une annulation remet les produits en stock.</p>
        </form>
    @endif
</div>
@endsection