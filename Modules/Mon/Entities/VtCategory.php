<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class VtCategory extends Model
{
    use  MediaRelation, SoftDeletes;

    protected $table = 'vt_category';
    protected $fillable = [
        'id',
        'name',
        'shop_id',
        'company_id',
        'parent_id'
    ];
    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
