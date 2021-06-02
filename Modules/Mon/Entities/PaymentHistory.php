<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
	use SoftDeletes;
    protected $table = 'payment_history';
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'pay_amount',
        'pay_method_name',

    ];

    public function orders()
    {
        return $this->hasMany(Orders::class,'payment_history_id');
    }

}
