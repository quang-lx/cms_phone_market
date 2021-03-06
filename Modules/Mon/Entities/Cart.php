<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use  SoftDeletes;

    protected $table = 'carts';
    protected $fillable = [
        'id',
        'user_id',

    ];

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id', 'id');
    }


}
