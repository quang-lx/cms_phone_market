<?php

namespace Modules\Api\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShopRatingCreated
{
    use Dispatchable, SerializesModels;

    public $shop_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shop_id)
    {
        $this->shop_id = $shop_id;
    }

}
