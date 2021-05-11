<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ShipType extends Model
{
    use  SoftDeletes;

    protected $table = 'ship_type';
    protected $fillable = [
        'name'
    ];
}
