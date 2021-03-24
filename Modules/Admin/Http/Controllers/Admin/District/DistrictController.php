<?php

namespace Modules\Admin\Http\Controllers\Admin\District;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\District;
use Modules\Admin\Repositories\DistrictRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class DistrictController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$districts = $this->district->all();

        return $this->view('admin::districts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::districts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  District $district
     * @return Response
     */
    public function edit(District $district)
    {
        return $this->view('admin::districts.edit', compact('district'));
    }


}
