<?php

namespace Modules\Shop\Events\Transfer;

use Modules\Mon\Entities\Transfer;


class TransferWasCreated
{
    /**
     * @var Transfer
     */
    private $model;
    private $data;
    public function __construct(Transfer $model, $data)
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
