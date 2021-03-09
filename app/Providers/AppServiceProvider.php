<?php

namespace App\Providers;

use App\Services\FcmService;
use App\Services\NotificationService;
use App\Services\SendSmsService;
use App\Services\SmsClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Kreait\Firebase\Messaging;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NotificationService::class, function ($app) {
            $messaging = app(Messaging::class);
            return new FcmService($messaging);
        });
        $this->app->singleton(SendSmsService::class, function ($app) {

            return new SmsClient();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
