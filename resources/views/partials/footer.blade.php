<footer class="site-footer mt-5 py-4">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span> © {{ date('Y') }} Afrique Artisanat — site de démonstration, aucune vente réelle</span>
        <span>
            <a href="{{ route('pages.legal') }}">Mentions légales</a> ·
            <a href="{{ route('pages.terms') }}">CGV</a> ·
            <a href="{{ route('pages.privacy') }}">Confidentialité</a>
        </span>
    </div>
</footer>