<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@hasSection('title')@yield('title') — @endif Administration · {{ config('shop.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
    @php
        $menu = [
            ['admin.dashboard', 'Tableau de bord', 'bi-speedometer2', 'admin.dashboard'],
            ['admin.products.index', 'Produits', 'bi-box-seam', 'admin.products.*'],
            ['admin.categories.index', 'Catégories', 'bi-tags', 'admin.categories.*'],
            ['admin.artisans.index', 'Artisans', 'bi-person-workspace', 'admin.artisans.*'],
            ['admin.orders.index', 'Commandes', 'bi-receipt', 'admin.orders.*'],
            ['admin.customers.index', 'Clients', 'bi-people', 'admin.customers.*'],
            ['admin.messages.index', 'Messages', 'bi-envelope', 'admin.messages.*'],
        ];
    @endphp
    <div class="d-lg-flex min-vh-100">
        <aside class="admin-sidebar flex-shrink-0 p-3">
            <a class="brand text-white d-flex align-items-center gap-2 mb-3 text-decoration-none fs-5"
                href="{{ route('admin.dashboard') }}">
                @include('partials.logo', ['inverse' => true, 'size' => 24]) Administration
            </a>
            <nav class="nav flex-lg-column" aria-label="Administration">
                @foreach ($menu as [$route, $label, $icon, $pattern])
                    <a class="nav-link @if (request()->routeIs($pattern)) active @endif" href="{{ route($route) }}">
                        <i class="bi {{ $icon }} me-2" aria-hidden="true"></i>{{ $label }}
                    </a>
                @endforeach
                <a class="nav-link mt-lg-3" href="{{ route('home') }}" target="_blank" rel="noopener"><i
                        class="bi bi-box-arrow-up-right me-2" aria-hidden="true"></i>Voir le site</a>
            </nav>
        </aside>

        <div class="flex-grow-1 p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <h1 class="h2 mb-0">@yield('heading')</h1>
                <div class="d-flex align-items-center gap-3">
                    @yield('actions')
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <span class="fw-bold">{{ auth()->user()->name }} |</span>
                        <button type="submit" class="btn btn-danger btn-sm align-baseline">Déconnexion</button>
                    </form>
                </div>
            </div>

            @foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $type)
                @if (session($key))
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>

</html>