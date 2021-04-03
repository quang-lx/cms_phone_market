<?php

namespace Modules\Shop\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Shop;
use Modules\Shop\Repositories\ShopRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ShopController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$shops = $this->shop->all();

        return $this->view('shop::shops.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::shops.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Shop $shop
     * @return Response
     */
    public function edit(Shop $shop)
    {
        return $this->view('shop::shops.edit', compact('shop'));
    }


}
