<?php

namespace Modules\Shop\Http\Controllers\Admin\Voucher;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Voucher;
use Modules\Shop\Repositories\VoucherRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class VoucherController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$vouchers = $this->voucher->all();

        return $this->view('shop::vouchers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::vouchers.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Voucher $voucher
     * @return Response
     */
    public function edit(Voucher $voucher)
    {
        return $this->view('shop::vouchers.edit', compact('voucher'));
    }


}
