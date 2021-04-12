<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class ProductPrice extends Model {
	use  SoftDeletes;


	protected $table = 'product_price';
	protected $fillable = [
		'product_id', 'min', 'max', 'price'
	];

	public function product() {
		return $this->belongsTo(Product::class);
	}
}
