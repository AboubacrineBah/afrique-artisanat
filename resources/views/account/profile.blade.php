@extends('layouts.account')

@section('title', 'Mon profil')
@section('heading', 'Mon profil')

@section('account')
<form method="POST" action="{{ route('account.profile.update') }}" class="panel">
    @csrf @method('PUT')
    <h2 class="h4 mb-3">Informations</h2>
    <div class="row">
        <x-input class="col-md-6" name="name" label="Nom" :value="$user->name" required />
        <x-input class="col-md-6" name="email" type="email" label="Adresse e-mail" :value="$user->email" required />
    </div>
    <x-input name="phone" type="tel" label="Téléphone" :value="$user->phone" />

    <h2 class="h4 mt-4 mb-3">Changer le mot de passe</h2>
    <p class="small">Laissez ces champs vides pour conserver votre mot de passe actuel.</p>
    <div class="row">
        <x-input class="col-md-4" name="current_password" type="password" label="Mot de passe actuel" autocomplete="current-password" />
        <x-input class="col-md-4" name="password" type="password" label="Nouveau mot de passe" autocomplete="new-password" />
        <x-input class="col-md-4" name="password_confirmation" type="password" label="Confirmation" autocomplete="new-password" />
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
@endsection
