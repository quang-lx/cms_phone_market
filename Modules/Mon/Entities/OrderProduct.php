<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Entities\Media;
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
    public function getThumbnailAttribute() {
    	if ($this->product) {
    		return $this->product->files()->first();
	    }
        return $this->filesByZone('image')->first();
    }
}
