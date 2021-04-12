<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Mon\Entities\ProductPrice;
use Modules\Shop\Repositories\ProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProductRepository extends BaseRepository implements ProductRepository
{

	public function create($data)
	{
		//LinhPV fake amount
		$data['amount'] =10;
		$model = $this->model->create($data);
		$this->syncPrice($model, $data['product_prices']);
		return $model;
	}

	public function update($model, $data)
	{
		//LinhPV fake amount
		$data['amount'] =10;
		$model->update($data);
		$this->syncPrice($model, $data['product_prices']);
		return $model;
	}

	public function syncPrice($model, $productPrices) {
		$selectedPrice= [];
		foreach ($productPrices as $item) {
			if (isset($item['id']))
				$selectedPrice[] = $item['id'];
		}
		ProductPrice::query()->where('product_id', $model->id)->whereNotIn('id', $selectedPrice)->delete();
		foreach ($productPrices as $item) {
			if (isset($item['min']) && isset($item['max']) && isset($item['price'])) {
				if (isset($item['id'])) {
					ProductPrice::query()->where('id',$item['id'])->update([
						'min' => $item['min'],
						'max' => $item['max'],
						'price' => $item['price'],
						'product_id' => $model->id
					]);
				} else {
					ProductPrice::create([
						'min' => $item['min'],
						'max' => $item['max'],
						'price' => $item['price'],
						'product_id' => $model->id
					]);
				}
			}

		}
	}
}
