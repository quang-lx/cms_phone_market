<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\ProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Company;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\PcategoryProduct;
use Modules\Admin\Transformers\ProductTransformer;

class EloquentProductRepository extends BaseRepository implements ProductRepository
{

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

       if ($request->get('company_id') !==null) {
            $companyId = $request->get('company_id');
            $query->where('company_id', $companyId);
        }

	   if ($request->get('source') == 'voucher'){
		   $query->select('product.*','product.name AS value');
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
        $orderProduct = OrderProduct::query()->whereNull('deleted_at')->get();
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
                $categoryWithRevenue[] = array(
                    'category_id' => $category->id,
                    'totalRevenue' => $revenueCategory,
                );
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
