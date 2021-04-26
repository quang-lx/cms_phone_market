<?php

namespace Modules\Admin\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Product;
use Modules\Admin\Repositories\ProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ProductController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$products = $this->product->all();

        return $this->view('admin::products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::products.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        return $this->view('admin::products.edit', compact('product'));
    }

    public function detail(Product $product)
    {
        return $this->view('admin::products.detail', compact('product'));
    }


}
