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

    const STATUS_ORDER_CREATED = 'order_created'; // Chờ xác nhận
    const STATUS_ORDER_CONFIRMED = 'order_confirmed';//Chờ giao hàng
    const STATUS_ORDER_SENDING = 'order_sending';//Chờ nhận hàng
    const STATUS_ORDER_FIXING = 'order_fixing'; //Chờ sửa chữa
    const STATUS_ORDER_WARRANTING = 'order_warranting';//Chờ bảo hành
    const STATUS_ORDER_DONE = 'order_done'; //Thành công
    const STATUS_ORDER_CANCEL = 'order_cancel'; //Đã hủy


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
        'created_at',
        'type_other'
    ];

    public function orderProducts()
    {
        return $this->belongsTo(OrderProduct::class, 'id', 'order_id');
    }

    public function orderBuySellProduts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusNameAttribute($value)
    {
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
