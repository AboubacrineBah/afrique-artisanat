@extends('layouts.admin')

@section('title', 'Produits')
@section('heading', 'Produits')

@section('content')
    <a class="btn btn-primary btn-sm mb-4" href="{{ route('admin.products.create') }}"><i class="bi bi-plus-lg"
            aria-hidden="true"></i> Nouveau produit</a>
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4"><label class="visually-hidden" for="q">Rechercher</label><input id="q" class="form-control"
                type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit…"></div>
        <div class="col-md-3"><label class="visually-hidden" for="category">Catégorie</label>
            <select id="category" name="category" class="form-select">
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}
                </option>@endforeach
            </select>
        </div>
        <div class="col-md-2"><label class="visually-hidden" for="status">Statut</label>
            <select id="status" name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="active" @selected(request('status') === 'active')>Actifs</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactifs</option>
            </select>
        </div>
        <div class="col-md-2"><label class="visually-hidden" for="stock">Stock</label>
            <select id="stock" name="stock" class="form-select">
                <option value="">Tout le stock</option>
                <option value="low" @selected(request('stock') === 'low')>Stock faible</option>
            </select>
        </div>
        <div class="col-md-1"><button class="btn btn-outline-secondary w-100" type="submit">OK</button></div>
    </form>

    <div class="panel p-2">
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th class="text-end">Prix</th>
                        <th class="text-end">Stock</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><img class="thumb-sm" src="{{ $product->image_url }}" alt=""></td>
                            <td><a class="fw-semibold"
                                    href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>@if ($product->is_featured)
                                    <i class="bi bi-star-fill text-warning" title="En vedette"></i>@endif</td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-end">{{ $product->price_formatted }}</td>
                            <td class="text-end"><span
                                    class="badge {{ $product->stock === 0 ? 'text-bg-danger' : ($product->stock <= config('shop.low_stock_threshold') ? 'text-bg-warning' : 'text-bg-light border') }}">{{ $product->stock }}</span>
                            </td>
                            <td><span
                                    class="badge {{ $product->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $product->is_active ? 'Actif' : 'Inactif' }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('admin.products.edit', $product) }}">Modifier</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Aucun produit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $products->links() }}</div>
@endsection