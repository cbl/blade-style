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

    protected function registerEngineResolver()
    {
        $this->app->singleton('style.engine.resolver', function ($app) {
            $resolver = new EngineResolver;

            foreach (['css', 'sass'] as $compiler) {
                $this->{'register' . ucfirst($compiler) . 'Compiler'}($resolver);
            }

            return $resolver;
        });
    }

    public function registerFactory()
    {
        $this->app->singleton('style.factory', function ($app) {
            $resolver = $app['style.engine.resolver'];

            return new Factory($resolver);
        });

        $this->app->alias('style.factory', Factory::class);
    }

    protected function registerCssCompiler($resolver)
    {
        $resolver->register('css', function () {
            $compiler = new CssCompiler($this->app['files'], $this->getCompiledPath());

            return new CompilerEngine($compiler);
        });
    }

    protected function registerSassCompiler($resolver)
    {
        $resolver->register('sass', function () {
            $compiler = new SassCompiler($this->app['files'], $this->getCompiledPath());

            return new CompilerEngine($compiler);
        });
    }

    public function getCompiledPath()
    {
        return storage_path('framework/styles');
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
    }
}
