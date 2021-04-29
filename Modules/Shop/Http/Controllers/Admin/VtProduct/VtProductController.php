<?php

namespace Modules\Shop\Http\Controllers\Admin\VtProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtProduct;
use Modules\Shop\Repositories\VtProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class VtProductController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$vtproducts = $this->vtproduct->all();

        return $this->view('shop::vtproducts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::vtproducts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  VtProduct $vtproduct
     * @return Response
     */
    public function edit(VtProduct $vtproduct)
    {
        return $this->view('shop::vtproducts.edit', compact('vtproduct'));
    }


}
