<?php

namespace App\Providers;

use App\Services\Cart;
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
        // Cart count for the shop header.
        View::composer('components.shop-layout', function ($view) {
            $view->with('cartCount', app(Cart::class)->count());
        });
    }
}
