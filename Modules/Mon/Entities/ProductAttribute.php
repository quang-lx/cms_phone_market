<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use  SoftDeletes;

    protected $table = 'product_attributes';
    protected $fillable = ['attribute_id','product_id','company_id','shop_id'];

    public function productAttributeValue() {
    	return $this->hasMany(ProductAttributeValue::class, 'product_id', 'id');
    }
}
