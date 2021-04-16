<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Mon\Traits\CommonModel;
use Wildside\Userstamps\Userstamps;
use Modules\Media\Traits\MediaRelation;

class Shop extends Model
{
    use  SoftDeletes, Userstamps, MediaRelation, CommonModel;

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
	    'lat',
	    'lng',
	    'place'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function getStatusNameAttribute($value) {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusName = 'Không hoạt động';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Hoạt động';
                break;
        }
        return $statusName;
    }
    public function getStatusColorAttribute($value) {
        $statusColor = '';
        switch ($this->status) {
            case self::STATUS_LOCK:
                $statusColor = '#F5ABAB';
                break;
            case self::STATUS_ACTIVE:
                $statusColor = '#219653';
                break;
        }
        return $statusColor;
    }
    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
