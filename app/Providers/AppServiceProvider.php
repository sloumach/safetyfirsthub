<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {




    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager la variable dans toutes les vues
        View::composer('navbar', function ($view) {
            $cartCount = session('cart') ? count(session('cart')) : 0;
            $view->with('cartCount', $cartCount);
        });
    }
}
