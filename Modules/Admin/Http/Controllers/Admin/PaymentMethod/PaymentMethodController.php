<?php

namespace Modules\Admin\Http\Controllers\Admin\PaymentMethod;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\PaymentMethod;
use Modules\Admin\Repositories\PaymentMethodRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class PaymentMethodController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$paymentmethods = $this->paymentmethod->all();

        return $this->view('admin::paymentmethods.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::paymentmethods.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  PaymentMethod $paymentmethod
     * @return Response
     */
    public function edit(PaymentMethod $paymentmethod)
    {
        return $this->view('admin::paymentmethods.edit', compact('paymentmethod'));
    }


}
