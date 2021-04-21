<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\VoucherRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

class EloquentVoucherRepository extends BaseRepository implements VoucherRepository
{

    public function create($data)
	{
        $data['company_id'] = Auth::user()->company_id;
		$model = $this->model->create($data);
        $data['products'] = array_map(function ($product){
            return $product['id'];
        }, $data['products']);
		if (isset($data['products']) && is_array($data['products'])) {
			$model->products()->sync($data['products']); //mảng product_id
		}

		return $model;
	}

}
