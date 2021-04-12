<?php

namespace Modules\Admin\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Account;
use Modules\Admin\Repositories\AccountRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class AccountController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$accounts = $this->account->all();

        return $this->view('admin::accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::accounts.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Account $account
     * @return Response
     */
    public function edit(Account $account)
    {
        return $this->view('admin::accounts.edit', compact('account'));
    }


}
