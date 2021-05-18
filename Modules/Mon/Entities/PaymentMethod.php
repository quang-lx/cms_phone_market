<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use  SoftDeletes;

    protected $table = 'payment_method';
    protected $fillable = [
        'name'
    ];
}