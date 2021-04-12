<?php

namespace Modules\Api\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Api\Repositories\ApiShopRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\Eloquent\Cached\CachedEloquentAreaRepository;
use Modules\Api\Repositories\Eloquent\EloquentApiShopRepository;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Api', 'Database/Migrations'));
        $this->registerRepository();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Api', 'Config/config.php') => config_path('api.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Api', 'Config/config.php'), 'api'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/api');

        $sourcePath = module_path('Api', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/api';
        }, Config::get('view.paths')), [$sourcePath]), 'api');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/api');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'api');
        } else {
            $this->loadTranslationsFrom(module_path('Api', 'Resources/lang'), 'api');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    public function registerRepository()
    {
        $this->app->bind(
            AreaRepository::class,
            function () {
                return new CachedEloquentAreaRepository();
            }
        );
	    $this->app->bind(
		    ApiShopRepository::class,
		    function () {
			    return new EloquentApiShopRepository();
		    }
	    );
    }
}
