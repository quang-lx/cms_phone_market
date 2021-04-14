<?php

namespace Modules\Admin\Http\Controllers\Admin\Banners;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Banners;
use Modules\Admin\Repositories\BannersRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class BannersController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$banners = $this->banners->all();

        return $this->view('admin::banners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::banners.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Banners $banners
     * @return Response
     */
    public function edit(Banners $banners)
    {
        return $this->view('admin::banners.edit', compact('banners'));
    }


}
