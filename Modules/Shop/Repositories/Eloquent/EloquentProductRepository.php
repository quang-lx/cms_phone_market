<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\ProductPrice;
use Modules\Shop\Events\Product\ProductWasCreated;
use Modules\Shop\Events\Product\ProductWasUpdated;
use Modules\Shop\Repositories\ProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProductRepository extends BaseRepository implements ProductRepository
{

	public function create($data)
	{

		$model = $this->model->create($data);
//		$this->syncPrice($model, $data['product_prices']);
		if (isset($data['category_id']) && is_array($data['category_id'])) {
			$model->pcategories()->sync($data['category_id']);
		}
		if (isset($data['problem_id']) && is_array($data['problem_id'])) {
			$model->problems()->sync($data['problem_id']);
		}
		event(new ProductWasCreated($model, $data));

		return $model;
	}

	public function update($model, $data)
	{

		$model->update($data);
//		$this->syncPrice($model, $data['product_prices']);
		if (isset($data['category_id']) && is_array($data['category_id'])) {
			$model->pcategories()->sync($data['category_id']);
		}
		if (isset($data['problem_id']) && is_array($data['problem_id'])) {
			$model->problems()->sync($data['problem_id']);
		}
		event(new ProductWasUpdated($model, $data));

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

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

		if ($request->get('category_id') !==null) {
			$catId = $request->get('category_id');
			$query->join('category_product','product.id','=','category_product.product_id')
				->where('category_product.category_id',$catId)->select('product.*');
		}

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        $query->where('company_id', Auth::user()->company_id);

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%");
            });
        }

       if ($request->get('brand_id') !==null) {
           $brandId = $request->get('brand_id');
           $query->where('brand_id', $brandId);
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
