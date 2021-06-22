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
use Illuminate\Support\Facades\Hash;

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

    //  đơn sửa chữa thông báo

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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            $data_update['status'] = Orders::STATUS_ORDER_CREATED;
            $data_update['fix_time_date'] = Carbon::now()->addDays($data['numberDate'])->toDateTimeString();
            $model->update($data_update);
			$data_noti = [
				'title' => trans('order.notifications.sua_chua.title'),
				'content' => trans('order.notifications.sua_chua.content fixing other', ['order_code' => $model->id,'time'=>$data['numberDate'].' ngày','price'=>number_format($data['price']).'đ']),
				'fcm_token' => $model->user->fcm_token,
				'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),
				'order_id' => $model->id
			];

			event(new ShopNotiCreated($data_noti));
			event(new ShopUpdateOrderStatus([
				'title' => trans('order.notifications.sua_chua.title'),
				'content' => trans('order.notifications.sua_chua.content fixing other', ['order_code' => $model->id,'time'=>$data['numberDate'].' ngày','price'=>number_format($data['price']).'đ']),
				'user_id' => $model->user->id,
				'noti_type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CONFIRMED]),
				'order_id' => $model->id
			]));


			event(new OrderStatusUpdated([
				'order_type' => $model->order_type,
				'order_id' => $model->id,
				'title' => $model->status_name,
				'old_status' => Orders::STATUS_ORDER_CONFIRMED,
				'new_status' => Orders::STATUS_ORDER_CREATED,
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
        ],422);
    }
    

    
    

    // đơn hàng bảo hành thông báo

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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
        ],422);
    }

    // đơn hàng mua bán thông báo

    public function buysellCancel($model, $data)
    {
        if ($model->shop_done == $model::ORDER_DONE_WAIT_CLIENT_CONFIRM && $model->order_type == $model::TYPE_MUA_HANG) {
            $data_update =[
                'status' => Orders::STATUS_ORDER_CANCEL
            ];
	        $model->update($data_update);

	        $data = [
		        'title' => trans('order.notifications.ban_hang.title'),
		        'content' => trans('order.notifications.ban_hang.content cancel', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.ban_hang.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.ban_hang.title'),
		        'content' => trans('order.notifications.ban_hang.content cancel', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.ban_hang.type', ['order_status' => Orders::STATUS_ORDER_CANCEL]),

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
            'message' => 'Lỗi trạng thái cập nhật',
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
		        'title' => trans('order.notifications.ban_hang.title'),
		        'content' => trans('order.notifications.ban_hang.content confirm', ['order_code' => $model->id]),
		        'fcm_token' => $model->user->fcm_token,
		        'order_id' => $model->id,
		        'type' => trans('order.notifications.ban_hang.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),
	        ];

	        event(new ShopNotiCreated($data));
	        event(new ShopUpdateOrderStatus([
		        'title' => trans('order.notifications.ban_hang.title'),
		        'content' => trans('order.notifications.ban_hang.content confirm', ['order_code' => $model->id]),
		        'noti_type' => trans('order.notifications.ban_hang.type', ['order_status' => Orders::STATUS_ORDER_SENDING]),

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
            'message' => 'Lỗi trạng thái cập nhật',
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
            'message' => 'Lỗi trạng thái cập nhật',
        ],422);
    }

	function stripVN($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
	
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		return $str;
	}

	public function getUserByPhone($params) {
    	$query = User::query();
        $user = $query->where('phone', $params['phone'])->first();
        if ($user){
            return $user;
        } else {
            $user = new User();
            $user->phone = $params['phone'];
            $user->name = $params['customer_name'];
            $user->type = User::TYPE_USER;
            $user->username = $this->stripVN($params['customer_name']);
            $user->password = Hash::make(User::DEFAULT_PASS);
            $user->save();
            return $user;
        }
	}

    public function storeBuySell($requestParams)
    {
        // Đơn mua bán - placeOrderMultiProduct
        // Đơn sửa chữa bảo hành - placeMultipleOrder
        $user = $this->getUserByPhone($requestParams);
		$user = Auth::user();
		$order = new Orders();
		$order->order_type = Orders::TYPE_MUA_HANG;
		$order->company_id = $user->company_id;
		$order->shop_id = $user->shop_id;
		$order->status = Orders::STATUS_ORDER_DONE;
		$order->payment_status = Orders::PAYMENT_PAID_DONE;
		$order->user_id = $user->id;
		$order->total_price = $requestParams['price'];
		$order->discount = $requestParams['sale_price'];
		$order->pay_price = (1 - $requestParams['sale_price']/100) * $requestParams['price'];
		$order->save();
    }
}
