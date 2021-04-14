<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class PcategoryProduct extends Model
{

    protected $table = 'category_product';
    protected $fillable = [
        'id',
        'product_id',
        'category_id',
    ];

}
