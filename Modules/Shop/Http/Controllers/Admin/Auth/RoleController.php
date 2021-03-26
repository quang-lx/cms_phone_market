<?php

namespace Modules\Shop\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class RoleController extends AdminController
{

    public function index()
    {
        return $this->view('shop::auth.role.index');
    }
    public function create()
    {
        return $this->view('shop::auth.role.create');
    }
    public function edit()
    {
        return $this->view('shop::auth.role.edit');
    }

}
