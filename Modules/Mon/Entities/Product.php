<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Product extends Model
{
     use  SoftDeletes, MediaRelation;

    protected $table = 'product';
    protected $fillable = [
        'id',
        'name',
        'sku',
        'status',
        'p_state',
        'description',
        'p_weight',
        's_long',
        's_width',
        's_height',
        'brand_id',
        'company_id',
	    'amount',
	    'price',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function prices() {
    	return $this->hasMany(ProductPrice::class, 'product_id');
    }


	public function pcategories()
	{
		return $this->belongsToMany(Pcategory::class, 'category_product', 'product_id', 'category_id');
	}
}
