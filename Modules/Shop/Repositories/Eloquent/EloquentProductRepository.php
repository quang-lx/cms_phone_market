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

use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Company;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\PcategoryProduct;
use Modules\Admin\Transformers\ProductTransformer;

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
							'title' => $item['value'],
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

		if ($request->get('shop_id') !== null) {
            $shopId = $request->get('shop_id');
            $query->where('shop_id', $shopId);
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

	public static function processArr($listProduct){
        $result = array();
        foreach ($listProduct as $productId => $total){
            $item['productId'] = $productId;
            $item['totalRevenue'] = $total;
            $result[] = $item;
        }

        usort($result, function (array $a, array $b) {
            return $a['totalRevenue'] < $b['totalRevenue'];
        });
        return $result;
    }

	public function topProduct(Request $request, $relations = null)
    {
		$user = Auth::user();
		$companyId = $user->company_id;
		$shopId = $user->shop_id;
        $query = OrderProduct::query()
			->join('orders','order_product.order_id','=','orders.id')
			->select('order_product.*')->where('orders.company_id', $companyId);
		if ($shopId){
			$query->where('orders.shop_id', $shopId);
		}

		$orderProduct = $query->whereNull('order_product.deleted_at')->get();

        $listProduct = array();
        foreach ($orderProduct as $order){
            $productId = $order->product_id;
            if (array_key_exists($productId,$listProduct)){
                $oldValue = $listProduct[$productId];
            } else $oldValue = 0;
            $listProduct[$productId] = $oldValue + intval($order->quantity * $order->price_sale);
        }

        $listProduct = self::processArr($listProduct);
        $result = array();
        foreach ($listProduct as $key => $product){
            if ($key >= 5) break;
            $key++;
            $productDetail = Product::find($product['productId']);
            $companyDetail = Company::find($productDetail->company_id);
            $item['key'] = $key;
            $item['productInfo'] = sprintf('%s. %s', '0'.$item['key'], $productDetail->name);
            $item['shopInfo'] = sprintf('%s - %s - %sđ', $companyDetail->name, $companyDetail->address, number_format($product['totalRevenue'],0,",","."));
            $result['topProduct'][] = $item;
        }

        //topCategory
        $categories = Category::all();
        $categoryWithRevenue = array();
        foreach ($categories as $category){
            //tim những sản phẩm thuộc category
            $categoryProduct = PcategoryProduct::query()->where('category_id', $category->id)->get();
            if (count($categoryProduct)){
                $revenueCategory = self::getRevenue($categoryProduct, $listProduct, $category->id);
				if ($revenueCategory){
					$categoryWithRevenue[] = array(
						'category_id' => $category->id,
						'totalRevenue' => $revenueCategory,
					);
				}

            }

        }
        usort($categoryWithRevenue, function (array $a, array $b) {
            return $a['totalRevenue'] < $b['totalRevenue'];
        });

        //lấy thêm tên chuyên mục, map kết quả trả về view
        foreach ($categoryWithRevenue as $category){
            $detailCategory = Category::find($category['category_id']);
            $item = array();
            $item['key'] = $key;
            $index = $key < 10 ? '0'.$key : $key;
            $item['value'] = sprintf('%s. %s - %sđ', $index, $detailCategory->title,
                    number_format($category['totalRevenue'],0,",","."));
            $result['topCategory'][] = $item;
        }
        return $result;

    }

    public static function getRevenue($categoryProduct, $listProduct, $categoryId){
        $revenue = 0;
        foreach ($categoryProduct as $record){
            foreach ($listProduct as $product){
                if ($product['productId'] == $record->product_id){
                    $revenue += $product['totalRevenue'];
                }
            }

        }
        return $revenue;
    }
}
