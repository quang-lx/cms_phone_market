<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class CartProduct extends Model
{
    use  SoftDeletes;

    protected $table = 'cart_product';
    protected $fillable = [
        'cart_id',
        'shop_id',
        'company_id',

        'product_id',
        'product_attribute_value_id',
        'quantity',

        'note',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
     }
}
