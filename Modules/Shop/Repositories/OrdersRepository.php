<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface OrdersRepository extends BaseRepository
{
    // đơn hàng sửa chữa
    public function repairCancel($model, $data);
    public function repairConfirmed($model, $data);
    public function repairFixing($model, $data);
    public function repairSending($model, $data);
    public function repairDone($model, $data);
    //đơn hàng mua bán
    public function updateBuySell($model, $data);
    public function cancelBuySell($model, $data);

    // đơn hàng sửa chữa
    public function guaranteeCancel($model, $data);
    public function guaranteeConfirmed($model, $data);
    public function guaranteeWarranting($model, $data);
    public function guaranteeSending($model, $data);
    public function guaranteeDone($model, $data);
}
