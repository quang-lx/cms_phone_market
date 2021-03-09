<?php

namespace Modules\Admin\Events\News;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\News;


class NewsWasUpdated implements StoringMedia
{
    /**
     * @var News
     */
    private $model;
    private $data;
    public function __construct(News $model, $data)
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
