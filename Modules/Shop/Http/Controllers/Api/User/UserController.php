<?php

namespace Modules\Shop\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\User;
use Modules\Mon\Repositories\ProfileRepository;
use Modules\Shop\Http\Requests\User\CreateUserRequest;
use Modules\Shop\Transformers\UserTransformer;
use Modules\Shop\Http\Requests\User\UpdateUserRequest;
use Modules\Shop\Repositories\UserRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class UserController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $profileRepository;

    public function __construct(
        Authentication $auth,
        UserRepository $user,
        ProfileRepository $profileRepository
    )
    {
        parent::__construct($auth);

        $this->userRepository = $user;
        $this->profileRepository = $profileRepository;
    }


    public function index(Request $request)
    {
        return UserTransformer::collection($this->userRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return UserTransformer::collection($this->userRepository->newQueryBuilder()->get());
    }


    public function store(CreateUserRequest $request)
    {
        $username = $request->get('username');
        $data = $request->all();

        $data['company_id'] = Auth::user()->company_id;
        $data['status'] = User::STATUS_ACTIVE;
        $data['sms_verified_at'] = now();
        $data['finish_reg'] = 1;

        $user = $this->userRepository->createWithRoles($data, $request->get('roles') );
        $data['user_id'] = $user->id;
        $this->profileRepository->create($data);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::user.message.create success'),
        ]);
    }


    public function find(User $user)
    {
        return new  UserTransformer($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $username = $request->get('username');
        $usernameFormatted = validate_isdn($username);
        $data = $request->all();

        $this->userRepository->updateAndSyncRoles($user, $data, $request->get('roles'));
        $profile = $user->profile()->first();

        if ($profile) {
            $this->profileRepository->update($profile, $data);
        } else {
            $data['user_id'] = $user->id;
            $this->profileRepository->create($data);
        }

        return response()->json([
            'errors' => false,
            'message' => trans('ch::user.message.update success'),
        ]);
    }

    public function destroy(User $user)
    {
        $this->userRepository->destroy($user);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::user.message.delete success'),
        ]);
    }
}
