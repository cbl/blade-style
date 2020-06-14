<?php

namespace BladeStyle;

use BladeStyle\Compiler\CssCompiler;
use BladeStyle\RouteServiceProvider;
use Illuminate\Support\Facades\View;
use BladeStyle\Compiler\LessCompiler;
use BladeStyle\Compiler\SassCompiler;
use Illuminate\Support\Facades\Blade;
use BladeStyle\Directives\BladeStyles;
use BladeStyle\Directives\WatchStyles;
use BladeStyle\Commands\InstallCommand;
use BladeStyle\Compiler\StylusCompiler;
use Illuminate\Support\Facades\Artisan;
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
        $this->app->register(RouteServiceProvider::class);
        $this->registerSingletons();
        $this->registerPublishes();
        $this->registerCompiler();
        $this->registerCommands();

        $this->blade();

        View::addNamespace('blade-style', __DIR__ . "/../views");
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Automaticly publish storage.
        Artisan::call('vendor:publish', [
            '--tag' => 'storage'
        ]);
    }

    /**
     * Extend blade.
     *
     * @return void
     */
    public function blade()
    {
        Blade::component('style', StyleComponent::class);
        Blade::directive('bladeStyles', function ($expression) {
            return (new BladeStyles)->compile($expression);
        });
        Blade::directive('watchStyles', function ($expression) {
            return (new WatchStyles)->compile($expression);
        });
    }

    /**
     * Register artisan commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            InstallCommand::class
        ]);
    }

    /**
     * Register singletons.
     *
     * @return void
     */
    public function registerSingletons()
    {
        $this->app->singleton('blade.style.compiler', StyleCompiler::class);
        $this->app->singleton('blade.style', function () {
            return new StyleLoader($this->app['blade.style.compiler']);
        });
    }

    /**
     * Register style compiler.
     *
     * @return void
     */
    public function registerCompiler()
    {
        $compiler = $this->app['blade.style.compiler'];
        $compiler->registerCompiler('css', new CssCompiler);
        $compiler->registerCompiler('sass', new SassCompiler);
        $compiler->registerCompiler('scss', new SassCompiler);
        $compiler->registerCompiler('less', new LessCompiler);
        $compiler->registerCompiler('stylus', new StylusCompiler);
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
