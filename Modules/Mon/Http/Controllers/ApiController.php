<?php

namespace Modules\Mon\Http\Controllers;

use Modules\Mon\Auth\Contracts\Authentication;

class ApiController extends BaseController
{
    public function __construct(Authentication $auth)
    {
        parent::__construct($auth);
        $this->auth->setGuard('api');
    }
    public function respond($data = [], $message = '', $errorCode = 0) {
        return response()->json([
            'error_code' => $errorCode,
            'message' => $message,
            'data' => $data
        ]);
    }
}
