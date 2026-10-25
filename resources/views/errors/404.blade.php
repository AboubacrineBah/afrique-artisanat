@extends('layouts.app')
@section('title', 'Page introuvable')
@section('content')
<div class="container py-5 text-center">
    <p class="eyebrow">Erreur 404</p>
    <h1 class="mb-3">Page introuvable</h1>
    <p class="mb-4">La page que vous cherchez n’existe pas ou a été déplacée.</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Retour à l’accueil</a>
</div>
@endsection
