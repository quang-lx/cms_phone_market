<?php

namespace Modules\Admin\Http\Controllers\Admin\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Orders;
use Modules\Admin\Repositories\OrdersRepository;
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

        return $this->view('admin::orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::orders.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Orders $orders
     * @return Response
     */
    public function edit(Orders $orders)
    {
        return $this->view('admin::orders.edit', compact('orders'));
    }


}
