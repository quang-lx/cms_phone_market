<?php

namespace Modules\Admin\Http\Controllers\Api\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Company;
use Modules\Admin\Http\Requests\Company\CreateCompanyRequest;
use Modules\Admin\Transformers\CompanyTransformer;
use Modules\Admin\Http\Requests\Company\UpdateCompanyRequest;
use Modules\Admin\Repositories\CompanyRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Repositories\UserRepository;

class CompanyController extends ApiController {
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Authentication $auth, CompanyRepository $company, UserRepository $userRepository) {
        parent::__construct($auth);

        $this->companyRepository = $company;
        $this->userRepository = $userRepository;
    }


    public function index(Request $request) {
        return CompanyTransformer::collection($this->companyRepository->serverPagingFor($request));
    }


    public function all(Request $request) {
        return CompanyTransformer::collection($this->companyRepository->newQueryBuilder()->get());
    }

    public function paramForCompany(Request $request) {
        return $request->only([
            'name', 'description', 'status', 'level', 'province_id', 'district_id', 'phoenix_id', 'created_by', 'deleted_by', 'address', 'phone'
        ]);
    }
    public function paramForUserCompanyCreate(Request $request) {
        return $request->only([
            'name','username', 'password','phone','email', 'status'
        ]);
    }
    public function store(CreateCompanyRequest $request) {
        $company = $this->companyRepository->create($this->paramForCompany($request));

        $userData = array_merge([
            'type' => User::TYPE_SHOP,
            'is_admin_company' => 1,
            'company_id' => $company->id

        ], $this->paramForUserCompanyCreate($request));

        $this->userRepository->create($userData);
        return response()->json([
            'errors' => false,
            'message' => trans('backend::company.message.create success'),
        ]);
    }


    public function find(Company $company) {
        return new  CompanyTransformer($company);
    }

    public function update(Company $company, UpdateCompanyRequest $request) {
        $this->companyRepository->update($company, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::company.message.update success'),
        ]);
    }

    public function destroy(Company $company) {
        $this->companyRepository->destroy($company);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::company.message.delete success'),
        ]);
    }
}
