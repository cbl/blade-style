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
        Route::get('watch-styles/{from}', StyleController::class . '@changed');
    }
}
