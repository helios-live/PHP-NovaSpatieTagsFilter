<?php

namespace HeliosLive\PhpNovaSpatieTagsFilter;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('php-nova-spatie-tags-filter', __DIR__ . '/../dist/js/filter.js');
            Nova::style('php-nova-spatie-tags-filter', __DIR__ . '/../dist/css/filter.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/php-nova-spatie-tags-filter')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
