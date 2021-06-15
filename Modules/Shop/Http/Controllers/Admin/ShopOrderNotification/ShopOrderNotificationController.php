<?php

namespace Modules\Shop\Http\Controllers\Admin\ShopOrderNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopOrderNotification;
use Modules\Shop\Repositories\ShopOrderNotificationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\AdminController;

class ShopOrderNotificationController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$shopordernotifications = $this->shopordernotification->all();

        return $this->view('shop::shopordernotifications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('shop::shopordernotifications.create');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  ShopOrderNotification $shopordernotification
     * @return Response
     */
    public function edit(ShopOrderNotification $shopordernotification)
    {
        return $this->view('shop::shopordernotifications.edit', compact('shopordernotification'));
    }


}
