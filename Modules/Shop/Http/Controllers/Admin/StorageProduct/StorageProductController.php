<?php

namespace Modules\Shop\Http\Controllers\Admin\StorageProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\StorageProduct;
use Modules\Shop\Repositories\StorageProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class StorageProductController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$storageproducts = $this->storageproduct->all();

        return $this->view('shop::storageproducts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::storageproducts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  StorageProduct $storageproduct
     * @return Response
     */
    public function edit(StorageProduct $storageproduct)
    {
        return $this->view('shop::storageproducts.edit', compact('storageproduct'));
    }


}
