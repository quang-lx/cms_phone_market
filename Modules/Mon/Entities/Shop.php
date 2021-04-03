<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Shop extends Model
{
    use  SoftDeletes, Userstamps;

    const STATUS_LOCK = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'shop';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'phone',
        'name',
        'address',
        'email',
        'status',
        'company_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function getStatusNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusName = 'Khóa';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Hoạt động';
                break;
        }
        return $statusName;
    }
}
