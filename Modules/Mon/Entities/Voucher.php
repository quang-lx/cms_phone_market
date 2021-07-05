<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Modules\Media\Traits\MediaRelation;

class Voucher extends Model
{
    use  SoftDeletes, Userstamps,MediaRelation;

    const TYPE_DISCOUNT_ALL = 1;
    const TYPE_DISCOUNT_PRODUCT = 2;


    const DISCOUNT_PRICE = 1;
    const DISCOUNT_PERCENT = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $casts = [
        'actived_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

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
        'use_condition',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'status',
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
        $status = $this->status ;

        if (!$status){
            $statusName = 'Không hiệu lực';
        } else {
            if (time() < $start){
                $statusName.='Sắp diễn ra: ';
            } else if (time() <= $end){
                $statusName.='Đang diễn ra: ';
            } else {
                $statusName.='Đã kết thúc: ';
            }
            $statusName.= sprintf('%s - %s', date('d/m/Y', $start), date('d/m/Y', $end));
        }
        
        return $statusName;
    }

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }

}
