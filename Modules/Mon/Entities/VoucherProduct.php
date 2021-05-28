<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class VoucherProduct extends Model
{

    protected $table = 'voucher_product';
    protected $fillable = [
        'voucher_id',
        'product_id',

    ];



}
