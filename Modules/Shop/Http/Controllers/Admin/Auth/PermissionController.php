<?php

namespace Modules\Shop\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class PermissionController extends AdminController
{
    public function shopindex()
    {
        return $this->view('shop::auth.permission.index');
    }
    public function index()
    {
        return $this->view('shop::auth.permission.index');
    }
    public function create()
    {
        return $this->view('shop::auth.permission.create');
    }
    public function edit()
    {
        return $this->view('shop::auth.permission.edit');
    }

}
