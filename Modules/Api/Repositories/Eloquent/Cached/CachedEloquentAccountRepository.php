<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use App\Services\GachTheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Modules\Api\Events\FinishLuckyDraw;
use Modules\Api\Events\UserBoughtCard;
use Modules\Api\Events\UserEarnCoin;
use Modules\Api\Repositories\AccountRepository;
use Modules\Mon\Entities\Card;
use Modules\Mon\Entities\CardConfig;
use Modules\Mon\Entities\CardHistory;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\GameConfig;
use Modules\Mon\Entities\GamePartion;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\LuckyDrawHistory;
use Modules\Mon\Entities\Partition;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\UserExchangeItem;
use Modules\Mon\Entities\UserFcm;
use Modules\Mon\Entities\UserTurnDaily;


class CachedEloquentAccountRepository implements AccountRepository
{
    public function update($model, $data)
    {
        $model->update($data);
        if (isset($data['fcm_token'])) {
            UserFcm::create([
                'user_id' => $model->id,
                'fcm_token' => $data['fcm_token']
            ]);
        }
        return $model;
    }

    public function checkShowAds($userTurnDaily, Game $game, Request $request)
    {
        $userTurns = $userTurnDaily['turns'];
        $userTurns++;
        if ($game->turn_show_ads && ($userTurns % $game->turn_show_ads) == 0) {
            $userTurnDaily['turns'] = $userTurns;
            $this->updateUserTurnDaily($userTurnDaily['user_id'], $userTurnDaily['game_id'], $userTurnDaily);
            return true;
        }
        return false;
    }

    public function allowTurn($userTurnDaily, Game $game, Request $request)
    {
        if (($userTurnDaily['turns'] + $request->get('number_turns', 1)) <= $userTurnDaily['free_turn']) {
            return true;
        }
        return false;
    }

    public function luckDrawMultiple(User $user, $userTurnDaily, Game $game, Request $request)
    {
        //TODO check logic quay nhieu lan
        $numberTurns = $request->get('number_turns', 1);
        $group = Str::uuid();
        $luckyItems = [];


        $luckyItems = $this->luckDrawBlock($user, $userTurnDaily, $game, $numberTurns);
        // add bonus item
        if ($numberTurns == 10) {
            $bonusItem = $this->getRandomItemForLuckyDraw($game->id);
            $luckyItems[] = $bonusItem;

        }


        return $luckyItems;
    }

    public function gamePartitions()
    {
        /** @var Collection $gamePartitions */
        $gamePartitions = Cache::rememberForever('game_partition.all', function () {
            return GamePartion::get();
        });
        return $gamePartitions;
    }

    public function selectGamePartition(Game $game, $userTurns)
    {
        $partitionIds = $this->getGamePartionsId($game->id);
        return $this->getPartition($partitionIds, $userTurns);
    }

    public function getGamePartionsId($gameId)
    {
        $gamePartitions = $this->gamePartitions();
        return $gamePartitions->where('game_id', $gameId)->pluck('partition_id')->all();
    }

    public function getPartition($partitionIds, $userTurns)
    {
        /** @var Collection $gamePartitions */
        $gamePartitions = Cache::rememberForever('_partitions.all', function () {
            return Partition::get();
        });

        $partition = $gamePartitions->whereIn('id', $partitionIds)->where('turn', '<=', $userTurns)->sortByDesc('turn')->first();
        if (!$partition) {
            $partition = $gamePartitions->whereIn('id', $partitionIds)->where('turn', '>', $userTurns)->sortBy('turn')->first();
        }
        return $partition;
    }

    public function items($gameId = null)
    {
        /** @var Collection $items */
        $items = Cache::rememberForever('items.all', function () {
            return Item::get();
        });

        if ($gameId) {
            $items = $items->where('game_id', $gameId);
        }
        return $items;
    }

