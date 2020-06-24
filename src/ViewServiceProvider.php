<?php

namespace BladeStyle;

use Illuminate\Support\ServiceProvider;
use BladeStyle\Engines\StyleCompilerEngine;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function () {
            $this->registerStyleEngine($this->app['view.engine.resolver']);
        });
    }

    /**
     * Register the style compiler engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerStyleEngine($resolver)
    {
        $resolver->register('blade', function () {
            return new StyleCompilerEngine($this->app['blade.compiler']);
        });
    }
}
