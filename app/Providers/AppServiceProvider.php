<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // utf8mb4 usa 4 bytes por carácter. Un índice sobre VARCHAR(255) pasa
        // el límite de 767 bytes de este MySQL. 191 × 4 = 764.
        Schema::defaultStringLength(191);
    }
}
