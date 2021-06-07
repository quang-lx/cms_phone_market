<?php

namespace App\Listeners;

use App\Events\UserUpdateOrderStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mon\Entities\ShopOrderNotification;

class InsertShopOrderNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserUpdateOrderStatus $event)
    {
		ShopOrderNotification::create($event->data);
    }
}
