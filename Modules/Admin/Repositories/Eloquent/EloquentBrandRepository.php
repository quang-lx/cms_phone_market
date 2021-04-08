<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\BrandRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Mon\Entities\BrandPcategory;
class EloquentBrandRepository extends BaseRepository implements BrandRepository
{
    public function create($data)
    {
        $brand_pcategory=[];
        $brand_id = $this->model->create($data)->id;
        foreach ($data['category'] as $key => $value) {
           array_push($brand_pcategory,['brand_id'=>$brand_id,'pcategory_id'=>$value]);
        }
        BrandPcategory::insert($brand_pcategory);
    }
}
