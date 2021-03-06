<?php

namespace Modules\Admin\Events\Company;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\Company;


class CompanyWasCreated implements StoringMedia
{
    /**
     * @var Company
     */
    private $model;
    private $data;
    public function __construct(Company $model, $data)
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
