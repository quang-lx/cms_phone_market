<?php

namespace App\Providers;

use App\Events\AdminNotiCreated;
use App\Events\NeedCreateUserSmsToken;
use App\Events\UserRoadCreated;
use App\Events\VbeeTTSResponsed;
use App\Events\WarningPublished;
use App\Events\WarningStatusUpdated;
use App\Listeners\PushNotiToUserCreateWarning;
use App\Listeners\PushNotiWhenAdminNotiCreated;
use App\Listeners\PushNotiWhenWarningPublish;
use App\Listeners\SendSmsToUserRegistered;
use App\Listeners\SyncRoadToElastic;
use App\Listeners\TTSWhenWarningPublish;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NeedCreateUserSmsToken::class => [
            SendSmsToUserRegistered::class
        ],
        UserRoadCreated::class => [
            SyncRoadToElastic::class
        ],
        WarningPublished::class=> [
            TTSWhenWarningPublish::class
        ],
        VbeeTTSResponsed::class=> [
            PushNotiWhenWarningPublish::class
        ],
        AdminNotiCreated::class => [
            PushNotiWhenAdminNotiCreated::class
        ],
        WarningStatusUpdated::class => [
            PushNotiToUserCreateWarning::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
