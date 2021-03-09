<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Traits\MediaRelation;
use Wildside\Userstamps\Userstamps;

class News extends Model
{
    use Userstamps;
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISH = 'publish';

    use SoftDeletes, MediaRelation;
    protected $casts = [
        'tags' => 'array',
    ];

    protected $table = 'news';
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'content',
        'author',
        'type',
        'status',
        'video_code',
        'tags',
        'flag_hot',
        'flag_featured',
        'flag_most_read',
        'flag_video',
        'flag',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'like',
        'view',
        'share',
        'sort',
    ];

    public function getThumbnailAttribute()
    {
        return $this->filesByZone('thumbnail')->first();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }



    public function scopePublish($query)
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }


}
