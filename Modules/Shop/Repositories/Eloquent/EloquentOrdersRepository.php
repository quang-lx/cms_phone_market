<?php

namespace Modules\Shop\Repositories\Eloquent;

use App\Events\OrderStatusUpdated;
use App\Events\ShopNotiCreated;
use App\Events\ShopUpdateOrderStatus;
use Modules\Shop\Repositories\OrdersRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\PaymentHistory;
use Modules\Mon\Entities\PaymentMethod;
use Modules\Mon\Entities\ProductAttributeValue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Api\Entities\ErrorCode;

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

            // tinh phi, doanh thu, tien da tra, s??? tra
            $totalRevenue += $record->tong_tien;
            $totalFee += $record->tong_phi;
            if ($record->payment_status){
                $paid += $record->tong_tien;
            } else {
                $waitPaid += $record->tong_tien;
            }
            //end t??nh phi

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

    //  ????n s???a ch???a th??ng b??o

    public function repairCancel($model, $data)
    {
        if ($model->shop_done == $model::ORDER_DONE_WAIT_CLIENT_CONFIRM && $model->order_type == $model::TYPE_SUA_CHUA) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CANCEL
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content cancel', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content cancel', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_CANCEL,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

    public function repairConfirmed($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CREATED && $model->order_type == $model::TYPE_SUA_CHUA) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CONFIRMED
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content confirm', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_CONFIRMED,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);


    }
    public function repairFixing($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CONFIRMED && $model->order_type == $model::TYPE_SUA_CHUA) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_FIXING
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content fixing', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_FIXING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content fixing', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_FIXING]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CONFIRMED,
		        'new_status' => Orders::STATUS_ORDER_FIXING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
        // $model->update($data_update);
    }

	public function repairFixingOther($model, $data)
    {
		$data_update=[];
        if($data['type']=='sua_chua'){
            $data_update['total_price'] = $data['price'];
            $data_update['pay_price'] = $data['price'];
            $data_update['fix_time'] = $data['numberDate'];
            $data_update['status'] = Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM;
            $data_update['fix_time_date'] = Carbon::now()->addDays($data['numberDate'])->toDateTimeString();
            $model->update($data_update);
			$data_noti = [
				'title' => trans('order.notifications.sua_chua.title'),
				'content' => trans('order.notifications.sua_chua.content fixing other', ['order_code' => $model->id,'time'=>$data['numberDate'].' ng??y','price'=>number_format($data['price']).'??']),
				'fcm_token' => $model->user->fcm_token,
				'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM]),
				'order_id' => $model->id
			];

			event(new ShopNotiCreated($data_noti));
			event(new ShopUpdateOrderStatus([
				'title' => trans('order.notifications.sua_chua.title'),
				'content' => trans('order.notifications.sua_chua.content fixing other', ['order_code' => $model->id,'time'=>$data['numberDate'].' ng??y','price'=>number_format($data['price']).'??']),
				'user_id' => $model->user->id,
				'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM]),
				'order_id' => $model->id
			]));


			event(new OrderStatusUpdated([
				'order_type' => $model->order_type,
				'order_id' => $model->id,
				'title' => $model->status_name,
				'old_status' => Orders::STATUS_ORDER_CONFIRMED,
				'new_status' => Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM,
				'user_id' => null,
				'shop_id' => Auth::user()->shop_id
			]));

        }
	}
    public function repairSending($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_FIXING && $model->order_type == $model::TYPE_SUA_CHUA) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_SENDING
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content sending', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content sending', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_FIXING,
		        'new_status' => Orders::STATUS_ORDER_SENDING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

    public function repairDone($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_SENDING && $model->order_type == $model::TYPE_SUA_CHUA) {
            $data_update =[
                'shop_done' => 1,
                'shop_done_at' => Carbon::now(),
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content done', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_DONE]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content done', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_DONE]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_FIXING,
		        'new_status' => Orders::STATUS_ORDER_FIXING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }





    // ????n h??ng b???o h??nh th??ng b??o

    public function guaranteeCancel($model, $data)
    {
        if ($model->shop_done == $model::ORDER_DONE_WAIT_CLIENT_CONFIRM && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CANCEL
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content cancel', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content cancel', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_CANCEL,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

    public function guaranteeConfirmed($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CREATED && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CONFIRMED
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content confirm', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_CONFIRMED,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);


    }
    public function guaranteeWarranting($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CONFIRMED && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_WARRANTING
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content warranting', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_WARRANTING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content warranting', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_WARRANTING]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CONFIRMED,
		        'new_status' => Orders::STATUS_ORDER_WARRANTING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
        // $model->update($data_update);
    }

    public function guaranteeSending($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_WARRANTING && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_SENDING
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content sending', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content sending', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_FIXING,
		        'new_status' => Orders::STATUS_ORDER_SENDING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

    public function guaranteeDone($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_SENDING && $model->order_type == $model::TYPE_BAO_HANH) {
            $data_update =[
                'shop_done' => 1,
                'shop_done_at' => Carbon::now(),
            ];
	        $model->update($data_update);
	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content done', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_DONE]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content done', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_DONE]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_FIXING,
		        'new_status' => Orders::STATUS_ORDER_FIXING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

    // ????n h??ng mua b??n th??ng b??o

    public function buysellCancel($model, $data)
    {
        if ($model->shop_done == $model::ORDER_DONE_WAIT_CLIENT_CONFIRM && $model->order_type == $model::TYPE_MUA_HANG) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CANCEL
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content cancel', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content cancel', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_CANCEL,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);



    }
	public function buysellConfirmed($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_CREATED && $model->order_type == $model::TYPE_MUA_HANG) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_SENDING
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content confirm', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_CREATED,
		        'new_status' => Orders::STATUS_ORDER_SENDING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);



    }
	public function buysellDone($model, $data)
    {
        if ($model->status == $model::STATUS_ORDER_SENDING && $model->order_type == $model::TYPE_MUA_HANG) {
            $data_update =[
                'shop_done' => 1,
                'shop_done_at' => Carbon::now(),
            ];
	        $model->update($data_update);
	        $data = [
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content done', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_DONE]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.mua_hang.title'),
		        'content' => trans('order.notifications.mua_hang.content done', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.mua_hang.type', ['order_status' => Orders::STATUS_ORDER_DONE]),

		        'user_id' => $model->user->id,
		        'order_id' => $model->id
	        ]));
	        event(new OrderStatusUpdated([
		        'order_id' => $model->id,
		        'order_type' => $model->order_type,
		        'title' => $model->status_name,
		        'old_status' => Orders::STATUS_ORDER_SENDING,
		        'new_status' => Orders::STATUS_ORDER_SENDING,
		        'user_id' => null,
		        'shop_id' => Auth::user()->shop_id
	        ]));

            return response()->json([
                'errors' => false,
                'message' => trans('ch::orders.message.update success'),
            ]);
        }

        return response()->json([
            'errors' => true,
            'message' => 'L???i tr???ng th??i c???p nh???t',
        ],422);
    }

	function stripVN($str) {
		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'a', $str);
		$str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'e', $str);
		$str = preg_replace("/(??|??|???|???|??)/", 'i', $str);
		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'o', $str);
		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'u', $str);
		$str = preg_replace("/(???|??|???|???|???)/", 'y', $str);
		$str = preg_replace("/(??)/", 'd', $str);

		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'A', $str);
		$str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'E', $str);
		$str = preg_replace("/(??|??|???|???|??)/", 'I', $str);
		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'O', $str);
		$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'U', $str);
		$str = preg_replace("/(???|??|???|???|???)/", 'Y', $str);
		$str = preg_replace("/(??)/", 'D', $str);
		$str = str_replace(' ', '_', $str);
		return $str;
	}

	public function getUserByPhone($params) {
    	$query = User::query();
        $user = $query->where('phone', validate_isdn($params['phone']))->first();
        if ($user){
            return $user;
        } else {
            $user = new User();
            $user->phone = validate_isdn($params['phone']);
            $user->name = $params['customer_name'];
            $user->type = User::TYPE_USER;
            $user->status = User::STATUS_INACTIVE;
            $user->username = $this->stripVN($params['customer_name']);
            $user->password = Hash::make(User::DEFAULT_PASS);
            $user->save();
            return $user;
        }
	}

    public function storeBuySell($requestParams)
    {
		DB::beginTransaction();
        try {
			$newUser = $this->getUserByPhone($requestParams);
			$user = Auth::user();

			$paymentHistory = new PaymentHistory();
			$paymentMethod = PaymentMethod::find($requestParams['payment_method']);
			$paymentHistory->user_id = $user->id;
			$paymentHistory->payment_method_id = $requestParams['payment_method'];
			$paymentHistory->pay_amount = $requestParams['total_price'];
			$paymentHistory->pay_method_name = $paymentMethod->name;
			$paymentHistory->save();

			$order = new Orders();
			$order->order_type = Orders::TYPE_MUA_HANG;
			$order->company_id = $user->company_id;
			$order->shop_id = $user->shop_id;
			$order->status = Orders::STATUS_ORDER_DONE;
			$order->payment_status = Orders::PAYMENT_PAID_DONE;
			$order->user_id = $newUser->id;
			$totalPrice = $discount = $payPrice = 0;
			$order->total_price = $requestParams['total_price'];
			$order->discount = 0;
			$order->pay_price = $requestParams['total_price'];
			$order->payment_history_id = $paymentHistory->id;
			$order->save();

			// L??u order_product
			foreach ($requestParams['products'] as $product) {
				$orderProduct = new OrderProduct();
				$orderProduct->order_id = $order->id;
				$orderProduct->product_id = $product['id'];
				if (isset($product['attribute_value_id']) && isset($product['attribute_id'])){
					$orderProduct->product_attribute_value_id  = $this->getProductAttributeValueId($product['attribute_value_id'], $product['attribute_id'], $product['id']);
				}
				$orderProduct->quantity = isset($product['amount']) ? $product['amount'] : 0;
				$orderProduct->price = isset($product['price']) ? $product['price'] : 0;
				$orderProduct->price_sale = isset($product['sale_price']) ? self::getPayPrice($product['price'],$product['sale_price']) : 0;
				$orderProduct->product_title = $product['name'];
				$orderProduct->save();
			}

			DB::commit();

		} catch (\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return [trans('api::messages.order.internal server error'), ErrorCode::ERR500];
        }
    }

	public function storeRepair($requestParams)
    {
		DB::beginTransaction();
        try {
			$newUser = $this->getUserByPhone($requestParams);
			$user = Auth::user();

			$paymentHistory = new PaymentHistory();
			$paymentMethod = PaymentMethod::find($requestParams['payment_method']);
			$paymentHistory->user_id = $user->id;
			$paymentHistory->payment_method_id = $requestParams['payment_method'];
			$paymentHistory->pay_amount = $requestParams['total_price'];
			$paymentHistory->pay_method_name = $paymentMethod->name;
			$paymentHistory->save();

			$order = new Orders();
			$order->order_type = Orders::TYPE_SUA_CHUA;
			$order->company_id = $user->company_id;
			$order->shop_id = $user->shop_id;
			$order->status = Orders::STATUS_ORDER_DONE;
			$order->payment_status = Orders::PAYMENT_PAID_DONE;
			$order->user_id = $newUser->id;
			$totalPrice = $discount = $payPrice = 0;
			$order->total_price = $requestParams['total_price'];
			$order->discount = 0;
			$order->pay_price = $requestParams['total_price'];
			$order->payment_history_id = $paymentHistory->id;
			$order->save();

			// L??u order_product
			foreach ($requestParams['products'] as $product) {
				$orderProduct = new OrderProduct();
				$orderProduct->order_id = $order->id;
				$orderProduct->product_id = isset($product['id']) ? $product['id'] : null;
				if (isset($product['attribute_value_id']) && isset($product['attribute_id'])){
					$orderProduct->product_attribute_value_id  = $this->getProductAttributeValueId($product['attribute_value_id'], $product['attribute_id'], $product['id']);
				}

				if ($requestParams['type_product'] == Orders::TYPE_SUA_CHUA_PRODUCT_EXIST){
					$orderProduct->quantity = isset($product['amount']) ? $product['amount'] : 0;
				} else {
					$orderProduct->quantity = 1; //????n s???n ph???m kh??c ko ch???n s??? l?????ng, default = 1
				}
				$orderProduct->price = isset($product['price']) ? $product['price'] : 0;
				$orderProduct->price_sale = isset($product['sale_price']) ? self::getPayPrice($product['price'],$product['sale_price']) : 0;
				$orderProduct->product_title = isset($product['product_title']) ? $product['product_title'] : $product['name'];
				$orderProduct->note = isset($product['product_detail']) ? $product['product_detail'] : null;
				$orderProduct->save();
			}

			DB::commit();

		} catch (\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return [trans('api::messages.order.internal server error'), ErrorCode::ERR500];
        }

    }

	public function getProductAttributeValueId($product_attribute_value_id, $attribute_id, $product_id){
		if ($product_attribute_value_id && $attribute_id && $product_id){
			$record = ProductAttributeValue::query()->where('value_id', $product_attribute_value_id)
				->where('attribute_id', $attribute_id)->where('product_id', $product_id)->first();
			return $record->id;
		} else {
			return '';
		}

	}

	public static function getPayPrice($basePrice, $discount){
		return round(((100 - $discount)/100) * $basePrice);
	}

}
