<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Admin\Console\CreateAdminUser;
use Modules\Admin\Console\GenerateAdminPermission;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        CreateAdminUser::class,
        GenerateAdminPermission::class
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
        $viewPath = resource_path('views/modules/admin');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/admin';
        }, \Config::get('view.paths')), [$sourcePath]), 'admin');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/admin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'admin');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'admin');
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
            'Modules\Admin\Repositories\CategoryRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentCategoryRepository(new \Modules\Mon\Entities\Category());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\NewsRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentNewsRepository(new \Modules\Mon\Entities\News());
                return $repository;
            }
        );

        $this->app->bind(
            'Modules\Admin\Repositories\BannerRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentBannerRepository(new \Modules\Mon\Entities\Banner());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\PartnerRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentPartnerRepository(new \Modules\Mon\Entities\Partner());
                return $repository;
            }
        );
// add bindings









    }
}
