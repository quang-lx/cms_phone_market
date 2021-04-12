<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use  SoftDeletes;

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
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
