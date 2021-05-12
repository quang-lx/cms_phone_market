<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use  SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'company_id',
        'shop_id',
        'ship_type_id',
        'order_type',
        'status',
        'payment_status',
        'total_price',
        'discount',
        'ship_fee',
        'pay_price',
        'ship_province_id',
        'ship_province_name',
        'ship_district_id',
        'ship_district_name',
        'ship_phoenix_id',
        'ship_phoenix_name',
        'ship_address',
    ];
}
