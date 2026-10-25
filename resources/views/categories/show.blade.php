@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container pb-5">
    @include('partials.breadcrumb', ['items' => [['Catalogue', route('products.index')], [$category->name, null]]])
    <h1 class="mb-2">{{ $category->name }}</h1>
    @if ($category->description)<p class="mb-4">{{ $category->description }}</p>@endif

    <form method="GET" class="d-flex align-items-center gap-2 mb-3">
        <label for="sort" class="mb-0">Trier par</label>
        <select id="sort" name="sort" class="form-select w-auto" onchange="this.form.submit()">
            @foreach ($sorts as $key => $label)
                <option value="{{ $key }}" @selected(request('sort', 'newest') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <span class="ms-auto">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</span>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="col-12"><div class="alert alert-info mb-0">Aucun produit dans cette catégorie pour le moment.</div></div>
        @endforelse
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
</div>
@endsection
