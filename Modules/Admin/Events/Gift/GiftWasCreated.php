<?php

namespace Modules\Admin\Events\Gift;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\AlarmType;
use Modules\Mon\Entities\Gift;


class GiftWasCreated implements StoringMedia
{
    /**
     * @var AlarmType
     */
    private $model;
    private $data;
    public function __construct(Gift $model, $data)
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
