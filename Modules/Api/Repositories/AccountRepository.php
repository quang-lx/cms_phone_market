<?php

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Mon\Entities\Card;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\UserTurnDaily;

interface AccountRepository {
    public function update($model, $data);

    public function luckDraw(User $user, $userTurnDaily, Game $game, Request $request, $group = null);

    public function luckDrawMultiple(User $user, $userTurnDaily, Game $game, Request $request);

    public function checkShowAds($userTurnDaily, Game $game, Request $request);

    public function allowTurn($userTurnDaily, Game $game, Request $request);

    public function exchangeItem(User $user, Item $item, Request $request);

    public function luckyDrawItems(User $user, Game $game, Request $request);

    public function items($gameId = null);

    public function gameConfig();

    /**
     * @param $date
     * @return Collection
     */
    public function userTurnDaily($userId, $gameId);


    public function updateUserTurnDaily($userId, $gameId, $userTurnDaily);

    public function exchangeCard(User $user, Card $card);
    public function exchangeHistory(User $user, Request $request);

    public function getTotalExchangeKey();
}
