<?php

namespace Modules\Shop\Events\Transfer;

use Modules\Mon\Entities\TransferHistory;


class TransferWasUpdated
{
    /**
     * @var Transfer
     */
    private $model;
    private $data;
    public function __construct(TransferHistory $model, $data)
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
