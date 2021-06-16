<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;

class ShopOrderNotification extends Model
{
	const TYPE_ORDER = 1;
	const TYPE_VT_TRANSFER = 2;

    protected $table = 'order_shop_notifications';
    protected $fillable = [
        'id',
        'order_id',
        'title',
        'content',
        'shop_id',
		'order_status',
		'noti_type',
		'vt_transfer_id',
		'order_type',
        'seen'
    ];


}
