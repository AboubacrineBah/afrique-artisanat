@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="container py-5">
    <form method="POST" action="{{ route('login.store') }}" class="panel mx-auto" style="max-width: 32rem">
        @csrf
        <h1 class="h2 mb-2">Connexion</h1>
        <p>Connectez-vous pour passer commande.</p>

        <x-input name="email" type="email" label="Adresse e-mail" autocomplete="email" required autofocus />
        <x-input name="password" type="password" label="Mot de passe" autocomplete="current-password" required />
        <x-check name="remember" label="Se souvenir de moi" />

        <button type="submit" class="btn btn-primary btn-lg w-100">Se connecter</button>
        <p class="text-center mt-3 mb-0">Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
    </form>
</div>
@endsection
