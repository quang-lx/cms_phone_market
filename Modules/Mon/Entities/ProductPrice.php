<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Modules\Media\Traits\MediaRelation;


class ProductPrice extends Model
{
    use  SoftDeletes;


    protected $table = 'product_price';
    protected $fillable = [
        'product_id', 'min', 'max', 'price'
    ];

    
}
