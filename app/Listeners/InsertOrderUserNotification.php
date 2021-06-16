<?php

namespace App\Listeners;

use App\Events\ShopUpdateOrderStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mon\Entities\OrderUserNotification;

class InsertOrderUserNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ShopUpdateOrderStatus $event)
    {
		OrderUserNotification::create($event->data);
    }
}
