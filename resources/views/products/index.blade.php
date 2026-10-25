@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
    <div class="container pb-5">
        @include('partials.breadcrumb', ['items' => [['Catalogue', null]]])
        <h1 class="mb-4">Catalogue</h1>

        <div class="row g-4">
            <aside class="col-lg-3">
                <form id="filter-form" class="panel" method="GET" action="{{ route('products.index') }}">
                    <h2 class="h5 mb-3">Filtres</h2>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="filter-q">Recherche</label>
                        <input id="filter-q" class="form-control" type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                            placeholder="Nom, matière…">
                    </div>

                    <fieldset class="mb-3">
                        <legend class="form-label fw-semibold fs-6">Catégorie</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="cat-all" value=""
                                @checked(empty($filters['category']))>
                            <label class="form-check-label" for="cat-all">Toutes</label>
                        </div>
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="cat-{{ $category->id }}"
                                    value="{{ $category->slug }}" @checked(($filters['category'] ?? '') === $category->slug)>
                                <label class="form-check-label" for="cat-{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </fieldset>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="min_price">Prix min.</label>
                            <input class="form-control" type="number" min="0" step="500" id="min_price" name="min_price"
                                value="{{ $filters['min_price'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="max_price">Prix max.</label>
                            <input class="form-control" type="number" min="0" step="500" id="max_price" name="max_price"
                                value="{{ $filters['max_price'] ?? '' }}">
                        </div>
                        <div class="form-text">En FCFA</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="material">Matière</label>
                        <select class="form-select" id="material" name="material">
                            <option value="">Toutes</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material }}" @selected(($filters['material'] ?? '') === $material)>
                                    {{ $material }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" value="1"
                            @checked(!empty($filters['in_stock']))>
                        <label class="form-check-label" for="in_stock">En stock seulement</label>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Filtrer</button>
                    <a class="d-block text-center mt-2 small" href="{{ route('products.index') }}">Réinitialiser</a>
                </form>
            </aside>

            <section class="col-lg-9" aria-label="Liste des produits">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <span>{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <label for="sort" class="mb-0">Trier par</label>
                        <select id="sort" name="sort" form="filter-form" class="form-select w-auto"
                            onchange="this.form.submit()">
                            @foreach ($sorts as $key => $label)
                                <option value="{{ $key }}" @selected(($filters['sort'] ?? 'newest') === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
                    @forelse ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">Aucun produit ne correspond à votre recherche.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 d-flex justify-content-end">{{ $products->links() }}</div>
            </section>
        </div>
    </div>
@endsection