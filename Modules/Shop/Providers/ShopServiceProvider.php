<?php

namespace Modules\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Shop\Console\GenerateShopAdminRole;
use Modules\Shop\Console\GenerateShopPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        GenerateShopPermission::class,
        GenerateShopAdminRole::class
    ];

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
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerBindings();
        $this->commands($this->commands);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('admin.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'admin'
        );

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/shop');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/shop';
        }, \Config::get('view.paths')), [$sourcePath]), 'shop');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/shop');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'shop');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'shop');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
        }
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
    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Shop\Repositories\RoleRepository',
            function () {
                return new \Modules\Shop\Repositories\Eloquent\RoleRepository(new Role());
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\PermissionRepository',
            function () {
                return new \Modules\Shop\Repositories\Eloquent\PermissionRepository(new Permission());
            }
        );

        $this->app->bind(
            'Modules\Shop\Repositories\ShopRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentShopRepository(new \Modules\Mon\Entities\Shop());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\UserRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentUserRepository(new \Modules\Mon\Entities\User());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\ProductRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentProductRepository(new \Modules\Mon\Entities\Product());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\CompanyRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentCompanyRepository(new \Modules\Mon\Entities\Company());
                return $repository;
            }
        );
// add bindings













    }
}
