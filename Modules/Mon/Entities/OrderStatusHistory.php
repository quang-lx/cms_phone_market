<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{



    protected $table = 'order_status_history';
    protected $fillable = [
        'order_id',
        'title',
        'old_status',
        'new_status',
        'user_id',
        'shop_id'
    ];


    public static function parseHistoryTitle($orderType, $oldStatus, $newStatus) {

	}
}
