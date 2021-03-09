<?php

namespace Modules\Admin\Http\Controllers\Admin\Auth;

use Modules\Mon\Http\Controllers\AdminController;

class AdminUserController extends AdminController
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
