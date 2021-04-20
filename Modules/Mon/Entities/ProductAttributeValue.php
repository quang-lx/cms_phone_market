<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use  SoftDeletes;

    protected $table = 'product_attributes';
    protected $fillable = ['attribute_id','product_id', 'value_id','company_id','shop_id', 'price', 'sale_price', 'amount'];
}
