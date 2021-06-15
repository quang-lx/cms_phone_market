<?php

namespace App\Listeners;

use App\Events\VtTransferHistoryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mon\Entities\ShopOrderNotification;

class HandleEventVtTransHistoryCreated implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(VtTransferHistoryCreated $event)
    {
		ShopOrderNotification::create($event->data);
    }
}
