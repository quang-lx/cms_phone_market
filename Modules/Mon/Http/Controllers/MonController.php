<?php

namespace Modules\Mon\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonController extends WebController
{

    public function index()
    {
        return redirect('admin');
    }
}
