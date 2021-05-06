<?php

namespace Modules\Shop\Events\Shop;

use Modules\Media\Repositories\StoringMedia;
use Modules\Mon\Entities\Shop;


class ShopWasUpdated implements StoringMedia
{
    /**
     * @var Shop
     */
    private $model;
    private $data;
    public function __construct(Shop $model, $data)
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
