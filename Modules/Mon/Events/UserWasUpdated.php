<?php

namespace Modules\Mon\Events;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\User;


class UserWasUpdated implements StoringMedia
{
    /**
     * @var User
     */
    private $model;
    private $data;
    public function __construct(User $model, $data)
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
