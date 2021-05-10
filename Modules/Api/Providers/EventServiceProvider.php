<?php

namespace Modules\Api\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Api\Events\ShopRatingCreated;
use Modules\Api\Events\UserSearched;
use Modules\Api\Listeners\InsertUserSearch;
use Modules\Api\Listeners\SummaryProductRating;
use Modules\Api\Listeners\SummaryShopRating;

class EventServiceProvider extends ServiceProvider {
	protected $listen = [

		ProductRatingCreated::class => [
			SummaryProductRating::class
		],
		ShopRatingCreated::class => [
			SummaryShopRating::class
		],

		UserSearched::class => [
            InsertUserSearch::class
        ]

	];
}
