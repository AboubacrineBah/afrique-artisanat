{{-- Espace client : fil d'Ariane, titre et menu latéral. Sections : title, heading, content. --}}
@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        @include('partials.breadcrumb', ['items' => [['Mon compte', route('account.orders.index')], [trim($__env->yieldContent('title')), null]]])
        <h1 class="mb-4">@yield('heading')</h1>

        <div class="row g-4">
            <aside class="col-lg-3">
                <nav class="list-group" aria-label="Mon compte">
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('account.orders.*')) active @endif"
                        href="{{ route('account.orders.index') }}">Mes commandes</a>
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('account.profile.*')) active @endif"
                        href="{{ route('account.profile.edit') }}">Mon profil</a>
                    <a class="list-group-item list-group-item-action @if (request()->routeIs('account.addresses.*')) active @endif"
                        href="{{ route('account.addresses.index') }}">Mes adresses</a>
                </nav>
            </aside>
            <section class="col-lg-9">
                @yield('account')
            </section>
        </div>
    </div>
@endsection