    /**
     * @param $date
     * @param $userId
     * @param $gameId
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function userTurnDaily($userId, $gameId)
    {
        /** @var Collection $userTurnDaily */
        $userTurnDaily = Cache::remember('user_turn_daily_' . $userId . '_' . $gameId, now()->addDay()->startOfDay(), function () use ($userId, $gameId) {
            return UserTurnDaily::query()->where('game_id', $gameId)->where('user_id', $userId)->first()->toArray();
        });
        return $userTurnDaily;
    }

    public function updateUserTurnDaily($userId, $gameId, $userTurnDaily)
    {
        Cache::put('user_turn_daily_' . $userId . '_' . $gameId, $userTurnDaily, now()->addDay()->startOfDay());
    }


    public function gameConfig()
    {
        /** @var Collection $gameConfigs */
        $gameConfigs = Cache::remember('game_config', now()->addDay()->startOfDay(), function () {
            return GameConfig::get();
        });

        return $gameConfigs;
    }

    public function getGameLuckyItem($gameId)
    {
        $items = $this->items($gameId);
        return $items->where('is_lucky', 1)->first();
    }


    public function luckDraw(User $user, $userTurnDaily, Game $game, Request $request, $group = null)
    {
        $resultItem = null;

        $userTurns = $userTurnDaily->turns;
        $userLuckTurns = $userTurnDaily->lucky_turn_daily;
        $userLuckTurns++;
        $userTurns++;
        // check game has luck item && user turns > lucky
        $luckyItem = $this->getGameLuckyItem($game->id);
        if ($luckyItem && $userLuckTurns >= $game->lucky && $game->lucky > 0) {
            $luckyPointRandom = mt_rand(0, $game->lucky * 2);
            // return lucky item
            $resultItem = $luckyItem;
        }

        // fail to get luck item

        if ($resultItem == null) {
            //check normal item
            // get partition
            $partition = $this->selectGamePartition($game, $userTurns);
            $luckyPointRandom = 0;
            if (!$partition) {
                $resultItem = $this->getRandomItemForLuckyDraw($game->id);
            } else {
                $totalPoint = 0;

                $gamePartition = $this->gamePartitions()->where('game_id', $game->id)->where('partition_id', $partition->id)->all();

                foreach ($gamePartition as $partitionItem) {
                    $item = $this->items()->where('id', $partitionItem->item_id)->first();
                    $itemPoint = $item->lucky_weight * $partitionItem->quantity;
                    $totalPoint += $itemPoint;

                }

                $luckyPointRandom = mt_rand(0, $totalPoint);
                $totalPoint = 0;
                foreach ($gamePartition as $partitionItem) {
                    $item = $this->items()->where('id', $partitionItem->item_id)->first();
                    $itemPoint = $item->lucky_weight * $partitionItem->quantity;
                    $totalPoint += $itemPoint;
                    if ($luckyPointRandom <= $totalPoint) {
                        $resultItem = $item;
                        break;
                    }
                }
            }


        }
        if ($resultItem && !$this->checkGameDailyPoint($game, $resultItem->coin)) {
            $resultItem = $this->getRandomItemForLuckyDraw($game->id);
        }

        $this->updateUserInfo($user, $resultItem->coin, [$resultItem]);

        // update user game turn daily
        if ($resultItem && $resultItem->is_lucky) {
            // if get lucky item -> set user turn = 0
            $userTurnDaily->update([

                'lucky_turn_daily' => 0,
                'lucky_turn_reset' => $userTurnDaily->lucky_turn_reset + 1
            ]);

        }

        //
        return $resultItem;

    }

    public function luckDrawBlock(User $user, $userTurnDaily, Game $game, $originalTurn)
    {

        $numberTurn = $originalTurn;
        $resultItem = [];
        $hasLuckyItem = false;
        $totalCoin = 0;
        $totalPoint = 0;

        $userTurnDaily['turns'] = $userTurnDaily['turns'] + $numberTurn;
        $userTurnDaily['lucky_turn_daily'] = $userTurnDaily['lucky_turn_daily'] + $numberTurn;

        $userTurns = $userTurnDaily['turns'];
        $userLuckTurns = $userTurnDaily['lucky_turn_daily'];
        // check game has luck item && user turns > lucky
        $luckyItem = $this->getGameLuckyItem($game->id);
        if ($luckyItem && $userLuckTurns >= $game->lucky && $game->lucky > 0) {
            // return lucky item
            $resultItem[] = $luckyItem;
            $hasLuckyItem = true;
            $numberTurn--;
            $totalCoin = $luckyItem->coin;
        }

        // fail to get luck item

        $partition = $this->selectGamePartition($game, $userTurns);
        $luckyPointRandom = 0;
        if (!$partition) {
            $resultItem = $this->getRandomItemForLuckyDrawBlock($game->id, $originalTurn);
        } else {

            $gamePartition = $this->gamePartitions()->where('game_id', $game->id)->where('partition_id', $partition->id)->all();

            // sum total point via item quatity and weight
            $items = [];
            foreach ($gamePartition as $partitionItem) {
                $item = $this->items()->where('id', $partitionItem->item_id)->first();

                $itemPoint = $item->lucky_weight * $partitionItem->quantity;
                $totalPoint += $itemPoint;
                $items[] = [
                    'item' => $item,
                    'item_point' => $itemPoint
                ];

            }

            //
            for ($i = 0; $i < $numberTurn; $i++) {
                $luckyPointRandom = mt_rand(0, $totalPoint);
                $turnPoint = 0;
                //shuffle
                shuffle($items);

                foreach ($items as $itemCache) {
                    $item = $itemCache['item'];
                    $itemPoint = $itemCache['item_point'];
                    $turnPoint += $itemPoint;
                    if ($luckyPointRandom <= $turnPoint) {
                        $resultItem[] = $item;
                        $totalCoin += $item->coin;
                        break;
                    }
                }

            }


            if (!$this->checkGameDailyPoint($game, $totalCoin)) {
                $resultItem = $this->getRandomItemForLuckyDrawBlock($game->id, $originalTurn);

            } else {
                foreach ($resultItem as $item) {
                    if ($item->is_lucky) {
                        $hasLuckyItem = true;
                    }
                }

            }
        }

        // update user game turn daily
        if ($hasLuckyItem) {
            $userTurnDaily['lucky_turn_daily'] = 0;
            $userTurnDaily['lucky_turn_reset'] = $userTurnDaily['lucky_turn_reset'] + 1;

        }
        $this->updateUserTurnDaily($userTurnDaily['user_id'], $userTurnDaily['game_id'], $userTurnDaily);
        $this->updateUserInfo($user, $totalCoin, $resultItem);
        //
        return $resultItem;
    }

    public function checkGameDailyPoint(Game $game, $coin)
    {
        $coinInCached = Cache::get('game_' . $game->id . '_' . now()->format('Ymd'), 0);
        $coinInCached = $coinInCached ? $coinInCached : 0;
//		$gameConfig = GameConfig::query()->whereDate('apply_date', Carbon::now()->format('Y-m-d'))->where('game_id', $game->id)->first();
        $gameConfig = $this->gameConfig()->where('apply_date', '<=', Carbon::now()->format('Y-m-d'))->where('game_id', $game->id)->sortByDesc('apply_date')->first();
        if ($gameConfig && $gameConfig->coin > ($coinInCached + $coin)) {
            Cache::increment('game_' . $game->id . '_' . now()->format('Ymd'), ($coin));
            return true;
        }

        return false;
    }

    public function exchangeItem(User $user, Item $item, Request $request)
    {

        // number item: -1: doi all, default 1
        $number_item = $request->get('number_item', 1);

        $stoneToExchange = 0;
        $mapToExchange = 0;
        $rate = 0;


        if ($number_item == -1) {
            if ($item->number_stone == 0 && $item->number_map > 0) {
                $number_item = floor($user->total_map / $item->number_map);
            } elseif ($item->number_map == 0 && $item->number_stone > 0) {
                $number_item = floor($user->total_stone / $item->number_stone);
            } else {
                $number_item = 0;
            }

        }
        if ($user->total_stone >= $item->number_stone * $number_item && $user->total_map >= $item->number_map * $number_item) {
            $stoneToExchange = $number_item * $item->number_stone;
            $user->total_stone -= $stoneToExchange;
            $mapToExchange = $number_item * $item->number_map;
            $user->total_map -= $mapToExchange;
            $rate = $number_item;
        }

        //incre user coin
        $user->coin += $item->coin * $number_item;
        $user->save();

        // save history
//        UserExchangeItem::query()->create([
//            'user_id' => $user->id,
//            'game_id' => $item->game_id,
//            'item_id' => $item->id,
//            'total_stone' => $stoneToExchange,
//            'total_map' => $mapToExchange,
//            'total_item' => $number_item,
//            'rate' => $rate,
//            'note' => ''
//        ]);

        return [
            'number_item' => $number_item,
            'exchanged_coin' => $item->coin * $number_item,
        ];
    }

    public function luckyDrawItems(User $user, Game $game, Request $request)
    {
        $query = LuckyDrawHistory::query()->with(['item'])
            ->select(DB::raw('sum(1) as total, item_id'))
            ->where('game_id', $game->id)
            ->where('user_id', $user->id)
            ->groupBy('item_id');
        return $query->get();
    }

    /**
     * @return Item|null
     */
    public function getRandomItemForLuckyDraw($gameId)
    {
        return $this->items($gameId)->where('type', Item::TYPE_ITEM)->where('coin', 0)->random(1)->first();
    }

    public function getRandomItemForLuckyDrawBlock($gameId, $numberBlock)
    {
        return $this->items($gameId)->where('type', Item::TYPE_ITEM)->where('coin', 0)->random($numberBlock)->get();
    }

    public function updateUserInfo(User $user, $coin, $items)
    {
        $totalMap = 0;
        $totalStone = 0;
        foreach ($items as $item) {
            if ($item->type == Item::TYPE_MAP) {
                $totalMap++;
            } elseif ($item->type == Item::TYPE_STONE) {
                $totalStone++;
            }
        }
        if ($coin || $totalMap || $totalStone) {
            event(new UserEarnCoin([
                'user_id' => $user->id,
                'coin' => $coin,
                'total_map' => $totalMap,
                'total_stone' => $totalStone,
            ]));
        }
    }
    public function getTotalExchangeKey() {
        $cacheTotalChargedKey = '';
        if (now() < now()->startOfDay()->addHours(12)) {
            $cacheTotalChargedKey='total_exchanged_'. now()->format('Ymd');
        } else {
            $cacheTotalChargedKey='total_exchanged_'. now()->addDay()->format('Ymd');
        }
        return $cacheTotalChargedKey;
    }
    public function exchangeCard(User $user, Card $card)
    {
        $cardInfoData = [];
        $hasSuccess = false;
        try {
            // add total
            $cacheTotalChargedKey = $this->getTotalExchangeKey();
            $currentCharged = Cache::get($cacheTotalChargedKey, 0);
            Cache::put($cacheTotalChargedKey, $currentCharged + $card->amount, now()->addDay()->startOfDay()->addHours(12));
            // add total
            $sign = '';
            $responseSource = '';
            $status = '';

            /** @var GachTheService $gachTheService */
            $gachTheService = app(GachTheService::class);
            $buyCardResult = $gachTheService->chargingCard($user, $card);
            if ($buyCardResult === false) {
                $sign= '';
                $status= '500';

            } else {
                if (isset($buyCardResult['sign'])) {
                    $sign = $buyCardResult['sign'];
                }
                if(isset($buyCardResult['bodyResponse'])) {

                    $napthenhanhResponse = $buyCardResult['bodyResponse'];
                    $cardInfo = \GuzzleHttp\json_decode($napthenhanhResponse);
                    $status = $cardInfo->status;
                    $responseSource= (string)$napthenhanhResponse;

                    // check status
                    if ($status == 1) {
                        $hasSuccess = true;
                    }
                }
            }
            // insert card history
            event(new UserBoughtCard([
                'user_id' => $user->id,
                'card_id' => $card->id,
                'amount' => $card->amount,
                'coin_amount' => $card->coin_amount,
                'type' => $card->type,
                'status' => $status,
                'response_source' => $responseSource,
                'sign' => $sign
            ]));

            //finish update user coin
            if ($hasSuccess) {
                event(new UserEarnCoin([
                    'user_id' => $user->id,
                    'coin' => - $card->coin_amount,
                    'total_map' => 0,
                    'total_stone' => 0,
                    'update_charge' => true
                ]));
            } else {
                Cache::decrement($cacheTotalChargedKey, $card->amount);

            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        return [
            'success' => $hasSuccess,
            'response_source' => $responseSource
        ];

    }
    public function exchangeHistory(User $user, Request $request) {
        $query = CardHistory::query();
        $query->where('user_id', $user->id);
        $query->orderByDesc('id');
        return $query->paginate($request->get('per_page', 10));
    }

}
