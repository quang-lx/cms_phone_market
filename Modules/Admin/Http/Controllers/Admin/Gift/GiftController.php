<?php

namespace Modules\Admin\Http\Controllers\Admin\Gift;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Gift;
use Modules\Admin\Repositories\GiftRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class GiftController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$gifts = $this->gift->all();

        return $this->view('admin::gifts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::gifts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Gift $gift
     * @return Response
     */
    public function edit(Gift $gift)
    {
        return $this->view('admin::gifts.edit', compact('gift'));
    }


}
