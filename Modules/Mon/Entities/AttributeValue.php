<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use  SoftDeletes;

    protected $table = 'attribute_values';
    protected $fillable = [
        'id',
        'name',
        'attribute_id',
        'company_id',
        'shop_id',
    ];
    
}
