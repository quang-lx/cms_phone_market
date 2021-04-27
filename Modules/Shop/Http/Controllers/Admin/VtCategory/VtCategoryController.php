<?php

namespace Modules\Shop\Http\Controllers\Admin\VtCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtCategory;
use Modules\Shop\Repositories\VtCategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class VtCategoryController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$vtcategories = $this->vtcategory->all();

        return $this->view('shop::vtcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::vtcategories.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  VtCategory $vtcategory
     * @return Response
     */
    public function edit(VtCategory $vtcategory)
    {
        return $this->view('shop::vtcategories.edit', compact('vtcategory'));
    }


}
