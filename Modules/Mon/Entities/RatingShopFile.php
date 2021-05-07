<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class RatingShopFile extends Model
{

    protected $table = 'rating_shop_file';
    protected $fillable = [
        'id',
        'shop_rating_id',
        'file_id'
    ];

}
