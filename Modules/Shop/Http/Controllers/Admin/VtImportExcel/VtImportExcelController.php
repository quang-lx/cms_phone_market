<?php

namespace Modules\Shop\Http\Controllers\Admin\VtImportExcel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtImportExcel;
use Modules\Shop\Repositories\VtImportExcelRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class VtImportExcelController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$vtimportexcels = $this->vtimportexcel->all();

        return $this->view('shop::vtimportexcels.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::vtimportexcels.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  VtImportExcel $vtimportexcel
     * @return Response
     */
    public function detail(VtImportExcel $vtimportexcel)
    {
        return $this->view('shop::vtimportexcels.detail', compact('vtimportexcel'));
    }


}
