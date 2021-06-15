<?php

namespace Modules\Shop\Http\Controllers\Admin\VtShopProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtShopProduct;
use Modules\Shop\Repositories\VtShopProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class VtShopProductController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$vtshopproducts = $this->vtshopproduct->all();

        return $this->view('shop::vtshopproducts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::vtshopproducts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  VtShopProduct $vtshopproduct
     * @return Response
     */
    public function edit(VtShopProduct $vtshopproduct)
    {
        return $this->view('shop::vtshopproducts.edit', compact('vtshopproduct'));
    }


}
