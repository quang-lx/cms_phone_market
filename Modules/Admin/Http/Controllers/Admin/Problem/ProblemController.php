<?php

namespace Modules\Admin\Http\Controllers\Admin\Problem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Problem;
use Modules\Admin\Repositories\ProblemRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ProblemController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$problems = $this->problem->all();

        return $this->view('admin::problems.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::problems.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Problem $problem
     * @return Response
     */
    public function edit(Problem $problem)
    {
        return $this->view('admin::problems.edit', compact('problem'));
    }


}
