<?php

namespace Modules\Shop\Http\Controllers\Api\Voucher;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Mon\Entities\Voucher;
use Modules\Shop\Http\Requests\Voucher\CreateVoucherRequest;
use Modules\Shop\Transformers\VoucherTransformer;
use Modules\Shop\Http\Requests\Voucher\UpdateVoucherRequest;
use Modules\Shop\Repositories\VoucherRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class VoucherController extends ApiController
{
    /**
     * @var VoucherRepository
     */
    private $voucherRepository;

    public function __construct(Authentication $auth, VoucherRepository $voucher)
    {
        parent::__construct($auth);

        $this->voucherRepository = $voucher;
    }

    public function changeStatus(Request $request)
    {
        $this->voucherRepository->changeStatus($request);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::voucher.message.update success'),
        ]);
    }


    public function index(Request $request)
    {
        return VoucherTransformer::collection($this->voucherRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return VoucherTransformer::collection($this->voucherRepository->newQueryBuilder()->get());
    }


    public function store(CreateVoucherRequest $request)
    {
        $this->voucherRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::voucher.message.create success'),
        ]);
    }


    public function find(Voucher $voucher)
    {
        return new  VoucherTransformer($voucher);
    }

    public function update(Voucher $voucher, UpdateVoucherRequest $request)
    {
        $this->voucherRepository->update($voucher, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::voucher.message.update success'),
        ]);
    }

    public function destroy(Voucher $voucher)
    {
        $this->voucherRepository->destroy($voucher);
        $cacheKey =  $cacheKey = sprintf(CacheKey::VOUCHER_SHOP, Auth::user()->shop_id);
        Cache::forget($cacheKey);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::voucher.message.delete success'),
        ]);
    }
}
