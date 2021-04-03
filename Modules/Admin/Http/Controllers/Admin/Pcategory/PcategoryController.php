<?php

namespace Modules\Admin\Http\Controllers\Admin\Pcategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Pcategory;
use Modules\Admin\Repositories\PcategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class PcategoryController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$pcategories = $this->pcategory->all();

        return $this->view('admin::pcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::pcategories.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Pcategory $pcategory
     * @return Response
     */
    public function edit(Pcategory $pcategory)
    {
        return $this->view('admin::pcategories.edit', compact('pcategory'));
    }


}
