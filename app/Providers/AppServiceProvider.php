<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination avec les composants Bootstrap 5 (le rendu par défaut de Laravel utilise Tailwind)
        Paginator::useBootstrapFive();

        // @money($montant) affiche un montant entier en FCFA : 28 000 FCFA
        Blade::directive('money', fn (string $expression) => "<?php echo e(\\App\\Support\\Money::format((int) ({$expression}))); ?>");

        // Nombre d'articles du panier, disponible dans les gabarits publics (badge du menu)
        View::composer(['layouts.*', 'partials.*'], function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
