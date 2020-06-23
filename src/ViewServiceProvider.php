<?php

namespace BladeStyle;

use Illuminate\Support\ServiceProvider;
use BladeStyle\Engines\BladeCompilerEngine;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->booted(function () {
            $this->registerStyleEngine($this->app['view.engine.resolver']);
        });
    }
    /**
     * Register the Style engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerStyleEngine($resolver)
    {
        $resolver->register('blade', function () {
            return new BladeCompilerEngine($this->app['blade.compiler']);
        });
    }
}
