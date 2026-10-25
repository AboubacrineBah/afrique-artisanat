@extends('layouts.admin')

@php($editing = $product->exists)
@section('title', $editing ? 'Modifier le produit' : 'Nouveau produit')
@section('heading', $editing ? 'Modifier le produit' : 'Nouveau produit')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="panel">
                <h2 class="h5 mb-3">Informations</h2>
                <x-input name="name" label="Nom" :value="$product->name" required />
                <x-input name="slug" label="Identifiant d’URL (facultatif)" :value="$product->slug" help="Laissez vide pour le générer automatiquement à partir du nom." />
                <div class="row">
                    <div class="col-md-6"><x-select name="category_id" label="Catégorie" :options="$categories->pluck('name', 'id')" :value="$product->category_id" placeholder="Choisir…" required /></div>
                    <div class="col-md-6"><x-select name="artisan_id" label="Artisan" :options="$artisans->pluck('name', 'id')" :value="$product->artisan_id" placeholder="Aucun" /></div>
                </div>
                <x-textarea name="description" label="Description" :value="$product->description" rows="5" required />
                <div class="row">
                    <x-input class="col-md-6" name="price" type="number" min="0" step="500" label="Prix (FCFA)" :value="$product->price" required />
                    <x-input class="col-md-6" name="stock" type="number" min="0" label="Stock" :value="$product->stock ?? 0" required />
                    <x-input class="col-md-6" name="material" label="Matière" :value="$product->material" />
                    <x-input class="col-md-6" name="origin" label="Origine" :value="$product->origin" />
                </div>
                <x-check name="is_active" label="Produit actif (visible sur le site)" :checked="$product->is_active" />
                <x-check name="is_featured" label="Produit en vedette (page d’accueil)" :checked="$product->is_featured" />
            </div>
        </div>

        <div class="col-xl-5">
            <div class="panel">
                <h2 class="h5 mb-3">Ajouter des images</h2>
                <label class="form-label fw-semibold" for="images">Images (JPG, PNG, WebP · 2 Mo max · {{ config('shop.max_images_per_product') }} maximum)</label>
                <input id="images" class="form-control @if ($errors->has('images') || $errors->has('images.*')) is-invalid @endif" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
                @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                @foreach ($errors->get('images.*') as $messages)<div class="invalid-feedback d-block">{{ $messages[0] }}</div>@endforeach
                <p class="form-text">La première image ajoutée devient l’image principale.</p>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-3 align-items-center">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.products.index') }}">Annuler</a>
    </div>
</form>

@if ($editing && $product->images->isNotEmpty())
    <div class="panel mt-4">
        <h2 class="h5 mb-3">Images du produit</h2>
        <div class="row row-cols-2 row-cols-md-4 row-cols-xl-6 g-3">
            @foreach ($product->images as $image)
                <div class="col">
                    <img class="thumb-md mb-2" src="{{ $image->url }}" alt="{{ $image->alt ?? $product->name }}">
                    @if ($image->is_primary)
                        <span class="badge text-bg-success">Principale</span>
                    @else
                        <form method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-link btn-sm p-0" type="submit">Définir principale</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" onsubmit="return confirm('Supprimer cette image ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-link btn-sm p-0 text-danger" type="submit">Supprimer</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
