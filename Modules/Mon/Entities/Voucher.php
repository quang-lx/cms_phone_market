<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Voucher extends Model
{
    use  SoftDeletes, Userstamps;

    const TYPE_DISCOUNT_ALL = 1;
    const TYPE_DISCOUNT_PRODUCT = 2;

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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'voucher_product');
    }

    public function getTypeNameAttribute($value)
    {
        $typeName = '';
        switch ($this->type) {
            case self::TYPE_DISCOUNT_ALL:
                $typeName = 'Giảm giá toàn bộ';
                break;
            case self::TYPE_DISCOUNT_PRODUCT:
                $typeName = 'Giảm giá sản phẩm';
                break;
        }
        return $typeName;
    }

    public function getStatusNameAttribute($value)
    {
        $statusName = '';
        $start = strtotime($this->actived_at);
        $end = strtotime($this->expired_at);
        if (time() < $start){
            $statusName.='Sắp diễn ra: ';
        } else if (time() <= $end){
            $statusName.='Đang diễn ra: ';
        } else {
            $statusName.='Đã kết thúc: ';
        }
        $statusName.= sprintf('%s - %s', date('d/m/Y', $start), date('d/m/Y', $end));
        return $statusName;
    }


}