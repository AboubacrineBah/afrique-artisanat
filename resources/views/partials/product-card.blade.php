{{-- Carte produit. Variable attendue : $product (relation "images" chargée pour éviter les requêtes en boucle). --}}
<div class="col">
    <article class="card card-product h-100 p-2">
        <img class="card-img-top" src="{{ $product->image_url }}"
            alt="{{ $product->images->first()?->alt ?? $product->name }}" loading="lazy">
        <div class="card-body px-1 pb-1">
            <h3 class="product-name mb-1">
                <a class="text-reset text-decoration-none stretched-link"
                    href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
            </h3>
            <p class="price mb-1">{{ $product->price_formatted }}</p>
            @unless ($product->in_stock)
                <span class="badge text-bg-danger">Rupture de stock</span>
            @endunless
        </div>
    </article>
</div>