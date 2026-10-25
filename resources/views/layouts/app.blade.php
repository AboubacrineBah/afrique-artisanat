<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') — @endif{{ config('shop.name') }}</title>
    <meta name="description"
        content="@yield('meta_description', 'Boutique en ligne d’artisanat d’Afrique de l’Ouest : textiles, bijoux, sculptures, maroquinerie, poterie et décoration. Site de démonstration.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="d-flex flex-column min-vh-100">
    <a class="visually-hidden-focusable position-absolute p-2 bg-white" href="#contenu">Aller au contenu</a>

    @include('partials.header')

    <main id="contenu" class="flex-grow-1">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>

</html>