<?php

namespace Modules\Admin\Http\Controllers\Admin\Rank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Rank;
use Modules\Admin\Repositories\RankRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class RankController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$ranks = $this->rank->all();

        return $this->view('admin::ranks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::ranks.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Rank $rank
     * @return Response
     */
    public function edit(Rank $rank)
    {
        return $this->view('admin::ranks.edit', compact('rank'));
    }


}
