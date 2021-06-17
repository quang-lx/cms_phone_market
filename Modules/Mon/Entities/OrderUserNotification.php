<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderUserNotification extends Model
{

    protected $table = 'order_user_notification';
    protected $fillable = [
        'order_id',
        'title',
        'content',
        'user_id',
		'order_status',
		'order_type',
	    'seen'
    ];


}
