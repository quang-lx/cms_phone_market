<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mon\Entities\OrderStatusHistory;

class InsertOrderStatusHistory implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderStatusUpdated $event)
    {
		OrderStatusHistory::create($event->data);
    }
}
