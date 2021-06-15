<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VtProduct extends Model
{
    use  SoftDeletes;

    protected $table = 'vt_product';
    protected $fillable = [
        'id',
        'name',
        'price',
        'vt_category_id',
        'shop_id',
        'company_id'
    ];

    public function VtCategory(){
        return $this->belongsTo(VtCategory::class,'vt_category_id');
    }

    public function VtShopProduct(){
        return $this->belongsTo(VtShopProduct::class,'id','vt_product_id');
    }
}
