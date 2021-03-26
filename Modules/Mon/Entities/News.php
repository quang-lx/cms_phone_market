<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Traits\MediaRelation;
use Wildside\Userstamps\Userstamps;

/**
 * Modules\Mon\Entities\News
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $category_id
 * @property string|null $description
 * @property string|null $content
 * @property string|null $author
 * @property string|null $type
 * @property string|null $status
 * @property string|null $video_code
 * @property array|null $tags
 * @property int|null $flag_hot
 * @property int|null $flag_featured
 * @property int|null $flag_most_read
 * @property int|null $flag_video
 * @property string|null $flag
 * @property string|null $meta_keywords
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int|null $like
 * @property int|null $view
 * @property int|null $share
 * @property int|null $sort
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Mon\Entities\Category|null $category
 * @property-read \Modules\Mon\Entities\User|null $creator
 * @property-read \Modules\Mon\Entities\User|null $destroyer
 * @property-read \Modules\Mon\Entities\User|null $editor
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Media\Entities\Media[] $files
 * @property-read int|null $files_count
 * @property-read mixed $thumbnail
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Query\Builder|News onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|News publish()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereFlagFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereFlagHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereFlagMostRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereFlagVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereVideoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereView($value)
 * @method static \Illuminate\Database\Query\Builder|News withTrashed()
 * @method static \Illuminate\Database\Query\Builder|News withoutTrashed()
 * @mixin \Eloquent
 */
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
