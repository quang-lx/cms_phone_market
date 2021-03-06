<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Pcategory extends Model
{
    use  MediaRelation, SoftDeletes;

    const TYPE_SERVICE = 'service';
    const TYPE_PRODUCT = 'product';

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

    public function brand()
    {
        return $this->belongsToMany(Pcategory::class, 'brand_pcategory', 'pcategory_id','brand_id');
    }

    public function shopCategory()
    {
        return $this->hasMany(ShopCategory::class,'category');
    }
}
