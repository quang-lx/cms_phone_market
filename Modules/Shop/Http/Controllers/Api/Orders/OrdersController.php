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

    // đơn hàng sửa chữa thông báo

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
    public function updateGuarantee(Orders $orders, Request $request)
    {

        return $this->ordersRepository->updateGuarantee($orders, $request->all());
    }
    // đơn hàng mua bán
    public function updateBuySell(Orders $orders, Request $request)
    {

        return $this->ordersRepository->updateBuySell($orders, $request->all());
    }

    public function cancelBuySell(Orders $orders, Request $request)
    {
	    $orders = $this->ordersRepository->cancelBuySell($orders, $request->all());
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
}
