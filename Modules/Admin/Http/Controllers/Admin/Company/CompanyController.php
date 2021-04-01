<?php

namespace Modules\Admin\Http\Controllers\Admin\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Company;
use Modules\Admin\Repositories\CompanyRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class CompanyController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$companies = $this->company->all();

        return $this->view('admin::companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::companies.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company $company
     * @return Response
     */
    public function edit(Company $company)
    {
        return $this->view('admin::companies.edit', compact('company'));
    }


}
