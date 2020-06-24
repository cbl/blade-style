<?php

namespace BladeStyle;

use BladeStyle\Factory;
use BladeStyle\Compiler\CssCompiler;
use Illuminate\Support\Facades\Blade;
use BladeStyle\Engines\CompilerEngine;
use BladeStyle\Engines\EngineResolver;
use BladeStyle\Engines\MinifierEngine;
use BladeStyle\Minifier\MullieMinifier;
use BladeStyle\Components\StyleComponent;
use BladeStyle\Commands\StyleCacheCommand;
use BladeStyle\Commands\StyleClearCommand;
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

        $this->registerMinifier();

        $this->registerMinifierEngine();

        $this->registerCssCompiler();

        $this->registerEngineResolver();

        $this->registerStyleClearCommand();

        $this->registerStyleCacheCommand();

        $this->registerFactory();

        $this->registerBladeComponents();

        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Register blade components.
     *
     * @return void
     */
    public function registerBladeComponents()
    {
        Blade::component('style', StyleComponent::class);
        Blade::component('styles', StylesComponent::class);
    }

    /**
     * Register minifier.
     *
     * @return void
     */
    protected function registerMinifier()
    {
        $this->app->singleton('style.minifier.mullie', function ($app) {
            return new MullieMinifier;
        });
    }

    /**
     * Register minifier engine.
     *
     * @return void
     */
    protected function registerMinifierEngine()
    {
        $this->app->singleton('style.engine.minifier', function ($app) {
            return new MinifierEngine($app['style.minifier.mullie']);
        });
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
                $this->app['style.engine.minifier'],
                $this->app['files'],
                $this->app['config']['style.compiled'],
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
     * Register the command.
     *
     * @return void
     */
    protected function registerStyleClearCommand()
    {
        $this->app->singleton('command.style.clear', function ($app) {
            return new StyleClearCommand($app['files']);
        });

        $this->commands(['command.style.clear']);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerStyleCacheCommand()
    {
        $this->app->singleton('command.style.cache', function ($app) {
            return new StyleCacheCommand($app['style.factory']);
        });

        $this->commands(['command.style.cache']);
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
