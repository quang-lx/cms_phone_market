<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\MediaRelation;

class CartProduct extends Model
{
    use  SoftDeletes;

    protected $table = 'cart_product';
    protected $fillable = [
        'cart_id',
        'shop_id',
        'shop_name',
        'company_id',

        'product_id',
        'product_attribute_value_id',
        'quantity',

        'note',
        'product_img_id'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
     }
	public function productValue() {
		return $this->belongsTo(ProductAttributeValue::class, 'product_attribute_value_id', 'id');
	}
    public function productThumbnail() {
        return $this->belongsTo(Media::class, 'product_img_id', 'id');
    }
}
