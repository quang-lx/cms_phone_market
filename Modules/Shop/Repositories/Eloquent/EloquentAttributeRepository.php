<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\AttributeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentAttributeRepository extends BaseRepository implements AttributeRepository
{
    public function create($data)
    {
        $attr_value = [];
        foreach ($data['list_attribute_data_post'] as $list_name) {
            array_push($attr_value,['name'=>$list_name]);
        }
        $model =  $this->model->create($data);
        $model->attributeValues()->createMany($attr_value);


    }
}
