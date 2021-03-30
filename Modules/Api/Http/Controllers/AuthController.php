<?php

namespace Modules\Api\Http\Controllers;

use App\Events\NeedCreateUserSmsToken;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Entities\ConnectedAccount;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Api\Http\Requests\GetInfoSocialRequest;
use Modules\Mon\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            return $this->respond([], ErrorCode::ERR07_MSG, ErrorCode::ERR07);
        }
        $password = $request->get('password');
        $input = $request->all();

        $validator = Validator::make($input, [
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR08_MSG, ErrorCode::ERR08);
        }

        $validator = Validator::make($input, [
            'password_confirmation' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR09_MSG, ErrorCode::ERR09);
        }

        $validator = Validator::make($input, [
            'password' => 'min:8',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR04_MSG, ErrorCode::ERR04);
        }

        $validator = Validator::make($input, [
            'password' => 'max:25',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR05_MSG, ErrorCode::ERR05);
        }

        $validator = Validator::make($input, [
            'password' => 'confirmed',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR06_MSG, ErrorCode::ERR06);
        }

        $name = $request->get('name');
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR26_MSG, ErrorCode::ERR26);
        }

        $phone = validate_isdn($request->get('phone'));
        $validator = Validator::make($input, [
            'phone' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR25_MSG, ErrorCode::ERR25);
        }

        if(!$phone) {
            return $this->respond([], ErrorCode::ERR27_MSG, ErrorCode::ERR27);
        }
        // check db
        $user = User::query()->where('username', $username)->first();
        if ($user && $user->finish_reg) {
            return $this->respond([], ErrorCode::ERR12_MSG, ErrorCode::ERR12);
        }
        if (!$user) {
            $userData = [
                'username' => $username,
                'name' => $name,
                'phone' => $phone,
                'password' => Hash::make($password),
                'type' => User::TYPE_USER,
                'status' => User::STATUS_INACTIVE,
                'finish_reg' => 0,

            ];

            $user = User::create($userData);
        }

        if ($user) {
            event(new NeedCreateUserSmsToken($user->id, $request->get('phone')));
            return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
        } else {
            return $this->respond([], ErrorCode::ERR500_MSG,ErrorCode::ERR500);
        }
    }

    public function verifySmsToken(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR29_MSG, ErrorCode::ERR01);
        }


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
        $user->finish_reg = 1;
        $user->status= User::STATUS_ACTIVE;
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
        if (!$user->phone) {
            return $this->respond(['user_id' => $user->id], ErrorCode::ERR25_MSG, ErrorCode::ERR25);

        }
        if ($user->phone) {
            event(new NeedCreateUserSmsToken($user->id, $user->phone));
        }

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

        if ($request->get('in_change')) {
            $data = ['user_id' => $user->id];
        } else {
            auth()->login($user);

            $data = $this->getAuthUser($user);
        }


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

        $username = validate_isdn($request->get('username'));
        $password = $request->get('password');
        // check db
        $user = User::query()->where(['username' =>  $username, 'type' =>  User::TYPE_USER])->first();
        if (!$user) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }

        if (!Auth::attempt(['username'=>$username, 'password' => $password])) {
            return $this->respond([], ErrorCode::ERR15_MSG, ErrorCode::ERR15);
        }
        $user = Auth::user();

        if (!$user->finish_reg) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }
        if ($user->status != User::STATUS_ACTIVE) {
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


        // check db
        $user = User::query()->where(['username' =>  $username, 'type' =>  User::TYPE_USER])->first();
        if (!$user) {
            return $this->respond([], ErrorCode::ERR14_MSG, ErrorCode::ERR14);
        }
        $countOtp = $this->countUserOtp($user->id);
        if ($countOtp >= env('MAX_OTP_PER_DAY', 5)) {
            return $this->respond([], ErrorCode::ERR24_MSG, ErrorCode::ERR24);

        }

        event(new NeedCreateUserSmsToken($user->id, $user->phone));
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function forgotPasswordSendOtp(User $user, Request $request)
    {
        $phone = $request->get('phone');

        if (empty($phone)) {
            return $this->respond([], ErrorCode::ERR25_MSG, ErrorCode::ERR25);
        }
        $phone = validate_isdn($phone);

        // check db

        if (!$user->phone != $phone) {
            return $this->respond([], ErrorCode::ERR27_MSG, ErrorCode::ERR27);
        }
        $countOtp = $this->countUserOtp($user->id);
        if ($countOtp >= env('MAX_OTP_PER_DAY', 5)) {
            return $this->respond([], ErrorCode::ERR24_MSG, ErrorCode::ERR24);

        }

        event(new NeedCreateUserSmsToken($user->id, $user->phone));
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function checkPassword(User $user, Request $request)
    {
        $password = $request->get('password');

        if (empty($password)) {
            return $this->respond([], ErrorCode::ERR08_MSG, ErrorCode::ERR08);
        }

        // check db

        if (!$user->password != Hash::make($password)) {
            return $this->respond([], ErrorCode::ERR28_MSG, ErrorCode::ERR28);
        }
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function loginSocial(GetInfoSocialRequest $request)
    {
        try{
            if ($request->provider === ConnectedAccount::SERVICE_FACEBOOK) {
                /**
                 * @var $userSocial \Laravel\Socialite\Two\User
                 */
                $userSocial = Socialite::driver('facebook')->userFromToken($request->get('token'));
                if (!$userSocial) {
                    return $this->respond([], ErrorCode::ERR500_MSG, ErrorCode::ERR500);

                }

                $userAccount = ConnectedAccount::query()->where([
                    'account_id' => $userSocial->id,
                    'provider' => ConnectedAccount::SERVICE_FACEBOOK
                ])->first();
                if ($userAccount) {
                    $user = $userAccount->user;
                    if ($user) {
                        auth()->login($user);
                        $data = $this->getAuthUser($user);
                        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $userRepository = app(UserRepository::class);
                        $user = $userRepository->create([
                            'email' => $userSocial->email,
                            'password' => bcrypt(rand(100000, 999999)),
                            'name' => $userSocial->name,
                            'username' => $userSocial->id,

                        ]);

                        ConnectedAccount::create([
                            'email' => $userSocial->email,
                            'account_id' => $userSocial->id,
                            'token' => $request->token,
                            'first_name' => $userSocial->name,
                            'user_id' => $user->id,
                            'provider' => ConnectedAccount::SERVICE_FACEBOOK
                        ]);
                        $message = 'Đăng ký thành công';
                        DB::commit();
                        auth()->login($user);
                        $data = $this->getAuthUser($user);
                        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

                    } catch (\Exception $exception) {
                        Log::info($exception->getMessage());
                        DB::rollBack();
                        return $this->respond([], ErrorCode::ERR500_MSG, ErrorCode::ERR500);

                    }

                }

            } else if ($request->provider === ConnectedAccount::SERVICE_GOOGLE) {
                //$token = Socialite::driver('google')->getAccessTokenResponse($request->get('token'));
                /**
                 * @var $userSocial User
                 */
                $userSocial = Socialite::driver('google')->userFromToken($request->get('token'));
                if (!$userSocial) {
                    return $this->respond([], ErrorCode::ERR500_MSG, ErrorCode::ERR500);

                }

                $userAccount = ConnectedAccount::query()->where([
                    'account_id' => $userSocial->id,
                    'provider' => ConnectedAccount::SERVICE_GOOGLE
                ])->first();
                if ($userAccount) {
                    $user = $userAccount->user;
                    if ($user) {
                        auth()->login($user);
                        $data = $this->getAuthUser($user);
                        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $userRepository = app(UserRepository::class);
                        $user = $userRepository->create([
                            'email' => $userSocial->email,
                            'password' => bcrypt(rand(100000, 999999)),
                            'name' => $userSocial->name,
                            'username' => $userSocial->id,

                        ]);

                        ConnectedAccount::create([
                            'email' => $userSocial->email,
                            'account_id' => $userSocial->id,
                            'token' => $request->token,
                            'first_name' => $userSocial->name,
                            'user_id' => $user->id,
                            'provider' => ConnectedAccount::SERVICE_GOOGLE
                        ]);
                        $message = 'Đăng ký thành công';
                        DB::commit();
                        auth()->login($user);
                        $data = $this->getAuthUser($user);
                        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

                    } catch (\Exception $exception) {
                        Log::info($exception->getMessage());
                        DB::rollBack();
                        return $this->respond([], ErrorCode::ERR500_MSG, ErrorCode::ERR500);

                    }

                }

            }
        }catch (\Exception $exception) {

            return $this->respond([], $exception->getMessage(), ErrorCode::ERR500);

        }

    }

}
