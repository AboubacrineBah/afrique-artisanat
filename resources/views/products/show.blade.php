@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', \Illuminate\Support\Str::limit($product->description, 150))

@section('content')
<div class="container pb-5">
    @include('partials.breadcrumb', [
        'items' => [
            ['Catalogue', route('products.index')],
            [$product->category->name, route('categories.show', $product->category)],
            [$product->name, null],
        ]
    ])

    <div class="row g-5 mt-1">
        <div class="col-lg-6">
            @php($images = $product->images)
            @if ($images->count() > 1)
                <div id="gallery" class="carousel slide" aria-label="Photos du produit">
                    <div class="carousel-inner rounded-3 overflow-hidden">
                        @foreach ($images as $image)
                            <div class="carousel-item @if ($loop->first) active @endif">
                                <img class="d-block w-100 gallery-img" src="{{ $image->url }}"
                                    alt="{{ $image->alt ?? $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#gallery" data-bs-slide="prev"><span
                            class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Photo
                            précédente</span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#gallery" data-bs-slide="next"><span
                            class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Photo
                            suivante</span></button>
                </div>
            @else
                <img class="img-fluid rounded-3 w-100 gallery-img" src="{{ $product->image_url }}"
                    alt="{{ $images->first()?->alt ?? $product->name }}">
            @endif
        </div>

        <div class="col-lg-6">
            @if ($product->artisan)
                <a class="fw-semibold"
                    href="{{ route('artisans.show', $product->artisan) }}">{{ $product->artisan->name }}</a>
            @endif
            <h1 class="mt-1 mb-2">{{ $product->name }}</h1>
            <p class="price fs-2 mb-2">{{ $product->price_formatted }}</p>

            @if (!$product->in_stock)
                <p class="text-danger fw-semibold">Rupture de stock</p>
            @elseif ($product->stock <= config('shop.low_stock_threshold'))
                <p class="text-warning-emphasis fw-semibold">Plus que {{ $product->stock }} en stock</p>
            @else
                <p class="text-success fw-semibold">En stock</p>
            @endif

            <p>{{ $product->description }}</p>

            <dl class="row">
                @if ($product->material)
                    <dt class="col-4">Matière</dt>
                <dd class="col-8">{{ $product->material }}</dd>@endif
                @if ($product->origin)
                    <dt class="col-4">Origine</dt>
                <dd class="col-8">{{ $product->origin }}</dd>@endif
                <dt class="col-4">Catégorie</dt>
                <dd class="col-8"><a
                        href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
                </dd>
            </dl>

            @if ($product->in_stock)
                <form method="POST" action="{{ route('cart.store') }}" class="d-flex flex-wrap align-items-end gap-3 my-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label for="quantity" class="form-label fw-semibold">Quantité</label>
                        <input id="quantity" name="quantity" type="number" class="form-control" style="width: 6rem" min="1"
                            max="{{ min($product->stock, config('shop.max_quantity_per_line')) }}" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-bag-plus" aria-hidden="true"></i> Ajouter au panier
                    </button>
                </form>
            @endif

            <div class="panel small">
                Livraison <strong>@money(config('shop.shipping_fee'))</strong>, offerte dès
                <strong>@money(config('shop.free_shipping_threshold'))</strong>.
                Paiement à la livraison ou Mobile Money (démo).
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-5" aria-labelledby="titre-similaires">
            <h2 id="titre-similaires" class="mb-3">Produits similaires</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @foreach ($related as $item)
                    @include('partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection