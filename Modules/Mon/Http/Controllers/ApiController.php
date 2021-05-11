<?php

namespace Modules\Mon\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function validate(Request $request, $rules, $messages = [], $customAttribute = []) {
	    $validator = Validator::make($request->all(),$rules, $messages, $customAttribute);
	    return $validator;
    }
}
