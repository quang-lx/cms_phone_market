<?php

namespace Modules\Mon\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class WebController extends BaseController
{
    use SEOToolsTrait;

    public function view($name, $data = [], $mergeData = [])
    {
        $namespace = 'base';
        return view("$namespace::$name", $data, $mergeData);
    }
}
