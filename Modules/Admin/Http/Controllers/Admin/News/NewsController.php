<?php

namespace Modules\Admin\Http\Controllers\Admin\News;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\News;
use Modules\Admin\Repositories\NewsRepository;
use Modules\Mon\Http\Controllers\AdminController;

class NewsController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$news = $this->news->all();

        return $this->view('admin::news.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::news.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  News $news
     * @return Response
     */
    public function edit(News $news)
    {
        return $this->view('admin::news.edit', compact('news'));
    }


}
