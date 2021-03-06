<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use  SoftDeletes;

    protected $table = 'product_attribute_values';
    protected $fillable = ['attribute_id','product_id', 'value_id','company_id','shop_id', 'price', 'sale_price', 'amount'];

    public function attributeValues()
    {
        return $this->belongsTo(AttributeValue::class, 'value_id');
    }
}
