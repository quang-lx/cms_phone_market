<?php

namespace Modules\Shop\Http\Controllers\Admin\ShopShipType;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopShipType;
use Modules\Shop\Repositories\ShopShipTypeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ShopShipTypeController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$shopshiptypes = $this->shopshiptype->all();

        return $this->view('shop::shopshiptypes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::shopshiptypes.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  ShopShipType $shopshiptype
     * @return Response
     */
    public function edit(ShopShipType $shopshiptype)
    {
        return $this->view('shop::shopshiptypes.edit', compact('shopshiptype'));
    }


}
