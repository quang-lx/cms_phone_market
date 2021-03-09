<?php

namespace Modules\Admin\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\User\ChangePasswordRequest;
use Modules\Admin\Http\Requests\User\CreateUserRequest;
use Modules\Admin\Http\Requests\User\UpdateUserRequest;
use Modules\Admin\Transformers\Auth\UserFullTransformer;
use Modules\Admin\Transformers\Auth\UserPermissionsTransformer;
use Modules\Admin\Transformers\Auth\UserTransformer;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Repositories\ProfileRepository;
use Modules\Mon\Repositories\UserRepository;
use Modules\Mon\Auth\Contracts\Authentication;

class UserController extends ApiController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var ProfileRepository
     */
    protected $profileRepository;
    public function __construct(
        Authentication $auth,
        UserRepository $userRepository,
        ProfileRepository $profileRepository
    ) {
        parent::__construct($auth);
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;

    }

    public function index(Request $request)
    {
        return UserTransformer::collection($this->userRepository->serverPagingFor($request));
    }

    public function store(CreateUserRequest $request)
    {
        $username = $request->get('username');
        $data = $request->all();
        if($request->get('type') == User::TYPE_USER) {
            $usernameFormatted = validate_isdn($username);
            $data['username'] = $usernameFormatted;
        }

        $data['sms_verified_at'] = now();
        $data['finish_reg'] = 1;

        $user = $this->userRepository->createWithRoles($data, $request->get('roles') );
        $data['user_id'] = $user->id;
        $this->profileRepository->create($data);
        return response()->json([
            'errors' => false,
            'message' => trans('backend::user.message.create success'),
        ]);
    }

    public function find(User $user)
    {
        return new  UserFullTransformer($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $username = $request->get('username');
        $usernameFormatted = validate_isdn($username);
        $data = $request->all();

        if ($user->type == User::TYPE_USER) {
            $data['username'] = $usernameFormatted;
        }

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
            'message' => trans('backend::user.message.update success'),
        ]);
    }

    public function destroy(User $user)
    {
        $currentUser = $this->auth->user();
        if ($currentUser->username == $user->username) {
            return response()->json([
                'errors' => true,
                'message' => trans('backend::user.message.not allow self delete'),
            ]);
        }

        $this->userRepository->destroy($user);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::user.message.delete success'),
        ]);
    }
    public function hasPermissions(Request $request)
    {
        $user = $this->auth->user();

        return UserPermissionsTransformer::collection($user->getAllPermissions());
    }
    public function changePassword(User $user, ChangePasswordRequest $request)
    {
        $data = $request->all();

        $this->userRepository->changePassword($user, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::user.message.change password success'),
        ]);
    }
}
