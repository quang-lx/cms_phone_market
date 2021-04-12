<?php

namespace Modules\Shop\Events\Product;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\Product;


class ProductWasCreated implements StoringMedia
{
    /**
     * @var Product
     */
    private $model;
    private $data;
    public function __construct(Product $model, $data)
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
