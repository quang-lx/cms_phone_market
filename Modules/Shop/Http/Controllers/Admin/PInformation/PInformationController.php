<?php

namespace Modules\Shop\Http\Controllers\Admin\PInformation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\PInformation;
use Modules\Shop\Repositories\PInformationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class PInformationController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$pinformations = $this->pinformation->all();

        return $this->view('shop::pinformations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::pinformations.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  PInformation $pinformation
     * @return Response
     */
    public function edit(PInformation $pinformation)
    {
        return $this->view('shop::pinformations.edit', compact('pinformation'));
    }


}
