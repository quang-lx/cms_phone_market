<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ShopShipType extends Model
{

    protected $table = 'shop_ship_type';
    protected $fillable = [
        'shop_id',
        'ship_type_id',
        'status'
    ];
}
