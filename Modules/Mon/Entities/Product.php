<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;
use Modules\Mon\Traits\CommonModel;
use Wildside\Userstamps\Userstamps;

class Product extends Model
{
     use  SoftDeletes, MediaRelation, CommonModel, Userstamps;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

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
	    'sale_price',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function productAttributes() {
    	return $this->hasMany(ProductAttribute::class, 'product_id');
    }
	public function productAttributeValues() {
		return $this->hasMany(ProductAttributeValue::class, 'product_id');
	}

	public function attributes() {
		return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id');
	}
	public function attributeValues() {
		return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 'product_id', 'value_id');
	}
    public function prices() {
    	return $this->hasMany(ProductPrice::class, 'product_id');
    }
	public function pcategoryAsm() {
		return $this->hasMany(PcategoryProduct::class, 'product_id');
	}

	public function pcategories()
	{
		return $this->belongsToMany(Pcategory::class, 'category_product', 'product_id', 'category_id');
	}
	public function problems()
	{
		return $this->belongsToMany(Problem::class, 'product_problem', 'product_id', 'problem_id');
	}
    public function getStatusNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $statusName = 'Hoạt động';
                break;
            case self::STATUS_INACTIVE:
                $statusName = 'Đã xóa';
                break;
        }
        return $statusName;
    }
    public function getStatusColorAttribute($value) {
        $statusColor = '';
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $statusColor = '#219653';
                break;
            case self::STATUS_INACTIVE:
                $statusColor = 'red';
                break;
        }
        return $statusColor;
    }

    public function getStateNameAttribute($value) {
        $p_state = '';
        switch ($this->p_state) {
            case 1:
                $p_state = 'Mới';
                break;
            case 2:
                $p_state = 'Đã sử dụng';
                break;
        }
        return $p_state;
    }

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('product_collection')->first();
    }
}
