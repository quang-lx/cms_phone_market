<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Admin\Console\CreateAdminUser;
use Modules\Admin\Console\GenerateAdminPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'Modules\Admin\Repositories\RoleRepository',
            function () {
                return new \Modules\Admin\Repositories\Eloquent\RoleRepository(new Role());
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\PermissionRepository',
            function () {
                return new \Modules\Admin\Repositories\Eloquent\PermissionRepository(new Permission());
            }
        );
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
            'Modules\Admin\Repositories\ProvinceRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentProvinceRepository(new \Modules\Mon\Entities\Province());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\DistrictRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentDistrictRepository(new \Modules\Mon\Entities\District());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\PhoenixRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentPhoenixRepository(new \Modules\Mon\Entities\Phoenix());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\CompanyRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentCompanyRepository(new \Modules\Mon\Entities\Company());
                return $repository;
            }
        );

        $this->app->bind(
            'Modules\Admin\Repositories\ListCompanyRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentListCompanyRepository(new \Modules\Mon\Entities\Company());
                return $repository;
            }
        );

        $this->app->bind(
            'Modules\Admin\Repositories\PcategoryRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentPcategoryRepository(new \Modules\Mon\Entities\Pcategory());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\BrandRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentBrandRepository(new \Modules\Mon\Entities\Brand());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\AccountRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentAccountRepository(new \Modules\Mon\Entities\User());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\ProblemRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentProblemRepository(new \Modules\Mon\Entities\Problem());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\HomeSettingRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentHomeSettingRepository(new \Modules\Mon\Entities\HomeSetting());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\BannersRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentBannersRepository(new \Modules\Mon\Entities\Banners());
                return $repository;
            }
        ); 
        $this->app->bind(
            'Modules\Admin\Repositories\AttributeRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentAttributeRepository(new \Modules\Mon\Entities\Attribute());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\ProductRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentProductRepository(new \Modules\Mon\Entities\Product());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\PInformationRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentPInformationRepository(new \Modules\Mon\Entities\PInformation());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\VoucherRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentVoucherRepository(new \Modules\Mon\Entities\Voucher());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\RankRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentRankRepository(new \Modules\Mon\Entities\Rank());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\ShipTypeRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentShipTypeRepository(new \Modules\Mon\Entities\ShipType());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\OrdersRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentOrdersRepository(new \Modules\Mon\Entities\Orders());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\PaymentMethodRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentPaymentMethodRepository(new \Modules\Mon\Entities\PaymentMethod());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\FbNotificationRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentFbNotificationRepository(new \Modules\Mon\Entities\FbNotification());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\ShopRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentShopRepository(new \Modules\Mon\Entities\Shop());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Admin\Repositories\GiftRepository',
            function () {
                $repository = new \Modules\Admin\Repositories\Eloquent\EloquentGiftRepository(new \Modules\Mon\Entities\Gift());
                return $repository;
            }
        );
// add bindings


























    }
}
