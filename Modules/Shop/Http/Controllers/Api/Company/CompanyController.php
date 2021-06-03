<?php

namespace Modules\Shop\Http\Controllers\Api\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Company;
use Modules\Shop\Http\Requests\Company\CreateCompanyRequest;
use Modules\Shop\Transformers\CompanyTransformer;
use Modules\Shop\Http\Requests\Company\UpdateCompanyRequest;
use Modules\Shop\Repositories\CompanyRepository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Repositories\UserRepository;
use Modules\Shop\Http\Requests\User\ChangePasswordRequest;

class CompanyController extends ApiController
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    protected $userRepository;
    public function __construct(Authentication $auth, CompanyRepository $company,UserRepository $userRepository)
    {
        parent::__construct($auth);
        $this->userRepository = $userRepository;
        $this->companyRepository = $company;
    }


    public function index(Request $request)
    {
        return CompanyTransformer::collection($this->companyRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return CompanyTransformer::collection($this->companyRepository->newQueryBuilder()->get());
    }


    public function store(CreateCompanyRequest $request)
    {
        $this->companyRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::company.message.create success'),
        ]);
    }


    public function find()
    {
        $id = Auth::user()->company_id;
        $company = $this->companyRepository->find($id);
        return new  CompanyTransformer($company);
    }

    public function update(UpdateCompanyRequest $request)
    {
        $id = Auth::user()->company_id;
        $company = $this->companyRepository->find($id);
        $this->companyRepository->update($company, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::company.message.update success'),
        ]);
    }

    public function destroy(Company $company)
    {
        $this->companyRepository->destroy($company);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::company.message.delete success'),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->userRepository->changePassword(Auth::user(), $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::user.message.change password success'),
        ]);
    }
}
