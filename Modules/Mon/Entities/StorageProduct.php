<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class StorageProduct extends Model
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
        'note',
        'status',
    ];

    const STATUS_INACTIVE = 0;
    const STATUS_NEW = 1;
    const STATUS_IMPORT = 2;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function toshop()
    {
        return $this->belongsTo(Shop::class, 'to_shop_id');
    }

    public function vtProducts()
    {
        return $this->belongsToMany(VtProduct::class, 'vt_transfer_history_detail','vt_transfer_id','vt_product_id')->withPivot('amount');
    }

    public function getStatusNameAttribute($value)
    {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_IMPORT:
                $statusName = 'Đã nhập kho';
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
            case self::STATUS_IMPORT:
                $statusColor = '#F5ABAB';
                break;
            case self::STATUS_NEW:
                $statusColor = '#219653';
                break;
            case self::STATUS_INACTIVE:
                $statusColor = 'red';
                break;
        }
        return $statusColor;
    }
}
