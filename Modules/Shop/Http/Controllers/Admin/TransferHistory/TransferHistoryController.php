<?php

namespace Modules\Shop\Http\Controllers\Admin\TransferHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\TransferHistory;
use Modules\Shop\Repositories\TransferHistoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class TransferHistoryController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$transferhistories = $this->transferhistory->all();

        return $this->view('shop::transferhistories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::transferhistories.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  TransferHistory $transferhistory
     * @return Response
     */
    public function edit(TransferHistory $transferhistory)
    {
        return $this->view('shop::transferhistories.edit', compact('transferhistory'));
    }


}
