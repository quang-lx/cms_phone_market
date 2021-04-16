<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

class EloquentSearchRepository implements SearchRepository
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
			$query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
			$products = $query->active()->paginate($request->get('per_page', 10));
		}
		return [$shop, $products];
	}
    public function listSuggestion(Request $request)
    {
        $result = [];
        if ($keyword = $request->get('q')) {
            $limit = 6;
            // search in shop
            $query = Shop::query();
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
            $shop = $query->select(['name'])->first();
            if ($shop) {
                $result[] = $shop->name;
                $limit = 5;
            }


            // search in product

            $query = Product::query();
            $query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
            $products = $query->select(['name'])->limit($limit)->get()->pluck('name')->toArray();
            Log::info($result);
            Log::info($products);
            $result = array_merge($result, $products);

        }
        return $result;
    }

    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 1) {
                $words[$key] = $word;
            }
        }

        $searchTerm = implode(',', $words);

        return $searchTerm;
    }
}
