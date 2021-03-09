<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Modules\Api\Events\FinishLuckyDraw;
use Modules\Api\Repositories\AccountRepository;
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


class EloquentAccountRepository implements AccountRepository
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

    public function checkShowAds(UserTurnDaily $userTurnDaily, Game $game, Request $request)
    {
        $userTurns = $userTurnDaily->turns;
        $userTurns++;
        if ($game->turn_show_ads && ($userTurns % $game->turn_show_ads) == 0) {
            $userTurnDaily->turns = $userTurns;
            $userTurnDaily->save();
            return true;
        }
        return false;
    }

    public function allowTurn(UserTurnDaily $userTurnDaily, Game $game, Request $request)
    {
        if (($userTurnDaily->turns + $request->get('number_turns', 1)) <= $userTurnDaily->free_turn) {
            return true;
        }
        return false;
    }

    public function luckDrawMultiple(UserTurnDaily $userTurnDaily, Game $game, Request $request)
    {
        //TODO check logic quay nhieu lan
        $numberTurns = $request->get('number_turns', 1);
        $group = Str::uuid();
        $luckyItems = [];
        for ($i = 0; $i < $numberTurns; $i++) {
            $item = $this->luckDraw($userTurnDaily, $game, $request, $group);
            $luckyItems[] = $item;
        }
        // add bonus item
        if ($numberTurns == 10) {
            $bonusItem = $game->getRandomItemForLuckyDraw();
            $luckyItems[] = $bonusItem;
            event(new FinishLuckyDraw([
                'user_id' => $userTurnDaily->user_id,
                'game_id' => $game->id,
                'item_id' => optional($bonusItem)->id,
                'turns' => 0,
                'lucky_point' => 0,
                'lucky_turn_daily' => 0,
                'group' => $group,
                'is_bonus' => 1,
                'note' => ''
            ]));
        }

        return $luckyItems;
    }

    public function selectGamePartition(Game $game, $userTurns)
    {
        $partitionIds = GamePartion::query()->where('game_id', $game->id)->select(DB::raw('distinct partition_id'));
        $partition = Partition::query()->whereIn('id', $partitionIds)->where('turn', '<=', $userTurns)->orderByDesc('turn')->first();
        if (!$partition) {
            $partition = Partition::query()->whereIn('id', $partitionIds)->where('turn', '>', $userTurns)->orderBy('turn')->first();
        }
        return $partition;
    }

    public function luckDraw(UserTurnDaily $userTurnDaily, Game $game, Request $request, $group = null)
    {
        $resultItem = null;

        $userTurns = $userTurnDaily->turns;
        $userLuckTurns = $userTurnDaily->lucky_turn_daily;
        $userLuckTurns++;
        $userTurns++;
        // check game has luck item && user turns > lucky
        $luckyItem = $game->items()->where('is_lucky', 1)->first();
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
                $resultItem = $game->getRandomItemForLuckyDraw();
            } else {
                $totalPoint = 0;
                $gamePartition = GamePartion::query()->where('game_id', $game->id)->where('partition_id', $partition->id)->with('item')->inRandomOrder()->get();
                foreach ($gamePartition as $partitionItem) {
                    $itemPoint = $partitionItem->item->lucky_weight * $partitionItem->quantity;
                    $totalPoint += $itemPoint;

                }

                $luckyPointRandom = mt_rand(0, $totalPoint);

                $totalPoint = 0;
                foreach ($gamePartition as $partitionItem) {
                    $itemPoint = $partitionItem->item->lucky_weight * $partitionItem->quantity;
                    $totalPoint += $itemPoint;
                    if ($luckyPointRandom <= $totalPoint) {
                        $resultItem = $partitionItem->item;
                        break;
                    }
                }
            }


        }
        if ($resultItem && !$this->checkGameDailyPoint($game, $resultItem)) {
            $resultItem = $game->getRandomItemForLuckyDraw();
        }

        event(new FinishLuckyDraw([
            'user_id' => $userTurnDaily->user_id,
            'game_id' => $game->id,
            'item_id' => optional($resultItem)->id,
            'turns' => $userTurns,
            'lucky_point' => $luckyPointRandom,
            'lucky_turn_daily' => $userLuckTurns,
            'group' => $group,
            'note' => ''
        ]));

        // update user game turn daily
        if ($resultItem && $resultItem->is_lucky) {
            // if get lucky item -> set user turn = 0
            $userTurnDaily->update([
                'turns' => $userTurns,
                'lucky_turn_daily' => 0,
                'lucky_turn_reset' => $userTurnDaily->lucky_turn_reset + 1
            ]);

        } else {
            $userTurnDaily->update([
                'turns' => $userTurns,
                'lucky_turn_daily' => $userLuckTurns
            ]);
        }


        //
        return $resultItem;

    }

    public function checkGameDailyPoint(Game $game, Item $itemReceived)
    {
        $coinInCached = Redis::get('game_' . $game->id);
        $coinInCached = $coinInCached ? $coinInCached : 0;
        Redis::set('game_' . $game->id, ($coinInCached + $itemReceived->coin));
//		$gameConfig = GameConfig::query()->whereDate('apply_date', Carbon::now()->format('Y-m-d'))->where('game_id', $game->id)->first();
        $gameConfig = GameConfig::query()->whereDate('apply_date', '<=', Carbon::now()->format('Y-m-d'))->where('game_id', $game->id)->orderByDesc('apply_date')->first();

        if ($gameConfig && $gameConfig->coin > ($gameConfig->coin_used + $itemReceived->coin)) {
            return true;
        }

        Redis::set('game_' . $game->id, $coinInCached);
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
        UserExchangeItem::query()->create([
            'user_id' => $user->id,
            'game_id' => $item->game_id,
            'item_id' => $item->id,
            'total_stone' => $stoneToExchange,
            'total_map' => $mapToExchange,
            'total_item' => $number_item,
            'rate' => $rate,
            'note' => ''
        ]);

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
}
