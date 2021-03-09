<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\ApiRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\AppVersion;
use Modules\Mon\Entities\Card;
use Modules\Mon\Entities\CardConfig;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\Mission;
use Modules\Mon\Entities\News;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\UserMissionDaily;

class CachedEloquentApiRepository implements ApiRepository
{
    public function lastestAppVersion($os) {
        $appVersion = Cache::rememberForever('app_version_'.$os, function () use ($os) {
            return AppVersion::query()->where('os', $os)->orderByDesc('version')->first();
        });
        return $appVersion;
    }
    public function topCoin(Request $request)
    {
        if ($request->get('type', 1) == 1) {
            return User::query()->orderByDesc('weekly_coin')->limit(10)->get();
        } else {
            return User::query()->orderByDesc('total_coin')->limit(10)->get();
        }
    }
    public function cardConfig()
    {
        $cardConfig = Cache::remember('card.config_'. now()->format('Ymd'), now()->addDay(), function ()  {
            return CardConfig::query()
                ->whereDate('from_date', '<=', now()->format('Y-m-d'))
                ->whereDate('to_date', '>=', now()->format('Y-m-d'))->first();
        });
        return $cardConfig;
    }
    public function listCard() {
        $cards = Cache::rememberForever('card.all', function () {
            return Card::query()->get();
        });
        return $cards;
    }
    public function itemsCached()
    {
        $items = Cache::rememberForever('items.all', function () {
            return Item::query()->get();
        });
        return $items;
    }

    public function games()
    {
        $games = Cache::rememberForever('games.all', function () {
            return Game::query()->where('status', Game::STATUS_ACTIVE)->get();
        });
        return $games;
    }

    public function items(Game $game, Request $request)
    {
        $items = Cache::rememberForever('items.game_' . $game->id, function () use ($game) {
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
        });
        return $items;
    }

    public function categories()
    {
        $categories = Cache::rememberForever('categories.all', function () {
            return Category::all();
        });
        return $categories;
    }

    public function newsCached()
    {
        return Cache::rememberForever('news.all', function () {
            return News::query()->orderByDesc('id')->get();
        });
    }

    public function news(Category $category, Request $request)
    {
//        $query = News::query()->where('category_id', $category->id);
//        $query->orderBy('created_at', 'desc');
//        return $query->paginate($request->get('per_page', 10));
        $query = $this->newsCached()->where('category_id', $category->id);
        $query->sortByDesc('id');
        return $query->take($request->get('per_page', 10));
    }

    public function search(Request $request)
    {
        $query = News::query();
        if ($request->get('keyword')) {
            $keyword = $request->get('keyword');
            $query->where('title', 'like', "%$keyword%");
        }
        $query->where('type', News::TYPE_NEWS);
        return $query->paginate($request->get('per_page', 10));

    }

    public function exchangeItems(Game $game)
    {
        $itemsExhange = Cache::rememberForever('item_exhange.game_' . $game->id, function () use ($game) {
            return Item::query()->where('game_id', $game->id)
                ->where(function ($q) {
                    $q->where('number_stone', '>', 0);
                    $q->orWhere('number_map', '>', 0);
                })->get();
        });
        return $itemsExhange;

    }

    public function missionsCached()
    {
        $mission = Cache::rememberForever('mission.all', function () {
            return Mission::query()->get();
        });
        return $mission;
    }

    public function missions(Request $request)
    {

        $missions = $this->missionsCached()->whereIn('type', ['read_news', 'share_news'])->all();
        $data = [];
        $userId = $request->get('user_id');

        foreach ($missions as $mission) {
            $missionDone = UserMissionDaily::query()
                ->whereDate('apply_date', Carbon::now()->format('Y-m-d'))
                ->where('user_id', $userId)
                ->where('mission_type', $mission->type)
                ->count();
            for ($i = 1; $i <= 5; $i++) {
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

    public function missionTurnAdded()
    {
        $missionTurnAdded = Cache::rememberForever('mission_turn_added', function () {
            $missionVQ = Mission::query()->where('type', Mission::TYPE_SHARE_VQ)->orderByDesc('created_at')->first();
            return $missionVQ ? $missionVQ->num_turn : 0;
        });
        return $missionTurnAdded;

    }

    public function validateUserTimeToExchange(User $user, CardConfig $cardConfig) {
        if (!$user->last_charged) {
            return true;
        }
        $exchangeConfigHour = $cardConfig->hour_exchange;
        return Carbon::parse($user->last_charged)->addHours($exchangeConfigHour) < Carbon::now();
    }
}
