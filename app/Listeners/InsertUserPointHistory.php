<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\OrderStatusHistory;
use Modules\Mon\Entities\UserPointHistory;

class InsertUserPointHistory implements ShouldQueue
{

	/**
	 * Handle the event.
	 *
	 * @param object $event
	 * @return void
	 */
	public function handle(OrderStatusUpdated $event)
	{
		$order_id = $event->data['order_id'];
		$order_status = $event->data['new_status'];
		if ($order_status == Orders::STATUS_ORDER_DONE) {
			/** @var Orders $order */
			$order = Orders::find($order_id);
			UserPointHistory::createFromOrder($order);
		}

	}
}
