<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\PInformation;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\ProductInformation;
use Modules\Mon\Entities\ProductPrice;
use Modules\Shop\Events\Product\ProductWasCreated;
use Modules\Shop\Events\Product\ProductWasUpdated;
use Modules\Shop\Repositories\ProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProductRepository extends BaseRepository implements ProductRepository {

	public function create($data) {

		$model = $this->model->create($data);
//		$this->syncPrice($model, $data['product_prices']);
		if (isset($data['category_id']) && is_array($data['category_id'])) {
			$model->pcategories()->sync($data['category_id']);
		}
		if (isset($data['problem_id']) && is_array($data['problem_id'])) {
			$model->problems()->sync($data['problem_id']);
		}
		if (isset($data['pinformations']) && is_array($data['pinformations'])) {
			$this->syncInformation($model, $data['pinformations']);
		}

		event(new ProductWasCreated($model, $data));

		if (isset($data['attribute_id']) && $data['attribute_id']) {
			$this->syncProductAttribute($model, $data['attribute_id']);
			if (isset($data['attribute_selected']) && isset($data['attribute_selected']['values']) && $data['attribute_selected']['values']) {
				$this->syncProductAttributeValues($model, $data['attribute_id'], $data['attribute_selected']['values']);
			}
		}

		return $model;
	}

	public function update($model, $data) {

		$model->update($data);
//		$this->syncPrice($model, $data['product_prices']);
		if (isset($data['category_id']) && is_array($data['category_id'])) {
			$model->pcategories()->sync($data['category_id']);
		}
		if (isset($data['problem_id']) && is_array($data['problem_id'])) {
			$model->problems()->sync($data['problem_id']);
		}

		if (isset($data['pinformations']) && is_array($data['pinformations'])) {
			$this->syncInformation($model, $data['pinformations']);
		}

		event(new ProductWasUpdated($model, $data));
		if (isset($data['attribute_id']) && $data['attribute_id']) {
			$this->syncProductAttribute($model, $data['attribute_id']);
			if (isset($data['attribute_selected']) && isset($data['attribute_selected']['values']) && $data['attribute_selected']['values']) {
				$this->syncProductAttributeValues($model, $data['attribute_id'], $data['attribute_selected']['values']);
			}
		} else {
			$this->removeProductAttribute($model);
		}
		return $model;
	}


	public function syncPrice($model, $productPrices) {
		$selectedPrice = [];
		foreach ($productPrices as $item) {
			if (isset($item['id']))
				$selectedPrice[] = $item['id'];
		}
		ProductPrice::query()->where('product_id', $model->id)->whereNotIn('id', $selectedPrice)->delete();
		foreach ($productPrices as $item) {
			if (isset($item['min']) && isset($item['max']) && isset($item['price'])) {
				if (isset($item['id'])) {
					ProductPrice::query()->where('id', $item['id'])->update([
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

	public function removeProductAttribute($model) {
		$model->attributes()->detach();
		$model->attributeValues()->detach();
	}

	public function syncProductAttribute($model, $productAttribute) {
		if ($productAttribute) {
			$model->attributes()->sync([ $productAttribute ]);
		}
	}

	public function syncInformation($model, $informations) {
		$user = Auth::user();
		if ($informations && is_array($informations)) {
			$selectedValues = [];
			foreach ($informations as $item) {
				if (isset($item['id']) && is_int($item['id']))
					$selectedValues[] = $item['id'];
			}
			ProductInformation::query()->where('product_id', $model->id)->whereNotIn('information_id', $selectedValues)->delete();
			foreach ($informations as $item) {
				if (isset($item['value']) && isset($item['id']) && !empty($item['id']) && !empty($item['value'])) {
					$pinformation = null;
					if (is_int($item['id'])) {
						$pinformation = PInformation::query()->find($item['id']);
					}

					if (!$pinformation) {
						$pinformation = PInformation::create([
							'title' => $item['id'],
							'company_id' => $user->company_id,
							'shop_id' => $user->shop_id,
						]);
					}
					$row = ProductInformation::query()->where([
						'product_id' => $model->id,
						'information_id' => $pinformation->id,
					])->first();
					if ($row) {
						$row->update([
							'value' => $item['value']

						]);
					} else {
						ProductInformation::create([
							'product_id' => $model->id,
							'information_id' => $pinformation->id,
							'value' => $item['value']
						]);
					}
				}

			}
		}
	}

	public function syncProductAttributeValues($model, $productAttribute, $productAttributeValues) {
		if ($productAttribute && is_array($productAttributeValues)) {
			$selectedValues = [];
			foreach ($productAttributeValues as $item) {
				if (isset($item['id']))
					$selectedValues[] = $item['id'];
			}
			ProductAttributeValue::query()->where('product_id', $model->id)->whereNotIn('value_id', $selectedValues)->delete();
			foreach ($productAttributeValues as $item) {
				if (isset($item['price']) && isset($item['sale_price']) && isset($item['amount'])) {
					$row = ProductAttributeValue::query()->where([
						'product_id' => $model->id,
						'value_id' => $item['id'],
					])->first();
					if ($row) {
						$row->update([
							'price' => $item['price'],
							'sale_price' => $item['sale_price'],
							'amount' => $item['amount']

						]);
					} else {
						ProductAttributeValue::create([
							'price' => $item['price'],
							'sale_price' => $item['sale_price'],
							'amount' => $item['amount'],
							'product_id' => $model->id,
							'attribute_id' => $item['attribute_id'],
							'value_id' => $item['id'],
						]);
					}
				}

			}
		}
	}

	public function serverPagingFor(Request $request, $relations = null) {
		$query = $this->newQueryBuilder();
		if ($relations) {
			$query = $query->with($relations);
		}

		if ($request->get('category_id') !== null) {
			$catId = $request->get('category_id');
			$query->join('category_product', 'product.id', '=', 'category_product.product_id')
				->where('category_product.category_id', $catId)->select('product.*');
		}

		if ($request->get('status') !== null) {
			$status = $request->get('status');
			$query->where('status', $status);
		}

		$user = Auth::user();
		$query->where('company_id', $user->company_id);
		if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}

		if ($request->get('search') !== null) {
			$keyword = $request->get('search');
			$query->where(function ($q) use ($keyword) {
				$q->orWhere('name', 'LIKE', "%{$keyword}%")
					->orWhere('sku', 'LIKE', "%{$keyword}%");
			});
		}

		if ($request->get('brand_id') !== null) {
			$brandId = $request->get('brand_id');
			$query->where('brand_id', $brandId);
		}

		if ($request->get('source') == 'voucher') {
			$query->select('product.*', 'product.name AS value');
		}

		if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
			$order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

			$query->orderBy($request->get('order_by'), $order);
		} else {
			$query->orderBy('product.id', 'desc');
		}

		return $query->paginate($request->get('per_page', 10));
	}
}
