<?php

namespace Modules\Admin\Http\Controllers\Admin\FbNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\FbNotification;
use Modules\Admin\Repositories\FbNotificationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class FbNotificationController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$fbnotifications = $this->fbnotification->all();

        return $this->view('admin::fbnotifications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('admin::fbnotifications.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  FbNotification $fbnotification
     * @return Response
     */
    public function edit(FbNotification $fbnotification)
    {
        return $this->view('admin::fbnotifications.edit', compact('fbnotification'));
    }


}
