<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;

class ShopOrderNotification extends Model
{

    protected $table = 'order_shop_notifications';
    protected $fillable = [
        'order_id',
        'title',
        'content',
        'shop_id',
    ];


}
