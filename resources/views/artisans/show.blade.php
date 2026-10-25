@extends('layouts.app')

@section('title', $artisan->name)

@section('content')
<div class="container pb-5">
    @include('partials.breadcrumb', ['items' => [['Artisans', route('artisans.index')], [$artisan->name, null]]])

    <section class="row align-items-center g-4 my-3">
        <div class="col-md-5">
            <img class="img-fluid rounded-3 w-100 hero-img" src="{{ $artisan->photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($artisan->photo_path) : asset('images/placeholder.svg') }}" alt="Atelier : {{ $artisan->name }}">
        </div>
        <div class="col-md-7">
            @if ($artisan->region)<p class="eyebrow mb-1">{{ $artisan->region }}</p>@endif
            <h1 class="mb-3">{{ $artisan->name }}</h1>
            <p>{{ $artisan->bio }}</p>
        </div>
    </section>

    <h2 class="mt-4 mb-3">Produits de l’atelier</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Cet atelier n’a pas encore de produit en vente.</div></div>
        @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
</div>
@endsection
