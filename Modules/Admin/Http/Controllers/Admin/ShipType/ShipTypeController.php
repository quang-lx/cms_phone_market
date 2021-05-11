<?php

namespace Modules\Admin\Http\Controllers\Admin\ShipType;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShipType;
use Modules\Admin\Repositories\ShipTypeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ShipTypeController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$shiptypes = $this->shiptype->all();

        return $this->view('admin::shiptypes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::shiptypes.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  ShipType $shiptype
     * @return Response
     */
    public function edit(ShipType $shiptype)
    {
        return $this->view('admin::shiptypes.edit', compact('shiptype'));
    }


}
