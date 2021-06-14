<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\VtProduct;
use Modules\Mon\Entities\VtShopProduct;
use Modules\Shop\Repositories\VtShopProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentVtShopProductRepository extends BaseRepository implements VtShopProductRepository
{
    public function create($data)
    {
        $company_id = Auth::user()->company_id;
        $shop_id = Auth::user()->shop_id;
        $exist_vt = VtProduct::whereRaw('LOWER(`name`) = ? ',[trim(strtolower($data['vt_product_name']))])->first();
        $exist_company = isset($exist_vt) ? $exist_vt->company_id : 0;
        if($exist_company != $company_id){
            return abort(500, 'Vật tư chưa tồn tại trong hệ thống');
        }
        $data['vt_product_id'] = $exist_vt->id;
        $vtshopproduct = VtShopProduct::firstOrNew(
            [
                'vt_product_id'=>$data['vt_product_id'],
                'shop_id'=>$shop_id,
                'company_id'=>$company_id,          
            ]);
        $vtshopproduct->amount = ($vtshopproduct->amount + $data['amount']);
        return $vtshopproduct->save();
    }

    public function update($model, $data)
    {
        $company_id = Auth::user()->company_id;
        $shop_id = Auth::user()->shop_id;
        $exist_vt = VtProduct::whereRaw('LOWER(`name`) = ? ',[trim(strtolower($data['vt_product_name']))])->first();
        $exist_company = isset($exist_vt) ? $exist_vt->company_id : 0;
        if($exist_company != $company_id){
            return abort(500, 'Vật tư chưa tồn tại trong hệ thống');
        }
        $data['vt_product_id'] = $exist_vt->id;
        $vtshopproduct = VtShopProduct::firstOrNew(
            [
                'vt_product_id'=>$data['vt_product_id'],
                'shop_id'=>$shop_id,
                'company_id'=>$company_id,          
            ]);
        $vtshopproduct->amount = $data['amount'];
        return $vtshopproduct->save();
    }
}
