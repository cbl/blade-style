<?php

namespace BladeStyle;

use BladeStyle\StyleHandler;
use BladeStyle\RouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
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
        $this->commands([
            InstallCommand::class
        ]);

        View::addNamespace('blade-style', __DIR__ . "/../views");
        Blade::component('style', StyleComponent::class);

        $this->publish();

        // Singletons.
        $this->app->singleton(StyleHandler::class);
        $this->app->singleton(StyleCompiler::class);

        Blade::directive('bladeStyles', function ($expression) {
            return "<?php echo app(BladeStyle\StyleHandler::class)->getStyleTags(get_defined_vars());?>";
        });

        Blade::directive('watchStyles', function ($expression) {
            $watch = File::get(__DIR__ . '/../scripts/watch.js');
            return "<div id=\"blade-style-error\" style=\"display:none;\" @onclick=\"closeBladeError()\"><div></div></div><script>{$watch}</script>";
        });
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

    public function publish()
    {
        $this->publishes([
            __DIR__ . '/../storage/' => storage_path('framework/styles')
        ], 'storage');
    }
}
