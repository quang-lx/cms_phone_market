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

    const STATUS_WAIT = 0;
    const STATUS_DONE = 1;


    protected $table = 'orders';
    protected $fillable = [
        'id',
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
        'created_at'
    ];

    public function orderProducts() {
       return $this->belongsTo(OrderProduct::class, 'id','order_id');
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_WAIT:
                $statusName = 'Chưa xác nhận';
                break;
            case self::STATUS_DONE:
                $statusName = 'Xác nhận';
                break;
        }
        return $statusName;
    }
}
