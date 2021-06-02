<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\OrdersRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Company;
use Modules\Admin\Transformers\OrdersTransformer;

class EloquentOrdersRepository extends BaseRepository implements OrdersRepository
{
    public function statistical(Request $request, $relations = null)
    {
        // select count(*) AS so_don,`order_type`,sum(`pay_price`) AS tong_tien, date(`created_at`) from `orders`
        //  where `orders`.`deleted_at` is null group by `order_type`, date(`created_at`)
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->selectRaw('count(*) AS so_don, order_type, sum(pay_price) AS tong_tien,
            sum(ship_fee) AS tong_phi, date(created_at) AS ngay_tao');

		// $user = Auth::user();
		// $query->where('company_id', $user->company_id);

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
        $totalRevenue = 0;
        $totalFee = 0;

        foreach ($data as $record){
            $date = date('d-m-Y', strtotime($record->ngay_tao));
            $totalRevenue += $record->tong_tien;
            $totalFee += $record->tong_phi;
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
            'totalRevenue' => $totalRevenue, 'totalFee' => $totalFee
        );

    }

    public function topCompany(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->selectRaw('sum(pay_price) AS tong_tien, company_id');
		$query->where('status', Orders::STATUS_ORDER_DONE);

        $query->groupBy('company_id')->orderBy('tong_tien','desc')->limit(10);
        $listCompany = $query->get();
        $result = array();
        foreach ($listCompany as $key => $company){
            $detai = Company::query()->find($company->company_id);
            $newKey = $key + 1;
            $item = array();
            $item['key'] = ($newKey < 10) ? '0'.$newKey : $newKey;
            $item['value'] = sprintf('%s. %s - %s - %sđ', $item['key'], $detai->name, $detai->address, number_format($company->tong_tien,0,",","."));
            $result[] = $item;
        }
        return $result;

    }

}
