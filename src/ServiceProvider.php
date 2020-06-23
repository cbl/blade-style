<?php

namespace BladeStyle;

use BladeStyle\Factory;
use BladeStyle\Compiler\CssCompiler;
use BladeStyle\Compiler\SassCompiler;
use Illuminate\Support\Facades\Blade;
use BladeStyle\Engines\CompilerEngine;
use BladeStyle\Engines\EngineResolver;
use BladeStyle\Components\StyleComponent;
use BladeStyle\Components\StylesComponent;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishes();

        $this->registerCssCompiler();

        $this->registerEngineResolver();

        $this->registerFactory();

        $this->blade();

        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Extend blade.
     *
     * @return void
     */
    public function blade()
    {
        Blade::component('style', StyleComponent::class);
        Blade::component('styles', StylesComponent::class);
    }

    /**
     * Register style engine resolver.
     *
     * @return void
     */
    protected function registerEngineResolver()
    {
        $this->app->singleton('style.engine.resolver', function ($app) {
            $resolver = new EngineResolver;

            $this->registerCssEngine($resolver);

            return $resolver;
        });
    }

    /**
     * Register style factory.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('style.factory', function ($app) {
            $resolver = $app['style.engine.resolver'];

            return new Factory($resolver);
        });

        $this->app->alias('style.factory', Factory::class);
    }

    /**
     * Register css compiler.
     *
     * @return void
     */
    protected function registerCssCompiler()
    {
        $this->app->singleton('style.compiler.css', function () {
            return new CssCompiler(
                $this->app['files'],
                $this->app['config']['style.compiled']
            );
        });
    }

    /**
     * Register css compiler.
     *
     * @param \BladeStyle\Engines\EngineResolver $resolver
     * @return void
     */
    protected function registerCssEngine($resolver)
    {
        $resolver->register('css', function () {
            return new CompilerEngine($this->app['style.compiler.css']);
        });
    }

    /**
     * Register publishes.
     *
     * @return void
     */
    public function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../storage/' => storage_path('framework/styles')
        ], 'storage');

        $this->publishes([
            __DIR__ . '/../config/style.php' => config_path('style.php')
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/style.php',
            'style'
        );
    }
}
