<?php

namespace Modules\Api\Repositories\Eloquent\Cached;


use App\Models\CacheKey;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Repositories\VoucherRepository;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Api\Transformers\VoucherTransformer;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\ShopShipType;


class EloquentCacheVoucherRepository implements VoucherRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getListVoucher(Request $request)
    {
        $shopIds = $request->get('shop_ids', null);

        if ($shopIds) {
            $shopIds = explode(',', $shopIds);
            if ($shopIds && is_array($shopIds)) {
                $data['shops'] = [];
                foreach ($shopIds as $shopId) {
                    if (!$shopId) continue;
                    $cacheKey = sprintf(CacheKey::VOUCHER_SHOP, $shopId);
                    $vouchers = Cache::tags(CacheKey::TAG_VOUCHER)->rememberForever($cacheKey, function () use ($shopId) {
                        $vouchers = $this->getVouchers($shopId);
                        return VoucherTransformer::collection($vouchers);
                    });
                    $data['shops'][] = [
                        'shop_id' => $shopId,
                        'vouchers' => $vouchers
                    ];
                }
            }
        } else {
            $cacheKey = CacheKey::VOUCHER_ALL;
            $data = [];

            $systemVouchers = Cache::tags(CacheKey::TAG_VOUCHER)->rememberForever($cacheKey, function () {
                $vouchers = $this->getVouchers();
                return VoucherTransformer::collection($vouchers);
            });

            $data['system'] = $systemVouchers;
        }



        return $data;
    }

    public function getVouchers($shopId = null){
        $query = $this->model->newQuery();
        // query by  active date
        $today = Carbon::now();
        $query->where(function($query) use ($today) {
            /** @var Builder $query */
            $query->whereDate('actived_at', '<=', $today)
                ->orWhereNull('actived_at');
        });

        // query by expired date
        $query->where(function($query) use ($today) {
            /** @var Builder $query */
            $query->whereDate('expired_at', '>=', $today)
                ->orWhereNull('expired_at');
        });

        // query voucher by total use
        $query->whereRaw('total_used < total');

        if ($shopId) {
            $query->where('shop_id', $shopId);
        } else {
            $query->whereNull('shop_id');
        }

        return $query->get();
    }
}
