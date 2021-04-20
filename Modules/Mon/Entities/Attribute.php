<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use  SoftDeletes;

    protected $table = 'attributes';
    protected $fillable = [
        'id',
        'code',
        'name',
        'company_id',
        'shop_id',
    ];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'company_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'shop_id');
    }
}
