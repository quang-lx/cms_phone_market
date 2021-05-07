<?php

namespace Modules\Api\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Api\Events\FinishLuckyDraw;
use Modules\Api\Events\MissionDone;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Api\Events\ShopRatingCreated;
use Modules\Api\Events\UserBoughtCard;
use Modules\Api\Events\UserEarnCoin;
use Modules\Api\Events\UserRegisted;
use Modules\Api\Listeners\CheckMissionCompleted;
use Modules\Api\Listeners\GenerateLuckyDrawHistory;
use Modules\Api\Listeners\GenerateUserTurnDaily;
use Modules\Api\Listeners\IncrementUserTurn;
use Modules\Api\Listeners\InsertCardHistory;
use Modules\Api\Listeners\SummaryProductRating;
use Modules\Api\Listeners\SummaryShopRating;
use Modules\Api\Listeners\UpdateGamePointUsed;
use Modules\Api\Listeners\UpdateUserCoin;

class EventServiceProvider extends ServiceProvider {
	protected $listen = [

		ProductRatingCreated::class => [
			SummaryProductRating::class
		],
		ShopRatingCreated::class => [
			SummaryShopRating::class
		]

	];
}
