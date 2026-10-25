@extends('layouts.admin')

@php($editing = $artisan->exists)
@section('title', $editing ? 'Modifier l’artisan' : 'Nouvel artisan')
@section('heading', $editing ? 'Modifier l’artisan' : 'Nouvel artisan')

@section('content')
<form method="POST" enctype="multipart/form-data" class="panel" style="max-width: 40rem"
      action="{{ $editing ? route('admin.artisans.update', $artisan) : route('admin.artisans.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <x-input name="name" label="Nom de l’atelier ou de l’artisan" :value="$artisan->name" required />
    <x-input name="slug" label="Identifiant d’URL (facultatif)" :value="$artisan->slug" help="Laissez vide pour le générer automatiquement." />
    <x-input name="region" label="Région" :value="$artisan->region" />
    <x-textarea name="bio" label="Présentation" :value="$artisan->bio" rows="5" />

    <div class="mb-3">
        <label class="form-label fw-semibold" for="photo">Photo (facultatif)</label>
        @if ($artisan->photo_path)<div class="mb-2"><img class="thumb-sm" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($artisan->photo_path) }}" alt="Photo actuelle"></div>@endif
        <input id="photo" class="form-control @error('photo') is-invalid @enderror" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-3 align-items-center">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.artisans.index') }}">Annuler</a>
    </div>
</form>
@endsection
