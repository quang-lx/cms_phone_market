<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    use  SoftDeletes;

    protected $table = 'production_information';
    protected $fillable = ['information_id','product_id', 'value'];

    public function pinformation() {
		return $this->belongsTo(PInformation::class, 'information_id');
	}
}
