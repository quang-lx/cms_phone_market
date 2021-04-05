<?php

namespace Modules\Shop\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\User;
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

    public function __construct(Authentication $auth, UserRepository $user)
    {
        parent::__construct($auth);

        $this->userRepository = $user;
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
        $this->userRepository->create($request->all());

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
        $this->userRepository->update($user, $request->all());

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
