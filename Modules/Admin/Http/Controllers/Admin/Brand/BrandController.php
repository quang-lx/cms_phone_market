<?php

namespace Modules\Admin\Http\Controllers\Admin\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Brand;
use Modules\Admin\Repositories\BrandRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class BrandController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$brands = $this->brand->all();

        return $this->view('admin::brands.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::brands.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Brand $brand
     * @return Response
     */
    public function edit(Brand $brand)
    {
        return $this->view('admin::brands.edit', compact('brand'));
    }


}
