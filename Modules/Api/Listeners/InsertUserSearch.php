<?php

namespace Modules\Api\Listeners;

use App\Events\NeedCreateUserSmsToken;
use App\Services\SendSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Api\Events\ShopRatingCreated;
use Modules\Api\Events\UserSearched;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\RatingShop;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\UserSearch;

class InsertUserSearch implements ShouldQueue
{



    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserSearched $event)
    {
        $userId = $event->user_id;
        $fcmToken = $event->fcm_token;
        $products = $event->products;

        if ($userId) {
            UserSearch::query()->where('user_id', $userId)->delete();
        }
        if ($fcmToken) {
            UserSearch::query()->where('fcm_token', $fcmToken)->delete();
        }

        foreach ($products as $product) {
            UserSearch::create([
               'user_id' => $userId,
               'fcm_token' => $fcmToken,
               'product_id' => $product,
            ]);
        }
    }
}
