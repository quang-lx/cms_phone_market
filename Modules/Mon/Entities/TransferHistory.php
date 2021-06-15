<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class TransferHistory extends Model
{
    use  SoftDeletes, Userstamps;

    protected $table = 'vt_transfer_history';
    protected $fillable = [
        'id',
        'title',
        'received_at',
        'shop_id',
        'to_shop_id',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'status',
        'type',
    ];

    const STATUS_INACTIVE = 0;
    const STATUS_NEW = 1;
    const STATUS_LOCK = 2;

    const TYPE_EXPORT = 1;
    const TYPE_MOVE = 2;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'to_shop_id');
    }
    public function getFromShopName() {
    	if ($this->shop) return $this->shop->name;
    	return optional($this->company)->name;
    }

    public function vtProducts()
    {
        return $this->belongsToMany(VtProduct::class, 'vt_transfer_history_detail','vt_transfer_id','vt_product_id')->withPivot('amount');
    }

    public function getStatusNameAttribute($value)
    {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusName = 'Đã khóa';
                break;
            case self::STATUS_NEW:
                $statusName = 'Mới tạo';
                break;
            case self::STATUS_INACTIVE:
                $statusName = 'Chưa hoạt động';
                break;
        }
        return $statusName;
    }
    public function getStatusColorAttribute($value)
    {
        $statusColor = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusColor = '#FF0000';
                break;
            case self::STATUS_NEW:
                $statusColor = '#008000'; //red
                break;
            case self::STATUS_INACTIVE:
                $statusColor = '#808080';
                break;
        }
        return $statusColor;
    }

    public function getTypeNameAttribute($value)
    {
        $typeName = '';
        switch ($this->type) {
            case self::TYPE_EXPORT:
                $typeName = 'Xuất kho';
                break;
            case self::TYPE_MOVE:
                $typeName = 'Chuyển kho';
                break;
        }
        return $typeName;
    }
    public function getTypeColorAttribute($value)
    {
        $typeColor = '';
        switch ($this->type) {
            case self::TYPE_EXPORT:
                $typeColor = '#008000'; //green
                break;
            case self::TYPE_MOVE:
                $typeColor = '#FF0000'; //red
                break;
        }
        return $typeColor;
    }
}
