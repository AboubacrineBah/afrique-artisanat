@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
@php($hero = $featuredProducts->first())
<section class="container hero py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <p class="eyebrow mb-2">Artisanat d’Afrique de l’Ouest</p>
            <h1 class="mb-3">Des créations faites main, de l’atelier jusqu’à chez vous</h1>
            <p class="lead">Textiles, bijoux, sculptures, maroquinerie : chaque pièce est rattachée à son artisan et à
                son origine.</p>
            <a class="btn btn-primary btn-lg me-3" href="{{ route('products.index') }}">Découvrir le catalogue</a>
            <a class="fw-semibold" href="{{ route('artisans.index') }}">Rencontrer les artisans</a>
        </div>
        <div class="col-lg-6">
            <img class="img-fluid rounded-3 w-100 hero-img shadow-sm"
                src="{{ asset('images/tiseran.jpg') ?? $hero?->image_url }}"
                alt="{{ $hero?->name ?? 'Artisanat africain' }}">
        </div>
    </div>
</section>

<section class="container py-4" aria-labelledby="titre-categories">
    <h2 id="titre-categories" class="mb-3">Nos catégories</h2>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
        @foreach ($categories as $category)
            <div class="col">
                <a class="cat-tile h-100" href="{{ route('categories.show', $category) }}">
                    {{ $category->name }}
                    <small>{{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}</small>
                </a>
            </div>
        @endforeach
    </div>
</section>

<section class="container py-4" aria-labelledby="titre-vedettes">
    <h2 id="titre-vedettes" class="mb-3">Produits vedettes</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        @forelse ($featuredProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <p class="col text-muted">Aucun produit en vedette pour le moment.</p>
        @endforelse
    </div>
</section>

<section class="container py-4" aria-labelledby="titre-nouveautes">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 id="titre-nouveautes" class="mb-0">Nouveautés</h2>
        <a href="{{ route('products.index') }}" class="fw-semibold">Voir tout le catalogue</a>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        @foreach ($newProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

@if ($artisans->isNotEmpty())
    <section class="container py-4" aria-labelledby="titre-artisans">
        <h2 id="titre-artisans" class="mb-3">Rencontrez nos artisans</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($artisans as $artisan)
                <div class="col">
                    <a class="panel d-block h-100 text-decoration-none" href="{{ route('artisans.show', $artisan) }}">
                        <h3 class="h5">{{ $artisan->name }}</h3>
                        <p class="eyebrow small mb-2">{{ $artisan->region }}</p>
                        <p class="mb-0 text-body">{{ \Illuminate\Support\Str::limit($artisan->bio, 110) }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif

<section class="container py-4">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="value-tile"><strong>Fait main</strong>Des pièces uniques, façonnées en atelier.</div>
        </div>
        <div class="col-md-4">
            <div class="value-tile"><strong>Artisan identifié</strong>Chaque produit renvoie à son artisan.</div>
        </div>
        <div class="col-md-4">
            <div class="value-tile"><strong>Paiement simple</strong>À la livraison ou Mobile Money (démo).</div>
        </div>
    </div>
</section>
@endsection