<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\AttributeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentAttributeRepository extends BaseRepository implements AttributeRepository
{
    public function create($data)
    {

        $model =  $this->model->create($data);
        foreach ($data['list_attribute_data_post'] as $list_name) {
            $model->attributeValues()->create(["name" => $list_name]);
        }

    }
}
