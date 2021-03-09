<?php

namespace Modules\Admin\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class UserController extends AdminController
{

    public function index()
    {
        return $this->view('admin::auth.user.index');
    }
    public function create()
    {
        return $this->view('admin::auth.user.create');
    }
    public function edit()
    {
        return $this->view('admin::auth.user.edit');
    }

}
