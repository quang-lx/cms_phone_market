<?php

namespace Modules\Shop\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class UserController extends AdminController
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
