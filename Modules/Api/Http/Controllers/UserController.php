<?php

namespace Modules\Api\Http\Controllers;

use App\Events\NeedCreateUserSmsToken;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends ApiController
{
    public function update(Request $request)
    {

        $user = $this->auth->user();
        if ($fcmToken = $request->get('fcm_token')) {
            $user->fcm_token = $fcmToken;
        }
        if ($lat = $request->get('lat')) {

        }
        if ($lng = $request->get('lng')) {

        }
        $user->save();
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function updateAvatar(Request $request)
    {

        $user = $this->auth->user();

        $user->save();
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function logout(Request $request) {
        Auth::logout();
        return $this->respond([], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }


}
