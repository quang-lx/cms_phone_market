<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Voucher extends Model
{
    use  SoftDeletes, Userstamps;

    protected $table = 'vouchers';
    protected $fillable = [
        'id',
        'company_id',
        'shop_id',
        'type',
        'title',
        'code',
        'discount_type',
        'discount_amount',
        'require_min_amount',
        'actived_at',
        'expired_at',
        'total',
        'total_used',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }


}
