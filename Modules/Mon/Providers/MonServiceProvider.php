<?php

namespace Modules\Mon\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Mon\Auth\AccessTokenGuard;
use Modules\Mon\Entities\Profile;
use Modules\Mon\Events\LoadingTranslations;
use Modules\Mon\Http\Middleware\Admin;
use Modules\Mon\Http\Middleware\ApiPermission;
use Modules\Mon\Http\Middleware\ApiRole;
use Modules\Mon\Http\Middleware\ApiToken;
use Modules\Mon\Http\Middleware\SetLocale;
use Modules\Mon\Http\Middleware\ShopAdmin;
use Modules\Mon\Http\Middleware\ThemeViews;
use Modules\Mon\Repositories\ConnectedAccountRepository;
use Modules\Mon\Support\Settings;
use Modules\Mon\Support\Theme;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MonServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        \Modules\Mon\Console\Theme::class,
        \Modules\Mon\Console\EntityScaffoldCommand::class,
    ];

    protected $middleware = [
        'locale' => SetLocale::class,
        'admin' => Admin::class,
        'api.token' => ApiToken::class,
        'api.role' => ApiRole::class,
        'api.permission' => ApiPermission::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'shopadmin' => ShopAdmin::class,
    ];

    protected $groupMiddleware = [
        'web' => [
            SetLocale::class,
            ThemeViews::class,
        ],
        'api.token' => [
            'throttle:60,1',
            'bindings',
            SetLocale::class,
        ]
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
        $this->registerMiddleware($this->app['router']);
        $this->registerRepositories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->register(RouteServiceProvider::class);


        $this->registerThemeTranslation();

        $this->app['events']->listen(LoadingTranslations::class, function (LoadingTranslations $event) {
            $event->load('mon', Arr::dot(trans('mon::mon')));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton('settings', function () {
//            return new Settings();
//        });

        $this->app->singleton('theme', function () {
            return new Theme();
        });

        $this->app->bind(
            'Modules\Mon\Auth\Contracts\Authentication',
            function () {
                return new \Modules\Mon\Auth\Authentication('web');
            }
        );


        $this->commands($this->commands);
    }

    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $middleware) {
            $router->aliasMiddleware($name, $middleware);
        }

        foreach ($this->groupMiddleware as $group => $middlewares) {
            foreach ($middlewares as $mw) {
                $router->pushMiddlewareToGroup($group, $mw);
            }
        }
    }

    public function registerRepositories()
    {
        $this->app->bind(
            'Modules\Mon\Repositories\UserRepository',
            function () {
                return new \Modules\Mon\Repositories\Eloquent\UserRepository(new \Modules\Mon\Entities\User());
            }
        );


        $this->app->bind(
            'Modules\Mon\Repositories\RoleRepository',
            function () {
                return new \Modules\Mon\Repositories\Eloquent\RoleRepository(new Role());
            }
        );
        $this->app->bind(
            'Modules\Mon\Repositories\PermissionRepository',
            function () {
                return new \Modules\Mon\Repositories\Eloquent\PermissionRepository(new Permission());
            }
        );
        $this->app->bind(
            'Modules\Mon\Repositories\ProfileRepository',
            function () {
                return new \Modules\Mon\Repositories\Eloquent\ProfileRepository(new Profile());
            }
        );


    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('mon.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'mon'
        );

        $this->publishes([
            __DIR__ . '/../Config/user.php' => config_path('user.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/user.php', 'user'
        );
        $this->publishes([
            __DIR__ . '/../Config/locales.php' => config_path('locales.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/locales.php', 'locales'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/mon');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/mon';
        }, \Config::get('view.paths')), [$sourcePath]), 'mon');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/mon');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'mon');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'mon');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {

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
    public function registerThemeTranslation() {
        $adminTheme = 'backend';

        $frontendTheme = 'base';
        $shopTheme = 'shop';

        //Language

        $langPathShop = base_path("themes/{$shopTheme}/lang");

        $this->app['translator']->addNamespace('ch', $langPathShop);

        $langPathBackend = base_path("themes/{$adminTheme}/lang");
        $this->app['translator']->addNamespace($adminTheme, $langPathBackend);

        $langPath = base_path("themes/{$frontendTheme}/lang");
        $this->app['translator']->addNamespace($frontendTheme, $langPath);

        $this->app['events']->listen(LoadingTranslations::class, function (LoadingTranslations $event) use ($frontendTheme) {
            $event->load($frontendTheme, Arr::dot(trans($frontendTheme."::frontend")));
        });

    }
}
