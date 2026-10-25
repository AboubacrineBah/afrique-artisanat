@extends('layouts.admin')

@php($editing = $category->exists)
@section('title', $editing ? 'Modifier la catégorie' : 'Nouvelle catégorie')
@section('heading', $editing ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')
<form method="POST" enctype="multipart/form-data" class="panel" style="max-width: 40rem"
      action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <x-input name="name" label="Nom" :value="$category->name" required />
    <x-input name="slug" label="Identifiant d’URL (facultatif)" :value="$category->slug" help="Laissez vide pour le générer automatiquement." />
    <x-textarea name="description" label="Description" :value="$category->description" rows="3" />

    <div class="mb-3">
        <label class="form-label fw-semibold" for="image">Image (facultatif)</label>
        @if ($category->image_path)<div class="mb-2"><img class="thumb-sm" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image_path) }}" alt="Image actuelle"></div>@endif
        <input id="image" class="form-control @error('image') is-invalid @enderror" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <x-check name="is_active" label="Catégorie active (visible sur le site)" :checked="$category->is_active" />

    <div class="d-flex gap-3 align-items-center">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.categories.index') }}">Annuler</a>
    </div>
</form>
@endsection
