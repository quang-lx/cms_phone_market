<?php

namespace App\Providers;

use App\Events\AdminNotiCreated;
use App\Events\OrderStatusUpdated;
use App\Events\ShopNotiCreated;
use App\Events\NeedCreateUserSmsToken;
use App\Events\UserUpdateOrderStatus;
use App\Events\VtTransferHistoryCreated;
use App\Listeners\HandleEventVtTransHistoryCreated;
use App\Listeners\InsertOrderStatusHistory;
use App\Listeners\InsertShopOrderNotification;
use App\Listeners\PushNotiWhenAdminNotiCreated;
use App\Listeners\PushNotiWhenSHopNotiCreated;
use App\Listeners\SendSmsToUserRegistered;
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
	    AdminNotiCreated::class => [
		    PushNotiWhenAdminNotiCreated::class
	    ],
        SHopNotiCreated::class => [
		    PushNotiWhenShopNotiCreated::class
	    ],
		OrderStatusUpdated::class => [
			InsertOrderStatusHistory::class
		],
		UserUpdateOrderStatus::class => [
			InsertShopOrderNotification::class
		],
	    VtTransferHistoryCreated::class => [
		    HandleEventVtTransHistoryCreated::class
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
