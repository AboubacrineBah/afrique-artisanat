@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <div class="container pb-5">
        @include('partials.breadcrumb', ['items' => [['Contact', null]]])
        <h1 class="mb-4">Contact</h1>

        <div class="row g-4">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('contact.store') }}" class="panel">
                    @csrf
                    {{-- Champ piège anti-robot : invisible pour les humains --}}
                    <div style="position:absolute;left:-9999px" aria-hidden="true">
                        <label for="website">Ne pas remplir</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="row">
                        <x-input class="col-md-6" name="name" label="Nom" :value="auth()->user()?->name" required />
                        <x-input class="col-md-6" name="email" type="email" label="Adresse e-mail"
                            :value="auth()->user()?->email" required />
                    </div>
                    <x-input name="subject" label="Objet" required />
                    <x-textarea name="message" label="Message" rows="6" required />
                    <button type="submit" class="btn btn-primary btn-lg">Envoyer le message</button>
                </form>
            </div>
            <aside class="col-lg-4">
                <div class="panel">
                    <h2 class="h4">Nous trouver</h2>
                    <p>Boutique fictive basée à Bamako (Mali).</p>
                    <p>E-mail : {{ config('shop.contact_email') }}<br>
                       WhatsApp : +223 82 89 09 92
                    </p>
                    <p class="small mb-0">Annonce : aucune vente réelle.</p>
                </div>
            </aside>
        </div>
    </div>
@endsection