<?php

namespace Modules\Media\Providers;

use Modules\Media\Image\ImageFactoryInterface;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Image\ThumbnailManagerRepository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Media\Image\Intervention\InterventionFactory;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageFactoryInterface::class, InterventionFactory::class);

        $this->app->singleton(ThumbnailManager::class, function () {
            return new ThumbnailManagerRepository();
        });

        $this->app->singleton('imagy', function ($app) {
            $factory = new InterventionFactory();

            return new Imagy($factory, $app[ThumbnailManager::class]);
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Imagy', \Modules\Media\Image\Facade\Imagy::class);
        });


    }

    public function boot() {
        $this->registerThumbnails();
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['imagy'];
    }

    private function registerThumbnails()
    {
        $this->app[ThumbnailManager::class]->registerThumbnail('s', [
            'resize' => [
                'width' => 80,
                'height' => null,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ]);
        $this->app[ThumbnailManager::class]->registerThumbnail('m', [
            'resize' => [
                'width' => 200,
                'height' => null,
                'callback' => function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                },
            ],
        ]);

    }
}
