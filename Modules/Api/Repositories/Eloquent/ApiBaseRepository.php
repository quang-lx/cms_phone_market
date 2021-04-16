<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

class ApiBaseRepository
{

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
