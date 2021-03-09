<?php

namespace Modules\Admin\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class PermissionController extends AdminController
{
    public function index()
    {
        return $this->view('admin::auth.permission.index');
    }
    public function create()
    {
        return $this->view('admin::auth.permission.create');
    }
    public function edit()
    {
        return $this->view('admin::auth.permission.edit');
    }

}
