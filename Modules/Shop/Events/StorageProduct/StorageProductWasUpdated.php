<?php

namespace Modules\Shop\Events\StorageProduct;

use Modules\Mon\Entities\StorageProduct;


class StorageProductWasUpdated
{
    /**
     * @var StorageProduct
     */
    private $model;
    private $data;
    public function __construct(StorageProduct $model, $data)
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
