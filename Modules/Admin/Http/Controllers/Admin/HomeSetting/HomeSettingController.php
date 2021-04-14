<?php

namespace Modules\Admin\Http\Controllers\Admin\HomeSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\HomeSetting;
use Modules\Admin\Repositories\HomeSettingRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class HomeSettingController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$homesettings = $this->homesetting->all();

        return $this->view('admin::homesettings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::homesettings.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  HomeSetting $homesetting
     * @return Response
     */
    public function edit(HomeSetting $homesetting)
    {
        return $this->view('admin::homesettings.edit', compact('homesetting'));
    }


}
