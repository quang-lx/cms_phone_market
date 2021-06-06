<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
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
    public function handle(OrderStatusUpdated $event)
    {
		ShopOrderNotification::create($event->data);
    }
}
