<?php

namespace Modules\Shop\Http\Controllers\Admin\Attribute;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Attribute;
use Modules\Shop\Repositories\AttributeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class AttributeController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$attributes = $this->attribute->all();

        return $this->view('shop::attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::attributes.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  Attribute $attribute
     * @return Response
     */
    public function edit(Attribute $attribute)
    {
        return $this->view('shop::attributes.edit', compact('attribute'));
    }


}
