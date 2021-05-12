<?php

namespace Modules\Admin\Http\Controllers\Api\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Orders;
use Modules\Admin\Http\Requests\Orders\CreateOrdersRequest;
use Modules\Admin\Transformers\OrdersTransformer;
use Modules\Admin\Http\Requests\Orders\UpdateOrdersRequest;
use Modules\Admin\Repositories\OrdersRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

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


    public function all(Request $request)
    {
        return OrdersTransformer::collection($this->ordersRepository->newQueryBuilder()->get());
    }


    public function store(CreateOrdersRequest $request)
    {
        $this->ordersRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::orders.message.create success'),
        ]);
    }


    public function find(Orders $orders)
    {
        return new  OrdersTransformer($orders);
    }

    public function update(Orders $orders, UpdateOrdersRequest $request)
    {
        $this->ordersRepository->update($orders, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::orders.message.update success'),
        ]);
    }

    public function destroy(Orders $orders)
    {
        $this->ordersRepository->destroy($orders);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::orders.message.delete success'),
        ]);
    }
}
