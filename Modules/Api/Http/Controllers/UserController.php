<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\OrderUserNotiRepository;
use Modules\Api\Transformers\OrderUserNotiTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class UserController extends ApiController
{

	/** @var OrderUserNotiRepository */
	public $orderUserNoti;
	public function __construct(Authentication $auth, OrderUserNotiRepository $orderUserNoti)
	{
		parent::__construct($auth);

		$this->orderUserNoti = $orderUserNoti;
	}
    public function update(Request $request)
    {

        $user = $this->auth->user();
        $user->update($request->only('email', 'name', 'phone', 'gender', 'birthday', 'lat', 'lng', 'fcm_token'));
	    if (  $avatar = $request->get('avatar')) {
		    $zone= 'avatar';
		    $fileSync[$avatar] = ['mediable_type' => get_class($user), 'zone' => $zone];
		    $user->filesByZone($zone)->sync($fileSync);

	    }
        return $this->respond(['user_id' => $user->id], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function logout(Request $request) {
        Auth::logout();
        return $this->respond([], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }


	public function changePassword(Request $request)
	{
        $oldPassword = $request->get('old_password');

        if (empty($oldPassword)) {
            return $this->respond([], ErrorCode::ERR30_MSG, ErrorCode::ERR08);
        }

        $user = Auth::user();
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
			'password' => 'min:6',
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

        if (!Hash::check($oldPassword, $user->password)) {
            return $this->respond([], ErrorCode::ERR28_MSG, ErrorCode::ERR28);
        }

		$data = ['user_id' => $user->id];
		$user->password = Hash::make($password);
		$user->finish_reg = 1;
		$user->save();

		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
	public function profile(Request $request)
	{
		$user = $this->auth->user();
		return $this->respond(new UserTransformer($user),ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}

	public function notifications(Request $request) {
		$notifications = $this->orderUserNoti->getList($request);
		return $this->respond(OrderUserNotiTransformer::collection($notifications),ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
}
