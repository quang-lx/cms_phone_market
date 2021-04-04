<?php

namespace Modules\Admin\Events\Pcategory;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\Pcategory;


class PCategoryWasUpdated implements StoringMedia
{
    /**
     * @var Pcategory
     */
    private $model;
    private $data;
    public function __construct(Pcategory $model, $data)
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
