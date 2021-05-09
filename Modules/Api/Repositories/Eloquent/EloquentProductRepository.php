<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

class EloquentProductRepository extends ApiBaseRepository implements ProductRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function listByCategory(Request $request, $includeSub = false)
    {
        $query = $this->model->query();
        if ($category_id = $request->get('category_id')) {

            if ($includeSub) {

                $query->whereHas('pcategories', function ($q) use ($category_id) {
                    $q->where('parent_id', $category_id)
                        ->orWhere('pcategory.id', $category_id);
                });
            } else {
                $query->whereHas('pcategories', function ($q) use ($category_id) {
                    $q->where('pcategory.id', $category_id);
                });
            }
        }
        if ($from_price = $request->get('from_price')) {
            $query->where('product.price', '>=', $from_price);
        }
        if ($to_price = $request->get('to_price')) {
            $query->where('product.price', '<=', $to_price);
        }

        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    public function listBaoHanh(Request $request)
    {
        $query = $this->model->query();
        if ($shop_id = $request->get('shop_id')) {

            $query->where('shop_id', $shop_id);
        }


        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    public function listByService(Request $request)
    {
        $query = $this->model->query();
        if ($category_id = $request->get('category_id')) {

            $query->whereHas('pcategories', function ($q) use ($category_id) {
                $q->where('pcategory.id', $category_id);
            });
        }
        if ($brand = $request->get('brand_id')) {

            $query->whereHas('brand', function ($q) use ($brand) {
                $q->where('brand.id', $brand);
            });
        }

        if ($problem_id = $request->get('problem_id')) {

            $query->whereHas('problems', function ($q) use ($problem_id) {
                $q->where('problems.id', $problem_id);
            });
        }
        if ($sort_by = $request->get('sort_by')) {

            if ($sort_by == 'price') {
                $query->orderBy('product.price');
            } elseif ($sort_by == 'distance') {
                //TODO
            }
        }

        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    public function detail($id)
    {
        return $this->model->query()->find($id);
    }

    //Sản phẩm tương tự: Lấy sản phẩm cùng danh mục nhưng khác cửa hàng.
    public function getRelated($id, Request $request)
    {
        $product = Product::query()->with('pcategoryAsm')->first();
        $query = $this->model->query();
        $query->where('id', '!=', $id);
        if ($product) {
            $query->whereHas('pcategories', function ($query) use ($product) {
                $query->whereIn('pcategory.id', $product->pcategoryAsm()->select('category_id'));
            });

            $query->where('shop_id', '<>', $product->shop_id);
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    //TODO
    public function getSuggested($id, Request $request)
    {
        $query = $this->model->query();
        $query->where('id', '!=', $id);
        return $query->active()->paginate($request->get('per_page', 10));

    }

    public function listByShop(Request $request, $shop_id)
    {
        $query = $this->model->query();

        $query->where('shop_id', $shop_id);

        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    //TODO

    public function buyNow(Request $request)
    {
        $query = $this->model->query();


        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }

    //TODO
    public function bestSell(Request $request)
    {
        $query = $this->model->query();


        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
        }
        return $query->active()->paginate($request->get('per_page', 10));

    }
}
