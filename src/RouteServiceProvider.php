<?php

namespace BladeStyle;

use BladeStyle\StyleController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        // Route only accessible when debugging.
        if (!env('APP_DEBUG')) {
            return;
        }

        Route::get('compile-styles/{name}', StyleController::class);
    }
}
