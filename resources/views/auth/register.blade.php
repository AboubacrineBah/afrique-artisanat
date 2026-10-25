@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="container py-5">
    <form method="POST" action="{{ route('register.store') }}" class="panel mx-auto" style="max-width: 32rem">
        @csrf
        <h1 class="h2 mb-3">Créer un compte</h1>

        <x-input name="name" label="Nom complet" autocomplete="name" required autofocus />
        <x-input name="email" type="email" label="Adresse e-mail" autocomplete="email" required />
        <x-input name="phone" type="tel" label="Téléphone (facultatif)" autocomplete="tel" />
        <x-input name="password" type="password" label="Mot de passe" autocomplete="new-password" help="8 caractères minimum." required />
        <x-input name="password_confirmation" type="password" label="Confirmer le mot de passe" autocomplete="new-password" required />

        <button type="submit" class="btn btn-primary btn-lg w-100">Créer mon compte</button>
        <p class="text-center mt-3 mb-0">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
    </form>
</div>
@endsection
