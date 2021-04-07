<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Pcategory extends Model
{
    use  MediaRelation, SoftDeletes;

    protected $table = 'pcategory';
    protected $fillable = [
        'id',
        'name',
        'type',
        'parent_id'
    ];
    public function getTypeNameAttribute($value) {
        $statusName = '';
        switch ($this->type) {
            case 'product':
                $statusName = 'Hàng hóa';
                break;
            case 'service':
                $statusName = 'Dịch vụ';
                break;
        }
        return $statusName;
    }
    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
