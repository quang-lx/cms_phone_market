<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\ShopRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Mon\Entities\Shop;

class EloquentShopRepository extends BaseRepository implements ShopRepository
{
    public function statistical(Request $request)
    {
        $shopActive = $shopInactive = array(
            'day' => 0,
            'week' => 0,
            'month' => 0,
        );
        $today = date('Y-m-d');
        $last7day = date('Y-m-d', time()-7*86400);
        $last30day = date('Y-m-d', time()-30*86400);

        $listShop = DB::table('shop')
            ->selectRaw('count(*) AS total_shop, date(created_at) AS ngay_tao')
            ->where('status', Shop::STATUS_ACTIVE)->whereNull('deleted_at')
            ->where('created_at', '>=', date('Y-m-d', time()-30*86400).' 00:00:00')
            ->groupBy('ngay_tao')
            ->orderBy('ngay_tao','asc')
            ->get();
        
        foreach ($listShop as $shop){
            if ($shop->ngay_tao == $today){
                $shopActive['day'] += $shop->total_shop;
                $shopActive['week'] += $shop->total_shop;
                $shopActive['month'] += $shop->total_shop;
            } else if ($shop->ngay_tao >= $last7day){
                $shopActive['week'] += $shop->total_shop;
                $shopActive['month'] += $shop->total_shop;
            } else if ($shop->ngay_tao >= $last30day){
                $shopActive['month'] += $shop->total_shop;
            }
        }

        $listShopInactive = DB::table('shop')
            ->selectRaw('count(*) AS total_shop, date(created_at) AS ngay_tao')
            ->where('status', Shop::STATUS_LOCK)->whereNull('deleted_at')
            ->where('created_at', '>=', date('Y-m-d', time()-30*86400).' 00:00:00')
            ->groupBy('ngay_tao')
            ->orderBy('ngay_tao','asc')
            ->get();
        
        foreach ($listShopInactive as $shop){
            if ($shop->ngay_tao == $today){
                $shopInactive['day'] += $shop->total_shop;
                $shopInactive['week'] += $shop->total_shop;
                $shopInactive['month'] += $shop->total_shop;
            } else if ($shop->ngay_tao >= $last7day){
                $shopInactive['week'] += $shop->total_shop;
                $shopInactive['month'] += $shop->total_shop;
            } else if ($shop->ngay_tao >= $last30day){
                $shopInactive['month'] += $shop->total_shop;
            }
        }

        return array(
            'shopActive' => $shopActive, 
            'shopInactive' => $shopInactive, 
        );
    }
}
