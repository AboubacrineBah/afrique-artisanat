{{-- Pages légales : menu latéral + contenu. Sections : title, content. --}}
@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        @include('partials.breadcrumb', ['items' => [[trim($__env->yieldContent('title')), null]]])
        <div class="row g-4 mt-1">
            <aside class="col-lg-3">
                <nav class="list-group" aria-label="Pages légales">
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('pages.legal')) active @endif"
                        href="{{ route('pages.legal') }}">Mentions légales</a>
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('pages.terms')) active @endif"
                        href="{{ route('pages.terms') }}">Conditions générales de vente</a>
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('pages.privacy')) active @endif"
                        href="{{ route('pages.privacy') }}">Politique de confidentialité</a>
                </nav>
            </aside>
            <article class="col-lg-9" style="max-width: 48rem">
                <h1 class="mb-3">@yield('title')</h1>
                <p><strong>Annonce.</strong> Contenu à titre de modèle : aucune vente réelle.</p>
                @yield('legal')
            </article>
        </div>
    </div>
@endsection