<?php

namespace Modules\Shop\Repositories\Eloquent;

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
            $data_update['status'] = 'wait_client';
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
        $query->selectRaw('count(*) AS so_don, order_type, sum(pay_price) AS tong_tien, date(created_at) AS ngay_tao');

		$user = Auth::user();
		$query->where('company_id', $user->company_id);
        if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}

        $query->groupBy('order_type', 'ngay_tao');
        $data =  $query->orderBy('ngay_tao', 'asc')->get();
        $listDate = [];
        $listRepairCount = [];
        $listRepairMoney = [];
        $listGuaranteeCount = [];
        $listGuaranteeMoney = [];
        $listBuyCount = [];
        $listBuyMoney = [];
        $key = 0;
        $totalRevenue = 1000000000;
        $paid = 900000000;
        $waitPaid = 100000000;
        $totalFee = 20000000;

        foreach ($data as $record){
            $date = date('d-m-Y', strtotime($record->ngay_tao));
            $orderType = $record->order_type;
            if (!in_array($date, $listDate)){
                $listDate[$key] = $date;
                $listRepairCount[$key] = $listRepairMoney[$key] = $listGuaranteeCount[$key] =
                    $listGuaranteeMoney[$key] = $listBuyCount[$key] = $listBuyMoney[$key] = 0;
                $curKey = $key;
                $key ++;
            }

            switch ($orderType){
                case Orders::TYPE_SUA_CHUA: {
                    $listRepairCount[$curKey] = $record->so_don;
                    $listRepairMoney[$curKey] = $record->tong_tien;
                    break;
                }
                case Orders::TYPE_BAO_HANH: {
                    $listGuaranteeCount[$curKey] = $record->so_don;
                    $listGuaranteeMoney[$curKey] = $record->tong_tien;
                    break;
                }
                case Orders::TYPE_MUA_HANG: {
                    $listBuyCount[$curKey] = $record->so_don;
                    $listBuyMoney[$curKey] = $record->tong_tien;
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
        $data_update =[
            'status' => 'warranting'
        ];
        $model->update($data_update);
    }
}
