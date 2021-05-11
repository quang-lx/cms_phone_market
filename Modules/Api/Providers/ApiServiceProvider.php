<?php

namespace Modules\Api\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\ApiShopRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Api\Repositories\Eloquent\Cached\CachedEloquentAreaRepository;
use Modules\Api\Repositories\Eloquent\Cached\CachedEloquentRankRepository;
use Modules\Api\Repositories\Eloquent\Cached\EloquentShipTypeRepository;
use Modules\Api\Repositories\Eloquent\EloquentAddressRepository;
use Modules\Api\Repositories\Eloquent\EloquentApiShopRepository;
use Modules\Api\Repositories\Eloquent\EloquentCategoryRepository;
use Modules\Api\Repositories\Eloquent\EloquentHomeSettingRepository;
use Modules\Api\Repositories\Eloquent\EloquentProductRepository;
use Modules\Api\Repositories\Eloquent\EloquentRatingRepository;
use Modules\Api\Repositories\Eloquent\EloquentRatingShopRepository;
use Modules\Api\Repositories\Eloquent\EloquentSearchRepository;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Repositories\RankRepository;
use Modules\Api\Repositories\RatingRepository;
use Modules\Api\Repositories\RatingShopRepository;
use Modules\Api\Repositories\SearchRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\RatingShop;
use Modules\Mon\Entities\ShipType;

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
	    $this->app->bind(
		    HomeSettingRepository::class,
		    function () {
			    return new EloquentHomeSettingRepository();
		    }
	    );
        $this->app->bind(
            SearchRepository::class,
            function () {
                return new EloquentSearchRepository();
            }
        );
	    $this->app->bind(
		    CategoryRepository::class,
		    function () {
			    return new EloquentCategoryRepository(new Pcategory());
		    }
	    );
	    $this->app->bind(
		    ProductRepository::class,
		    function () {
			    return new EloquentProductRepository(new Product());
		    }
	    );

	    $this->app->bind(
		    RatingRepository::class,
		    function () {
			    return new EloquentRatingRepository(new Rating());
		    }
	    );
	    $this->app->bind(
		    RatingShopRepository::class,
		    function () {
			    return new EloquentRatingShopRepository(new RatingShop());
		    }
	    );
	    $this->app->bind(
		    AddressRepository::class,
		    function () {
			    return new EloquentAddressRepository(new Address());
		    }
	    );
	    $this->app->bind(
		    RankRepository::class,
		    function () {
			    return new CachedEloquentRankRepository();
		    }
	    );
	    $this->app->bind(
		    ShipTypeRepository::class,
		    function () {
			    return new EloquentShipTypeRepository(new ShipType());
		    }
	    );
    }
}
