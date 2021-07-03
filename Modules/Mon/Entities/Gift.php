<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;
use Wildside\Userstamps\Userstamps;

class Gift extends Model
{
    use  SoftDeletes,Userstamps,MediaRelation;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'gifts';
    protected $fillable = [
        'id',
        'name',
        'point',
        'amount',
        'used_amount',
        'status',
        'description',
    ];

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }

    public function users() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusNameAttribute($value)
    {
        $statusName = '';
        switch ($this->status) {
            case self::STATUS_INACTIVE:
                $statusName = 'Khóa';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Hoạt động';
                break;
        }
        return $statusName;
    }
    public function getStatusColorAttribute($value)
    {
        $statusColor = '';
        switch ($this->status) {
            case self::STATUS_INACTIVE:
                $statusColor = '#F5ABAB';
                break;
            case self::STATUS_ACTIVE:
                $statusColor = '#219653';
                break;
        }
        return $statusColor;
    }
}
