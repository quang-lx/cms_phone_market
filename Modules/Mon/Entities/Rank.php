<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Traits\MediaRelation;


class Rank extends Model
{
    use  SoftDeletes,MediaRelation;

    protected $table = 'rank';
    protected $fillable = [
        'id',
        'name',
        'description',
        'point',
    ];

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
