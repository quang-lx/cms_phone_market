<?php

namespace Modules\Api\Http\Controllers;

use App\Events\NeedCreateUserSmsToken;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            return $this->respond([], ErrorCode::ERR07_MSG, ErrorCode::ERR07);
        }
        $usernameFormatted = validate_isdn($username);
        if (empty($usernameFormatted)) {
            return $this->respond([], ErrorCode::ERR01_MSG, ErrorCode::ERR01);
        }

        // check db
        $user = User::query()->where('username', $usernameFormatted)->first();
        if ($user && $user->finish_reg) {
            return $this->respond([], ErrorCode::ERR12_MSG, ErrorCode::ERR12);
        }
        if (!$user) {
            $userData = [
                'username' => $usernameFormatted,
                'name' => $usernameFormatted,
                'password' => $usernameFormatted,
                'type' => User::TYPE_USER,

            ];

            $user = User::create($userData);
        }

        if ($user) {
            event(new NeedCreateUserSmsToken($user->id, $usernameFormatted));
            return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
        } else {
            return $this->respond([], ErrorCode::ERR500_MSG,ErrorCode::ERR500);
        }
    }

    public function verifySmsToken(User $user, Request $request)
    {
        $token = $request->get('token');
        $smsToken = SmsToken::query()->where([
            'user_id' => $user->id,
            'token' => $token
        ])->orderBy('created_at','desc')->first();
        if (!$smsToken) {
            return $this->respond([], ErrorCode::ERR13_MSG, ErrorCode::ERR13);
        }
        if ($smsToken->expired_at < now()) {
            return $this->respond([], ErrorCode::ERR18_MSG, ErrorCode::ERR18);
        }
        $user->sms_verified_at = Carbon::now();
        $smsToken->used++;

        $user->save();
        $smsToken->save();

        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function countUserOtp($userId) {
        return SmsToken::query()->where('user_id', $userId)->whereDate('created_at', now())->count();
    }
    public function resendOtp(User $user, Request $request)
    {
        $countOtp = $this->countUserOtp($user->id);
        if ($countOtp >= env('MAX_OTP_PER_DAY', 5)) {
            return $this->respond(['user_id' => $user->id], ErrorCode::ERR24_MSG, ErrorCode::ERR24);

        }
        event(new NeedCreateUserSmsToken($user->id, $user->username));
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function storePassword(User $user, Request $request)
    {
        $password = $request->get('password');

        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR08_MSG, ErrorCode::ERR08);
        }

        $validator = Validator::make($request->all(), [
            'password_confirmation' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR09_MSG, ErrorCode::ERR09);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'min:8',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR04_MSG, ErrorCode::ERR04);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'max:25',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR05_MSG, ErrorCode::ERR05);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'confirmed',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR06_MSG, ErrorCode::ERR06);
        }

        $user->password = Hash::make($password);
        $user->finish_reg = 1;
        $user->save();

        auth()->login($user);

        $data = $this->getAuthUser($user);

        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function getAuthUser($user) {
        $data = [];

        $customClaims = [
            'user_id' => $user->id,
            'username' => $user->username,
        ];
        $data['token'] = JWTAuth::fromUser($user, $customClaims);
        $data['user'] = new UserTransformer($user);

        return $data;
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => new PhoneNumber()
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR01_MSG, ErrorCode::ERR01);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR07_MSG, ErrorCode::ERR07);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR08_MSG, ErrorCode::ERR08);
        }

        $usernameFormatted = validate_isdn($request->get('username'));
        $password = $request->get('password');
        // check db
        $user = User::query()->where('username', $usernameFormatted)->first();
        if (!$user) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }

        if (!Auth::attempt(['username'=>$usernameFormatted, 'password' => $password])) {
            return $this->respond([], ErrorCode::ERR15_MSG, ErrorCode::ERR15);
        }
        $user = Auth::user();

        if (!$user->finish_reg) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }

        $data = $this->getAuthUser($user);

        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }

    public function forgotPassword(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            return $this->respond([], ErrorCode::ERR07_MSG, ErrorCode::ERR07);
        }
        $usernameFormatted = validate_isdn($username);
        if (empty($usernameFormatted)) {
            return $this->respond([], ErrorCode::ERR01_MSG, ErrorCode::ERR01);
        }

        // check db
        $user = User::query()->where('username', $usernameFormatted)->first();
        if (!$user) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }
        $countOtp = $this->countUserOtp($user->id);
        if ($countOtp >= env('MAX_OTP_PER_DAY', 5)) {
            return $this->respond([], ErrorCode::ERR24_MSG, ErrorCode::ERR24);

        }

        event(new NeedCreateUserSmsToken($user->id, $user->username));
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function testSmsToken() {
        var_dump( (Redis::keys('vov_1_*')));
        return $this->respond(SmsToken::query()->orderBy('id', 'desc')->limit(5)->get(), ErrorCode::SUCCESS, ErrorCode::SUCCESS_MSG);
    }
    public function sendSmsToPhone( Request $request)
    {
        $phone = $request->get('phone');

        $phone = validate_isdn($phone);
        event(new NeedCreateUserSmsToken(1, $phone));
        return $this->respond(['phone' => $phone], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
}
