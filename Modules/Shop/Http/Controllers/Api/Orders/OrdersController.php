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

    public function update(Orders $orders, UpdateOrdersRequest $request)
    {
	    $orders = $this->ordersRepository->update($orders, $request->all());
        $data = [
            'title' => trans('order.notifications.sua_chua.title'),
            'content' => trans('order.notifications.sua_chua.content confirm', ['order_code' => $orders->id]),
            'fcm_token' => $orders->user->fcm_token,
            'type' => trans('order.notifications.sua_chua.type', ['order_status' => $orders->status]),
        ];
        event(new ShopNotiCreated($data));

        event(new OrderStatusUpdated([
            'order_id' => $orders->id,
            'title' => $orders->status_name,
            'old_status' => Orders::STATUS_ORDER_CREATED,
            'new_status' => Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM,
            'user_id' => '',
            'shop_id' => Auth::user()->shop_id
          ]));
        return response()->json([
            'errors' => false,
            'message' => trans('ch::orders.message.update success'),
        ]);
    }

    public function updateGuarantee(Orders $orders, Request $request)
    {

        return $this->ordersRepository->updateGuarantee($orders, $request->all());
    }

    public function updateBuySell(Orders $orders, Request $request)
    {

        return $this->ordersRepository->updateBuySell($orders, $request->all());
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
