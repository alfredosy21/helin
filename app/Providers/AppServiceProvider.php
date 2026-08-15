<?php

namespace App\Providers;

use App\Models\PageSeo;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('minAsset', function ($expression) {
            return "<?php \$__minPath = $expression; \$__minExt = pathinfo(\$__minPath, PATHINFO_EXTENSION); \$__minPath = app()->environment('production') ? preg_replace('/\.' . \$__minExt . '$/', '.min.' . \$__minExt, \$__minPath) : \$__minPath; echo asset(\$__minPath); ?>";
        });

        View::composer('web.*', function ($view) {
            $pageSlug = match (request()->route()?->getName()) {
                'home' => 'home',
                'catalogo' => 'catalogo',
                'producto' => 'producto',
                'solicitud' => 'solicitud',
                'solicitud-enviada' => 'solicitud-enviada',
                'contactanos' => 'contactanos',
                'nuestra-empresa' => 'nuestra-empresa',
                'politicas' => 'politicas',
                'recursos-clinicos' => 'recursos-clinicos',
                'caso-clinico' => 'caso-clinico',
                default => null,
            };

            $view->with('pageSeo', $pageSlug ? PageSeo::where('page_slug', $pageSlug)->first() : null);
        });
    }
}
