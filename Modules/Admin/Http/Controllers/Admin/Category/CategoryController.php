<?php

namespace Modules\Admin\Http\Controllers\Admin\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Category;
use Modules\Admin\Repositories\CategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class CategoryController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$categories = $this->category->all();

        return $this->view('admin::categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return  $this->view('admin::categories.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        return  $this->view('admin::categories.edit', compact('category'));
    }


}
