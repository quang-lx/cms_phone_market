<?php

namespace Modules\Mon\Http\Controllers;


use Illuminate\Support\Facades\View;

class AdminController extends BaseController
{
    public function view($name, $data = [], $mergeData = [])
    {
		$token = session('jwt_token');
		View::share('jwt_token', $token);
        if (!strpos($name, '::')) {
            $namespace = 'backend';
            return view("$namespace::$name", $data, $mergeData);
        }
        return view($name, $data, $mergeData);
    }
}
