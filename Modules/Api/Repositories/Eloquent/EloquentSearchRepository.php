<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

class EloquentSearchRepository extends ApiBaseRepository implements SearchRepository
{

    public function search(Request $request)
    {
        $shop = [];
        $products = [];
        if ($keyword = $request->get('q')) {
            // search in shop
            $query = Shop::query();
            $query->whereRaw("MATCH (name, address) AGAINST (?)", $this->fullTextWildcards($keyword));
            $shop = $query->active()->first();

            // search in product

            $query = Product::query();
            // filter
            if ($category_id = $request->get('category_id')) {

                $query->whereHas('pcategories', function ($q) use ($category_id) {
                    $q->where('pcategory.id', $category_id);
                });
            }
            if ($from_price = $request->get('from_price')) {
                $query->where('product.price', '>=', $from_price);
            }
            if ($to_price = $request->get('to_price')) {
                $query->where('product.price', '<=', $to_price);
            }
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
            $products = $query->active()->paginate($request->get('per_page', 10));
        }
        return [$shop, $products];
    }
    public function listSuggestion(Request $request)
    {
        $result = [];
        if ($keyword = $request->get('q')) {
            $limit = 10;
            // search in shop
            $query = Shop::query();
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
            $result = $query->select(['name'])->limit(5)->get()->pluck('name')->toArray();
            if ($result) {

                $limit = $limit - count($result);
            }


            // search in product

            $query = Product::query();

            // filter
            if ($category_id = $request->get('category_id')) {

                $query->whereHas('pcategories', function ($q) use ($category_id) {
                    $q->where('pcategory.id', $category_id);
                });
            }
            if ($from_price = $request->get('from_price')) {
                $query->where('product.price', '>=', $from_price);
            }
            if ($to_price = $request->get('to_price')) {
                $query->where('product.price', '<=', $to_price);
            }

            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
            $products = $query->select(['name'])->limit($limit)->get()->pluck('name')->toArray();
            $result = array_merge($result, $products);

        }
        return $result;
    }

}
