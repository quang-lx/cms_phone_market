<?php

namespace Modules\Shop\Http\Controllers\Admin;


use Modules\Mon\Http\Controllers\AdminController;

class HomeController extends AdminController
{

    public function index()
    {
        return $this->view('shop::index');
    }

}
