@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
<div class="container pb-5">
    @include('partials.breadcrumb', ['items' => [['Panier', null]]])
    <h1 class="mb-4">Mon panier</h1>

    @if ($cart['items']->isEmpty())
        <div class="panel text-center py-5">
            <p class="mb-3">Votre panier est vide.</p>
            <a class="btn btn-primary" href="{{ route('products.index') }}">Découvrir le catalogue</a>
        </div>
    @else
        <div class="row g-4">
            <section class="col-lg-8" aria-label="Articles du panier">
                @foreach ($cart['items'] as $item)
                    @php($product = $item->product)
                    <div class="panel mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-3 col-md-2">
                                <img class="thumb-md" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </div>
                            <div class="col-9 col-md-4">
                                <a class="product-name text-decoration-none" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                <div class="price">{{ $product->price_formatted }}</div>
                                @unless ($item->isAvailable())
                                    <div class="small text-danger">Stock insuffisant ({{ $product->stock }} disponible{{ $product->stock > 1 ? 's' : '' }})</div>
                                @endunless
                            </div>
                            <div class="col-7 col-md-3">
                                <form method="POST" action="{{ route('cart.update', ['product' => $product->id]) }}" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <label class="visually-hidden" for="qty-{{ $product->id }}">Quantité de {{ $product->name }}</label>
                                    <input id="qty-{{ $product->id }}" type="number" name="quantity" min="0" max="{{ config('shop.max_quantity_per_line') }}" value="{{ $item->quantity }}" class="form-control" style="width: 5rem">
                                    <button class="btn btn-outline-secondary" type="submit">OK</button>
                                </form>
                            </div>
                            <div class="col-5 col-md-3 text-end">
                                <div class="fw-bold mb-1">@money($item->lineTotal())</div>
                                <form method="POST" action="{{ route('cart.destroy', ['product' => $product->id]) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm p-0" type="submit">Retirer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <a class="btn btn-outline-info btn-sm" href="{{ route('products.index') }}">‹ Continuer mes achats</a>
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-link btn-sm p-0 text-danger" type="submit">Vider le panier</button>
                    </form>
                </div>
            </section>

            <aside class="col-lg-4">
                <div class="panel">
                    <h2 class="h4 mb-3">Récapitulatif</h2>
                    <div class="d-flex justify-content-between py-1"><span>Sous-total</span><span>@money($cart['subtotal'])</span></div>
                    <div class="d-flex justify-content-between py-1">
                        <span>Livraison</span>
                        <span>@if ($cart['shipping_fee'] > 0) @money($cart['shipping_fee']) @else Offerte @endif</span>
                    </div>
                    @php($missing = config('shop.free_shipping_threshold') - $cart['subtotal'])
                    @if ($missing > 0)
                        <div class="alert alert-success py-2 small my-3">Plus que @money($missing) pour la livraison offerte.</div>
                    @endif
                    <div class="d-flex justify-content-between border-top border-2 border-info mt-3 pt-3 fs-5 fw-bold">
                        <span>Total</span><span>@money($cart['total'])</span>
                    </div>
                    <a class="btn btn-primary btn-lg w-100 mt-3" href="{{ route('checkout.create') }}">Commander</a>
                </div>
            </aside>
        </div>
    @endif
</div>
@endsection
