<?php

namespace Modules\Shop\Repositories\Eloquent;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Shop\Repositories\VoucherRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Events\Voucher\VoucherWasCreated;
use Modules\Shop\Events\Voucher\VoucherWasUpdated;


class EloquentVoucherRepository extends BaseRepository implements VoucherRepository
{

    public function create($data)
    {
        $data['company_id'] = Auth::user()->company_id;
        $model = $this->model->create($data);
        $data['products'] = array_map(function ($product) {
            return $product['id'];
        }, $data['products']);
        if (isset($data['products']) && is_array($data['products'])) {
            $model->products()->sync($data['products']); //mảng product_id
        }
        event(new VoucherWasCreated($model, $data));
        $cacheKey =  $cacheKey = sprintf(CacheKey::VOUCHER_SHOP, Auth::user()->shop_id);
        Cache::forget($cacheKey);

        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        // $model = $this->model->update($data);
        event(new VoucherWasUpdated($model, $data));
        $data['products'] = array_map(function ($product) {
            return $product['id'];
        }, $data['products']);
        if (isset($data['products']) && is_array($data['products'])) {
            $model->products()->sync($data['products']); //mảng product_id
        }
        $cacheKey =  $cacheKey = sprintf(CacheKey::VOUCHER_SHOP, Auth::user()->shop_id);
        Cache::forget($cacheKey);


        return $model;
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $code = $request->get('search');
            $query->where('code', 'LIKE', "%{$code}%");
        }

        $companyId = Auth::user()->company_id;
        $shopId = Auth::user()->shop_id;
        if (!$shopId){
            //nếu là chủ cửa hàng -> Xem được KM của mình, chi nhánh, admin tạo
            $query->where(function ($query) use ($companyId){
                $query->where(function ($query) {
                    $query->whereNull('shop_id')
                        ->whereNull('company_id');
                })->orWhere('company_id', $companyId);
            });
        } else {
            //nếu là chi nhánh => xem được KM của cửa hàng, chi nhánh của mình, admin
            $query->where(function ($query) use ($companyId, $shopId){
                $query->where(function ($query) {
                    $query->whereNull('shop_id')
                        ->whereNull('company_id');
                })->orWhere(function ($query) use ($companyId) {
                    $query->whereNull('shop_id')
                        ->where('company_id', $companyId);
                })->orWhere(function ($query) use ($shopId, $companyId) {
                    $query->where('shop_id', $shopId)
                        ->where('company_id', $companyId);
                });
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

}
