<?php

namespace Modules\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Problem;
use Modules\Shop\Console\GenerateShopAdminRole;
use Modules\Shop\Console\GenerateShopPermission;
use Modules\Shop\Repositories\Eloquent\EloquentPcategoryRepository;
use Modules\Shop\Repositories\Eloquent\EloquentProblemRepository;
use Modules\Shop\Repositories\PcategoryRepository;
use Modules\Shop\Repositories\ProblemRepository;
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
		   PcategoryRepository::class,
		    function () {
			    return new EloquentPcategoryRepository(new Pcategory());
		    }
	    );
		$this->app->bind(
            'Modules\Shop\Repositories\CompanyRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentCompanyRepository(new \Modules\Mon\Entities\Company());
                return $repository;
            }
        );
	    $this->app->bind(
		    ProblemRepository::class,
		    function () {
			    return new EloquentProblemRepository(new Problem());
		    }
	    );
		$this->app->bind(
            'Modules\Shop\Repositories\BrandRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentBrandRepository(new \Modules\Mon\Entities\Brand());
                return $repository;
            }
        );
        $this->app->bind(
 
            'Modules\Shop\Repositories\BrandRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentBrandRepository(new \Modules\Mon\Entities\Brand());
                return $repository;
            }
        );

        $this->app->bind(
            'Modules\Shop\Repositories\AttributeRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentAttributeRepository(new \Modules\Mon\Entities\Attribute());
                return $repository;
            }
        );
 
            
        $this->app->bind(
            'Modules\Shop\Repositories\VoucherRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentVoucherRepository(new \Modules\Mon\Entities\Voucher());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\PInformationRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentPInformationRepository(new \Modules\Mon\Entities\PInformation());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\VtCategoryRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentVtCategoryRepository(new \Modules\Mon\Entities\VtCategory());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\VtProductRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentVtProductRepository(new \Modules\Mon\Entities\VtProduct());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\TransferHistoryRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentTransferHistoryRepository(new \Modules\Mon\Entities\TransferHistory());
                return $repository;
            }
        );

        $this->app->bind(
            'Modules\Shop\Repositories\VtImportExcelRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentVtImportExcelRepository(new \Modules\Mon\Entities\VtImportExcel());
                return $repository;
            }
        );
        $this->app->bind(
            'Modules\Shop\Repositories\StorageProductRepository',
            function () {
                $repository = new \Modules\Shop\Repositories\Eloquent\EloquentStorageProductRepository(new \Modules\Mon\Entities\StorageProduct());
                return $repository;
            }
        );
// add bindings




    }
}
