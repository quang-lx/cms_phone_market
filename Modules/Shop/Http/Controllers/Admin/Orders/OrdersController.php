<?php

namespace Modules\Shop\Http\Controllers\Admin\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Orders;
use Modules\Shop\Repositories\OrdersRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class OrdersController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$orders = $this->orders->all();

        return $this->view('shop::orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::orders.create');
    }

    public function createRepair()
    {
        return $this->view('shop::orders.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Orders $orders
     * @return Response
     */
    public function edit(Orders $orders)
    {
        return $this->view('shop::orders.edit', compact('orders'));
    }

    public function detail(Orders $orders)
    {
        return $this->view('shop::orders.detail', compact('orders'));
    }


}
