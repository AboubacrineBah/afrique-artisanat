{{-- Fil d'Ariane. Variable : $items = [['Libellé', url|null], ...]. Le dernier élément est la page courante. --}}
<nav aria-label="Fil d’Ariane" class="pt-4 pb-2">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Accueil</a>
        </li>
        @foreach ($items as [$label, $url])
            @if ($loop->last || !$url)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $url }}">{{ $label }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>