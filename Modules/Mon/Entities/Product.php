<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Media\Traits\MediaRelation;
use Modules\Mon\Traits\CommonModel;
use Wildside\Userstamps\Userstamps;

/**
 * Class Product
 * @method available()
 * @package Modules\Mon\Entities
 */

class Product extends Model {
	use  SoftDeletes, MediaRelation, CommonModel, Userstamps;

	const TYPE_PRODUCT = 1;
	const TYPE_REPAIR = 2;
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_INCOMING = 2;

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
		'shop_id',
		'amount',
		'price',
		'sale_price',
		'fix_time',
		'warranty_time',
		'type',
		'rating_avg',
		'rating_user',
	];
	protected $casts = [
		'type' => 'integer',
	];
	public function company() {
		return $this->belongsTo(Company::class);
	}

	public function shop() {
		return $this->belongsTo(Shop::class, 'shop_id');
	}

	public function brand() {
		return $this->belongsTo(Brand::class, 'brand_id');
	}

	public function pinformation() {
		return $this->belongsToMany(PInformation::class, 'production_information', 'product_id', 'information_id')->withPivot('value');
	}

	public function productInformation() {
		return $this->hasMany(ProductInformation::class, 'product_id');
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
		return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 'product_id', 'value_id')->withPivot('price', 'sale_price', 'amount', 'id');
	}

	public function prices() {
		return $this->hasMany(ProductPrice::class, 'product_id');
	}

	public function pcategoryAsm() {
		return $this->hasMany(PcategoryProduct::class, 'product_id');
	}

	public function pcategories() {
		return $this->belongsToMany(Pcategory::class, 'category_product', 'product_id', 'category_id');
	}

	public function problems() {
		return $this->belongsToMany(Problem::class, 'product_problem', 'product_id', 'problem_id');
	}
	public function orderProducts() {
		return $this->hasMany(OrderProduct::class, 'product_id');
	}

	public function getStatusNameAttribute($value) {
		$statusName = '';
		switch ($this->status) {
			case self::STATUS_ACTIVE:
				$statusName = 'Hoạt động';
				break;
			case self::STATUS_INACTIVE:
				$statusName = 'Đã ẩn';
				break;
				case self::STATUS_INCOMING:
				$statusName = 'Hàng sắp về';
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
			case self::STATUS_INCOMING:
				$statusColor = '#2003fc';
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

	public function getAmountNameAttribute($value) {
		$amount_name = '';
		if ($this->amount>0) {
			$amount_name = 'Có';
		}else{
			$amount_name = 'Không';
		}

		return $amount_name;
	}

	public function getThumbnailAttribute() {
		return $this->filesByZone('product_collection')->first();
	}

	public function scopeAvailable($query) {

		return $query->where(function ($q) {
			/** @var \Illuminate\Database\Eloquent\Builder $q */
			$q->orWhere('amount', '>', 0);
			$q->orWhereHas('productAttributeValues', function ($q) {
				$q->where('amount', '>', 0);
			});
		});

	}
}
