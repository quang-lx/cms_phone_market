<?php

namespace Modules\Api\Repositories;

use Illuminate\Http\Request;
use Modules\Mon\Entities\CardConfig;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\User;

interface ApiRepository
{
    public function games();

    public function search(Request $request);

    public function items(Game $game, Request $request);
    public function itemsCached();

    public function exchangeItems(Game $game);

    public function categories();

    public function missions(Request $request);

    public function news(Category $category, Request $request);

    public function missionTurnAdded();
    public function newsCached();

    public function listCard();
    public function topCoin(Request $request);
    public function cardConfig();
    public function lastestAppVersion($os);
    public function validateUserTimeToExchange(User $user, CardConfig $cardConfig);
}
