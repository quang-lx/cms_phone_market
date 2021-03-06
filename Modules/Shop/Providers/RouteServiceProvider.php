<?php

namespace Modules\Shop\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'Modules\Shop\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $locale = locale_prefix();
        if ($locale !== null) {
            Route::group(['prefix' => $locale], function () {
                $this->mapRoutes();
            });
        } else {
            $this->mapRoutes();
        }
    }

    protected function mapRoutes()
    {
        $this->mapApiRoutes();
        $this->mapWebAdminRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../Routes/web.php');
    }

    protected function mapWebAdminRoutes()
    {

        Route::prefix('shop-admin')
            ->middleware(['web', 'shopadmin'])
           // ->middleware(['web'])
            ->namespace($this->namespace.'\Admin')
            ->group(__DIR__ . '/../Routes/admin.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('shop-api')
            ->middleware('api')
            ->namespace($this->namespace.'\Api')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
