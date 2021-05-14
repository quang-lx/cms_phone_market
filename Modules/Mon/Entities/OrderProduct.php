<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use  SoftDeletes;

    protected $table = 'order_product';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_attribute_value_id',
        'quantity',
        'price',
        'price_sale',
        'note',
    ];
}
