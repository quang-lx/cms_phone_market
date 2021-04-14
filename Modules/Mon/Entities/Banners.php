<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;

class Banners extends Model
{
    use  MediaRelation;

    protected $table = 'banners';
    protected $fillable = [
        'id',
        'description',
    ];

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
