<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;
use Modules\Mon\Traits\CommonModel;

class Product extends Model
{
     use  SoftDeletes, MediaRelation, CommonModel;

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

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('product_collection')->first();
    }
}
