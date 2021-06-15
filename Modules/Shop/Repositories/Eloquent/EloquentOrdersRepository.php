<?php

namespace Modules\Shop\Repositories\Eloquent;

use App\Events\OrderStatusUpdated;
use App\Events\ShopNotiCreated;
use Modules\Shop\Repositories\OrdersRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Mon\Entities\Orders;

class EloquentOrdersRepository extends BaseRepository implements OrdersRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $search = $request->get('search');
            $query->where('id', 'LIKE', "%{$search}%");
            $query->orWhereHas('user', function ($c) use ($search) {
                $c->where('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->get('searchDate') !== null) {
            $searchDate = $request->get('searchDate');
            $query->where(function ($c) use ($searchDate) {
                $c->where('created_at', '>=', $searchDate[0]);
                $c->where('created_at', '<=', $searchDate[1]);
            });

        }

        if ($request->get('shop') !== null) {
            $shop = $request->get('shop');
            $query->where(function ($c) use ($shop) {
                $c->where('shop_id', '=', $shop);
            });

        }

        if ($request->get('order_type') !== null) {
            $order_type = $request->get('order_type');
            $query->where(function ($c) use ($order_type) {
                $c->where('order_type', '=', $order_type);
            });

        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where(function ($c) use ($status) {
                $c->where('status', '=', $status);
            });

        }

		$user = Auth::user();
		$query->where('company_id', $user->company_id);
        if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}


        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

    public function update($model, $data)
    {
        $data_update=[];
        if($data['type']=='sua_chua'){
            $data_update['total_price'] = $data['price'];
            $data_update['pay_price'] = $data['price'];
            $data_update['fix_time'] = $data['numberDate'];
            $data_update['fix_time_date'] = Carbon::now()->addDays($data['numberDate'])->toDateTimeString();
            $data_update['status'] = Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM;

			$data = [
				'title' => trans('order.notifications.sua_chua.title'),
				'content' => trans('order.notifications.sua_chua.content confirm', ['order_code' => $model->id]),
				'fcm_token' => $model->user->fcm_token,
				'type' => trans('order.notifications.sua_chua.type', ['order_status' => $model->status]),
			];

			event(new ShopNotiCreated($data));


			event(new OrderStatusUpdated([
				'order_id' => $model->id,
				'title' => $model->status_name,
				'old_status' => Orders::STATUS_ORDER_CREATED,
				'new_status' => Orders::STATUS_ORDER_WARRANTING,
				'user_id' => '',
				'shop_id' => Auth::user()->shop_id
			]));
        }

        $model->update($data_update);
    }
    public function statistical(Request $request, $relations = null)
    {
        // select count(*) AS so_don,`order_type`,sum(`pay_price`) AS tong_tien, date(`created_at`) from `orders`
        //  where `company_id` = 3 and `orders`.`deleted_at` is null group by `order_type`, date(`created_at`)
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->selectRaw('order_type, pay_price AS tong_tien, date(created_at) AS ngay_tao, payment_status, ship_fee AS tong_phi');

		$user = Auth::user();
		$query->where('company_id', $user->company_id);
        if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}

        // $query->groupBy('order_type', 'ngay_tao');
        $data =  $query->orderBy('ngay_tao', 'asc')->get();
        $listDate = [];
        $listRepairCount = [];
        $listRepairMoney = [];
        $listGuaranteeCount = [];
        $listGuaranteeMoney = [];
        $listBuyCount = [];
        $listBuyMoney = [];
        $key = 0;
        $totalRevenue = 0;
        $paid = 0;
        $waitPaid = 0;
        $totalFee = 0;

        foreach ($data as $record){
            $date = date('d-m-Y', strtotime($record->ngay_tao));
            $orderType = $record->order_type;

            // tinh phi, doanh thu, tien da tra, sẽ tra
            $totalRevenue += $record->tong_tien;
            $totalFee += $record->tong_phi;
            if ($record->payment_status){
                $paid += $record->tong_tien;
            } else {
                $waitPaid += $record->tong_tien;
            }
            //end tính phi

            if (!in_array($date, $listDate)){
                $listDate[$key] = $date;
                $listRepairCount[$key] = $listRepairMoney[$key] = $listGuaranteeCount[$key] =
                    $listGuaranteeMoney[$key] = $listBuyCount[$key] = $listBuyMoney[$key] = 0;
                $curKey = $key;
                $key ++;
            }

            switch ($orderType){
                case Orders::TYPE_SUA_CHUA: {
                    $listRepairCount[$curKey] ++;
                    $listRepairMoney[$curKey] += $record->tong_tien;
                    break;
                }
                case Orders::TYPE_BAO_HANH: {
                    $listGuaranteeCount[$curKey] ++;
                    $listGuaranteeMoney[$curKey] += $record->tong_tien;
                    break;
                }
                case Orders::TYPE_MUA_HANG: {
                    $listBuyCount[$curKey] ++;
                    $listBuyMoney[$curKey] += $record->tong_tien;
                    break;
                }
                default:
            }
        }
        return array(
            'listDate' => $listDate,
            'listRepairCount' => $listRepairCount, 'listRepairMoney' => $listRepairMoney,
            'listGuaranteeCount' => $listGuaranteeCount, 'listGuaranteeMoney' => $listGuaranteeMoney,
            'listBuyCount' => $listBuyCount, 'listBuyMoney' => $listBuyMoney,
            'totalRevenue' => $totalRevenue, 'paid' => $paid,
            'waitPaid' => $waitPaid, 'totalFee' => $totalFee
        );

    }

    public function updateGuarantee($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CREATED && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_WARRANTING
            ];
	        $model = $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => $model->status]),
	        ];

	        event(new ShopNotiCreated($data));


	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_WARRANTING,
		        'user_id' => '',
		        'shop_id' => Auth::user()->shop_id
	        ]));
            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'Lỗi trạng thái cập nhật',
        ],422);



    }

    public function updateBuySell($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CREATED && $model->order_type == $model::TYPE_MUA_HANG) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CONFIRMED
            ];
	        $model = $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.ban_hang.title'),
		        'content' => trans('order.notifications.ban_hang.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'type' => trans('order.notifications.ban_hang.type', ['order_status' => $model->status]),
	        ];

	        event(new ShopNotiCreated($data));

	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_WARRANTING,
		        'user_id' => '',
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'Lỗi trạng thái cập nhật',
        ],422);



    }
}
