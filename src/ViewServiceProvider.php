<?php

namespace BladeStyle;

use Illuminate\Support\ServiceProvider;
use Livewire\LivewireViewCompilerEngine;
use BladeStyle\Engines\StyleViewCompilerEngine;
use BladeStyle\Engines\StyleLivewireViewCompilerEngine;

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
            $this->registerViewCompilerEngine($this->app['view.engine.resolver']);
        });
    }

    /**
     * Register the style compiler engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerViewCompilerEngine($resolver)
    {
        if (class_exists(LivewireViewCompilerEngine::class)) {
            return $this->registerLivewireViewCompilerEngine($resolver);
        }

        $resolver->register('blade', function () {
            return new StyleViewCompilerEngine($this->app['blade.compiler']);
        });
    }

    /**
     * Register the style compiler engine implementation for livewire.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerLivewireViewCompilerEngine($resolver)
    {
        $resolver->register('blade', function () {
            return new StyleLivewireViewCompilerEngine($this->app['blade.compiler']);
        });
    }
}
