<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{

    protected $table = 'shop_category';
    protected $fillable = [
        'id',
        'shop_id',
        'category',
        'type'
    ];

    public function pcategory()
    {
        return $this->hasMany(Pcategory::class,'category');
    }
}
