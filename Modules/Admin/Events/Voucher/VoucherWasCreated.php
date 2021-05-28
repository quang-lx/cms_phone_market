<?php

namespace Modules\Admin\Events\Voucher;

use Modules\Mon\Entities\Voucher;
use Modules\Media\Repositories\StoringMedia;


class VoucherWasCreated implements StoringMedia
{
    /**
     * @var Voucher
     */
    private $model;
    private $data;
    public function __construct(Voucher $model, $data)
    {
        $this->model = $model;
        $this->data = $data;
    }
    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->model;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
