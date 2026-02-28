<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\InstituteLabelsComposer;

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
        // Share $isSport and $label[] with every Blade view.
        // These switch labels between "sport" and "academic" institute types.
        View::composer('*', InstituteLabelsComposer::class);
    }
}
