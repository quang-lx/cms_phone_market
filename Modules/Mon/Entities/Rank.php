<?php

namespace Modules\Mon\Entities;

use App\Models\CacheKey;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Transformers\RankTransformer;
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

    public static function rankById($id) {
	    $key = sprintf(CacheKey::RANK_DETAIL, $id);
	    return  Cache::rememberForever($key, function () use ($id) {
		    $rank = Rank::query()->find($id);
		    return $rank? new RankTransformer($rank): null;
	    });
    }
}
