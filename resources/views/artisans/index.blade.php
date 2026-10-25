@extends('layouts.app')

@section('title', 'Nos artisans')

@section('content')
<div class="container pb-5">
    @include('partials.breadcrumb', ['items' => [['Artisans', null]]])
    <h1 class="mb-2">Nos artisans</h1>
    <p class="mb-4" style="max-width: 40rem">Chaque produit est rattaché à l’atelier qui l’a façonné. Ateliers fictifs créés pour la démonstration.</p>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse ($artisans as $artisan)
            <div class="col">
                <article class="panel h-100">
                    <h2 class="h5 mb-1"><a class="text-decoration-none" href="{{ route('artisans.show', $artisan) }}">{{ $artisan->name }}</a></h2>
                    <p class="eyebrow small mb-2">{{ $artisan->region }}</p>
                    <p>{{ \Illuminate\Support\Str::limit($artisan->bio, 120) }}</p>
                    <a class="fw-semibold" href="{{ route('artisans.show', $artisan) }}">{{ $artisan->products_count }} produit{{ $artisan->products_count > 1 ? 's' : '' }}</a>
                </article>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Aucun artisan pour le moment.</div></div>
        @endforelse
    </div>
</div>
@endsection
