@extends('layouts.app')
@section('title', 'Accès refusé')
@section('content')
<div class="container py-5 text-center">
    <p class="eyebrow">Erreur 403</p>
    <h1 class="mb-3">Accès refusé</h1>
    <p class="mb-4">{{ $exception->getMessage() ?: 'Vous n’avez pas l’autorisation d’accéder à cette page.' }}</p>
    <a class="btn btn-primary" href="{{ route('home') }}">Retour à l’accueil</a>
</div>
@endsection
