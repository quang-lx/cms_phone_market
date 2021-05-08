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
        'company_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'status',
    ];

    const STATUS_INACTIVE = 0;
    const STATUS_NEW = 1;
    const STATUS_LOCK = 2;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'vt_transfer_history_detail','vt_transfer_id','vt_product_id')->withPivot('amount');
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