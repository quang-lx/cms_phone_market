<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Support\Carbon;
use Modules\Api\Repositories\ApiRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\Mission;
use Modules\Mon\Entities\News;
use Modules\Mon\Entities\UserMissionDaily;

class EloquentApiRepository implements ApiRepository
{
    public function games()
    {
        return Game::query()->where('status', Game::STATUS_ACTIVE)->get();
    }

    public function items(Game $game, Request $request)
    {
        $query = Item::query();
        $limit = 1200;
        if ($game->type == 'vqhn' || $game->type == 'vqct') {
            $limit = 12;
        }
        // ko lay item quy doi
        $query->where(function ($q) {
            $q->whereNull('number_stone')
                ->orWhere('number_stone', 0);
        });
        $query->where(function ($q) {
            $q->whereNull('number_map')
                ->orWhere('number_map', 0);
        });

//        $query->whereHas('gamePartitions', function ($q) use ($game) {
//            $q->where('game_id', $game->id);
//        });
        return $query->where('game_id', $game->id)->limit($limit)->get();
    }

	public function categories()
	{
		return Category::all();
	}
	public function news(Category $category, Request $request) {
		$query = News::query()->where('category_id', $category->id);
        $query->orderBy('created_at', 'desc');
        return $query->paginate($request->get('per_page', 10));

    }
    public function search(Request $request) {
        $query = News::query();
        if ($request->get('keyword')) {
            $keyword = $request->get('keyword');
            $query->where('title','like', "%$keyword%");
        }
        $query->where('type', News::TYPE_NEWS);
        return $query->paginate($request->get('per_page', 10));

    }
    public function exchangeItems(Game $game)
    {
        return Item::query()->where('game_id', $game->id)
            ->where(function ($q){
                $q->where('number_stone', '>', 0);
                $q->orWhere('number_map', '>', 0);
            })->get();
    }
    public function missions(Request $request)
    {
        $missions = Mission::whereIn('type', ['read_news','share_news'])->get();
        $data = [];
        $userId = $request->get('user_id');
        foreach ($missions as $mission) {
            for ($i = 1; $i <= 5; $i++) {
                $missionDone = UserMissionDaily::query()
                    ->whereDate('apply_date',   Carbon::now()->format('Y-m-d'))
                    ->where('user_id', $userId)
                    ->where('mission_type', $mission->type)
                    ->count();
                if ($mission->type == 'read_news') {
                    $data[] = [
                        'type' => $mission->type,
                        'coin' => $mission->coin * $i,
                        'num_turn' => $mission->num_turn * $i,
                        'title' => sprintf('Đọc %d bài báo', $mission->num_turn * $i, $mission->coin * $i),
                        'is_done' => $missionDone >= $mission->num_turn * $i,
                        'count_done' => $missionDone
                    ];
                } else {
                    $data[] = [
                        'type' => $mission->type,
                        'coin' => $mission->coin * $i,
                        'num_turn' => $mission->num_turn * $i,
                        'title' => sprintf('Chia sẻ %d bài báo', $mission->num_turn * $i, $mission->coin * $i),
                        'is_done' => $missionDone >= $mission->num_turn * $i,
                        'count_done' => $missionDone
                    ];
                }

            }
        }
        return $data;
    }

    public function missionTurnAdded() {
        $missionVQ = Mission::query()->where('type', Mission::TYPE_SHARE_VQ)->orderByDesc('created_at')->first();
        return $missionVQ? $missionVQ->num_turn : 0;
    }
}
