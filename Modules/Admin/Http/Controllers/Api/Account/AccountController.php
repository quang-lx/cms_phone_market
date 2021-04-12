<?php

namespace Modules\Admin\Http\Controllers\Api\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Account;
use Modules\Admin\Http\Requests\Account\CreateAccountRequest;
use Modules\Admin\Transformers\AccountTransformer;
use Modules\Admin\Http\Requests\Account\UpdateAccountRequest;
use Modules\Admin\Repositories\AccountRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class AccountController extends ApiController
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(Authentication $auth, AccountRepository $account)
    {
        parent::__construct($auth);

        $this->accountRepository = $account;
    }


    public function index(Request $request)
    {
        return AccountTransformer::collection($this->accountRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return AccountTransformer::collection($this->accountRepository->newQueryBuilder()->get());
    }


    public function store(CreateAccountRequest $request)
    {
        $this->accountRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::account.message.create success'),
        ]);
    }


    public function find(Account $account)
    {
        return new  AccountTransformer($account);
    }

    public function update(Account $account, UpdateAccountRequest $request)
    {
        $this->accountRepository->update($account, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::account.message.update success'),
        ]);
    }

    public function destroy(Account $account)
    {
        $this->accountRepository->destroy($account);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::account.message.delete success'),
        ]);
    }
}
