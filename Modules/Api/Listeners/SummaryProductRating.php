<?php

namespace Modules\Api\Listeners;

use App\Events\NeedCreateUserSmsToken;
use App\Services\SendSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\SmsToken;

class SummaryProductRating implements ShouldQueue
{



    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProductRatingCreated $event)
    {
        $productId = $event->product_id;
        $query = Rating::query()->where('product_id', $productId);
        $avg = $query->avg('rating');
        $total = $query->count();
        Product::query()->where('id', $productId)->update([
            'rating_avg' => $avg,
            'rating_user' => $total,
        ]);
    }
}
