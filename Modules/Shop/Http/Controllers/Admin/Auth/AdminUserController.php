<?php

namespace Modules\Shop\Http\Controllers\Admin\Auth;

use Modules\Mon\Http\Controllers\AdminController;

class AdminUserController extends AdminController
{

    public function index()
    {
        return $this->view('shop::auth.user.index');
    }
    public function create()
    {
        return $this->view('shop::auth.user.create');
    }
    public function edit()
    {
        return $this->view('shop::auth.user.edit');
    }

}
