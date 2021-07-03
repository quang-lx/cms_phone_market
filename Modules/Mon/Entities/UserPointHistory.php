<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


/**
 * Modules\Mon\Entities\Company
 *
 * @property int $id
 * @property string $user_id
 * @property string|null $order_id
 * @property int|null $product_id
 * @property int|null $product_name
 * @property int|null $title
 * @property int|null $point
 * @property int|null $created_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @mixin \Eloquent
 */
class UserPointHistory extends Model
{

    const STATUS_LOCK = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'user_point_history';
    protected $fillable = [
        'user_id', 'order_id', 'product_id', 'product_name', 'title', 'point'
    ];

	public static function createFromOrder($order) {
		try {
			if ($order && $order->order_type == Orders::TYPE_MUA_HANG) {
				$orderProducts = $order->orderProducts;
				foreach ($orderProducts as $orderProduct) {
					$productName = $orderProduct->product_title;
					$productId = $orderProduct->product_id;
					$price = $orderProduct->price_sale;
					$title = "Mua hàng sản phẩm ". $productName;
					$point = $price/100000;
					UserPointHistory::create([
						'user_id' => $order->user_id,
						'order_id'=> $order->id,
						'product_id'=> $productId,
						'product_name' =>$productName,
						'title' => $title,
						'point' => (int)$point
					]);
				}
			}
		} catch (\Exception $exception) {
			Log::error($exception->getMessage());
		}

	}
}
