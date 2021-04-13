<?php

namespace Modules\Admin\Events\Banners;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\AlarmType;
use Modules\Mon\Entities\Banners;


class BannersWasUpdated implements StoringMedia
{
    /**
     * @var AlarmType
     */
    private $model;
    private $data;
    public function __construct(Banners $model, $data)
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
