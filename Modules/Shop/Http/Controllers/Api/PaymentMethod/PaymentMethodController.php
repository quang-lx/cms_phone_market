<?php

namespace Modules\Shop\Http\Controllers\Api\PaymentMethod;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\PaymentMethod;
use Modules\Shop\Http\Requests\PaymentMethod\CreatePaymentMethodRequest;
use Modules\Shop\Transformers\PaymentMethodTransformer;
use Modules\Shop\Http\Requests\PaymentMethod\UpdatePaymentMethodRequest;
use Modules\Shop\Repositories\PaymentMethodRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class PaymentMethodController extends ApiController
{
    /**
     * @var PaymentMethodRepository
     */
    private $paymentmethodRepository;

    public function __construct(Authentication $auth, PaymentMethodRepository $paymentmethod)
    {
        parent::__construct($auth);

        $this->paymentmethodRepository = $paymentmethod;
    }


    public function index(Request $request)
    {
        return PaymentMethodTransformer::collection($this->paymentmethodRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return PaymentMethodTransformer::collection($this->paymentmethodRepository->newQueryBuilder()->get());
    }


    public function store(CreatePaymentMethodRequest $request)
    {
        $this->paymentmethodRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::paymentmethod.message.create success'),
        ]);
    }


    public function find(PaymentMethod $paymentmethod)
    {
        return new  PaymentMethodTransformer($paymentmethod);
    }

    public function update(PaymentMethod $paymentmethod, UpdatePaymentMethodRequest $request)
    {
        $this->paymentmethodRepository->update($paymentmethod, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::paymentmethod.message.update success'),
        ]);
    }

    public function destroy(PaymentMethod $paymentmethod)
    {
        $this->paymentmethodRepository->destroy($paymentmethod);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::paymentmethod.message.delete success'),
        ]);
    }
}
