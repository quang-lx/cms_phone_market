<?php

namespace Modules\Admin\Events\Rank;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\Rank;


class RankWasCreated implements StoringMedia
{
    /**
     * @var Rank
     */
    private $model;
    private $data;
    public function __construct(Rank $model, $data)
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
