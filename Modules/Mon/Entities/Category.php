<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Traits\MediaRelation;

class Category extends Model
{
    use MediaRelation, SoftDeletes;

    const STATUS_PUBLISH = 'publish';
    const STATUS_HIDE = 'hide';

    protected $table = 'category';
    protected $fillable = ['title', 'type', 'slug', 'parent_id', 'order', 'description', 'status'];

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }
}
