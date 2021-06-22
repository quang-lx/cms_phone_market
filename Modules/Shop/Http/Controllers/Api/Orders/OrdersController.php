<?php

namespace Modules\Shop\Http\Controllers\Api\Orders;

use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Orders;
use Modules\Shop\Http\Requests\Orders\CreateOrdersRequest;
use Modules\Shop\Transformers\OrdersTransformer;
use Modules\Shop\Transformers\OrdersBuySellTransformer;
use Modules\Shop\Http\Requests\Orders\UpdateOrdersRequest;
use Modules\Shop\Repositories\OrdersRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use App\Events\ShopNotiCreated;
use Illuminate\Support\Facades\Auth;

class OrdersController extends ApiController
{
    /**
     * @var OrdersRepository
     */
    private $ordersRepository;

    public function __construct(Authentication $auth, OrdersRepository $orders)
    {
        parent::__construct($auth);

        $this->ordersRepository = $orders;
    }


    public function index(Request $request)
    {
        return OrdersTransformer::collection($this->ordersRepository->serverPagingFor($request));
    }

    public function statistical(Request $request)
    {
        return $this->ordersRepository->statistical($request);
    }



    public function all(Request $request)
    {
        return OrdersTransformer::collection($this->ordersRepository->newQueryBuilder()->get());
    }


    public function store(CreateOrdersRequest $request)
    {
        $this->ordersRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.create success'),
        ]);
    }


    public function find(Orders $orders)
    {
        return new  OrdersTransformer($orders);
    }

    public function findBuySell(Orders $orders)
    {
        return new OrdersBuySellTransformer($orders);
    }

    // đơn hàng sửa chữa 

    public function repairCancel(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->repairCancel($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }


    public function repairConfirmed(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->repairConfirmed($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function repairFixing(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->repairFixing($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function repairSending(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->repairSending($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function repairDone(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->repairDone($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    // đơn hàng bảo hành

    public function guaranteeCancel(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->guaranteeCancel($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }


    public function guaranteeConfirmed(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->guaranteeConfirmed($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function guaranteeWarranting(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->guaranteeWarranting($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function guaranteeSending(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->guaranteeSending($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function guaranteeDone(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->guaranteeDone($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }
    // đơn hàng mua bán

    public function buysellCancel(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->buysellCancel($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function buysellConfirmed(Orders $orders, Request $request)
    {

        $this->ordersRepository->buysellConfirmed($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function buysellDone(Orders $orders, Request $request)
    {

        $this->ordersRepository->buysellDone($orders, $request->all());
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    



    public function destroy(Orders $orders)
    {
        $this->ordersRepository->destroy($orders);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.delete success'),
        ]);
    }

    //nhatdv1 - Tạo mới đơn
    public function storeBuysell(CreateOrdersRequest $request)
    {
        $params = [
            "order_type" => 1, 
            "status" => 1, 
            "payment_status" => 1
        ];
        $this->ordersRepository->create(array_merge($request->all(),$params));

        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.create success'),
        ]);
    }

    public function updateBuysell(Orders $orders, UpdateOrdersRequest $request)
    {
        $this->ordersRepository->update($voucher, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }
}
