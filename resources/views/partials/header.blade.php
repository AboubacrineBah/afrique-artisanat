<div class="demo-bar sticky-top">Annonce : aucune vente réelle</div>
<header class="bg-white border-bottom">
    <nav class="navbar navbar-expand-lg py-3" aria-label="Navigation principale">
        <div class="container">
            <a class="navbar-brand brand d-flex align-items-center gap-2 me-lg-4" href="{{ route('home') }}">
                @include('partials.logo') Afrique Artisanat
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav"
                aria-controls="main-nav" aria-expanded="false" aria-label="Afficher le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main-nav">
                <form class="flex-grow-1 my-3 my-lg-0 me-lg-4" role="search" action="{{ route('products.index') }}"
                    method="GET">
                    <label class="visually-hidden" for="header-search">Rechercher un produit</label>
                    <input id="header-search" class="form-control" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Rechercher un produit…">
                </form>

                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('home')) active @endif"
                            href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('products.*', 'categories.*')) active @endif"
                            href="{{ route('products.index') }}">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('artisans.*')) active @endif"
                            href="{{ route('artisans.index') }}">Artisans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('contact.*')) active @endif"
                            href="{{ route('contact.create') }}">Contact</a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person" aria-hidden="true"></i>
                                {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.orders.index') }}">Mes commandes</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.profile.edit') }}">Mon profil</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.addresses.index') }}">Mes adresses</a>
                                </li>
                                @if (auth()->user()->isAdmin())
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Administration</a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-info btn-sm @if (request()->routeIs('login')) active @endif"
                                href="{{ route('login') }}">
                                <i class="bi" aria-hidden="true"></i> Connexion
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag" aria-hidden="true"></i> Panier
                            <span class="badge text-bg-light ms-1">{{ $cartCount ?? 0 }}</span>
                            <span class="visually-hidden">article(s)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>