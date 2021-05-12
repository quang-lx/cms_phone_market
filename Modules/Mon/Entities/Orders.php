<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use  SoftDeletes;

    const TYPE_SUA_CHUA = 'sua_chua';
    const TYPE_BAO_HANH = 'bao_hanh';
    const TYPE_MUA_HANG = 'mua_hang';


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

	    'ship_address_id',
        'ship_province_id',
        'ship_province_name',
        'ship_district_id',
        'ship_district_name',
        'ship_phoenix_id',
        'ship_phoenix_name',
        'ship_address',
    ];
}
