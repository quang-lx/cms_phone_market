<?php

namespace Modules\Mon\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Mon\Auth\Contracts\Authentication;

class BaseController extends Controller
{
    /**
     * @var Authentication
     */
    public $auth;
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }
}
