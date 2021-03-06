<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\RankRepository;
use Modules\Api\Transformers\RankTransformer;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\Rank;

class CachedEloquentRankRepository implements RankRepository
{


    public function getAll(Request $request) {
        return  Cache::rememberForever(CacheKey::RANK_ALL, function () use ($request) {
             $ranks = Rank::query()->orderBy('point')->get();
             return RankTransformer::collection($ranks);
        });
    }
	public function rankDetail(Request $request, $rankId) {

		return  Rank::rankById($rankId);
	}
}
