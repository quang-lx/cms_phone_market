<?php

namespace Modules\Admin\Events\VtCategory;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\VtCategory;


class VtCategoryWasUpdated implements StoringMedia
{
    /**
     * @var VtCategory
     */
    private $model;
    private $data;
    public function __construct(VtCategory $model, $data)
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
