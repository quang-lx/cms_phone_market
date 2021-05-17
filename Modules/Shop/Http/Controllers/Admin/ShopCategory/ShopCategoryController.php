<?php

namespace Modules\Shop\Http\Controllers\Admin\ShopCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopCategory;
use Modules\Shop\Repositories\ShopCategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ShopCategoryController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$shopcategories = $this->shopcategory->all();

        return $this->view('shop::shopcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::shopcategories.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  ShopCategory $shopcategory
     * @return Response
     */
    public function edit(ShopCategory $shopcategory)
    {
        return $this->view('shop::shopcategories.edit', compact('shopcategory'));
    }


}
