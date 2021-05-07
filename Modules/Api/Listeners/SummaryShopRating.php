<?php

namespace Modules\Api\Listeners;

use App\Events\NeedCreateUserSmsToken;
use App\Services\SendSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Api\Events\ShopRatingCreated;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\RatingShop;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\SmsToken;

class SummaryShopRating implements ShouldQueue
{



    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ShopRatingCreated $event)
    {
        $shopId = $event->shop_id;
        $query = RatingShop::query()->where('shop_id', $shopId);
        $avg = $query->avg('rating');
        $total = $query->count();
        Shop::query()->where('id', $shopId)->update([
            'rating_avg' => $avg,
            'rating_user' => $total,
        ]);
    }
}
