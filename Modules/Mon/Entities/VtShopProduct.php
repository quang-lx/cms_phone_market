<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class VtShopProduct extends Model
{
    use  SoftDeletes, Userstamps;

    protected $table = 'vt_shop_product';
    protected $fillable = [
        'id',
        'vt_product_id',
        'shop_id',
        'company_id',
        'amount',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}
