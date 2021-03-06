<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NeedCreateUserSmsToken
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $user_phone;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $user_phone)
    {
        $this->user_id = $user_id;
        $this->user_phone = $user_phone;
    }

}
