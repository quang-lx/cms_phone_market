<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class OrderProduct extends Model
{
    use  SoftDeletes, MediaRelation;

    protected $table = 'order_product';
    protected $fillable = [
        'order_id',
	    'product_title',
        'product_id',
        'product_attribute_value_id',
        'quantity',
        'price',
        'price_sale',
        'note',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
     }
}
