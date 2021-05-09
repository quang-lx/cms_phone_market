<?php

namespace Modules\Api\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSearched
{
    use Dispatchable, SerializesModels;

    public $user_id;
    public $fcm_token;
    public $products;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $fcm_token, $products)
    {
        $this->user_id = $user_id;
        $this->fcm_token = $fcm_token;
        $this->products = $products;
    }

}
