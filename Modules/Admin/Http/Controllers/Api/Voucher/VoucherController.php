<?php

namespace Modules\Admin\Http\Controllers\Api\Voucher;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Voucher;
use Modules\Admin\Http\Requests\Voucher\CreateVoucherRequest;
use Modules\Admin\Transformers\VoucherTransformer;
use Modules\Admin\Http\Requests\Voucher\UpdateVoucherRequest;
use Modules\Admin\Repositories\VoucherRepository;
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
            'message' => trans('backend::voucher.message.create success'),
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
            'message' => trans('backend::voucher.message.update success'),
        ]);
    }

    public function destroy(Voucher $voucher)
    {
        $this->voucherRepository->destroy($voucher);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::voucher.message.delete success'),
        ]);
    }
}
