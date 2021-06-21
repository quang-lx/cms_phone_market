<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Api\Repositories\ApiRepository;
use Illuminate\Http\Request;
use Modules\Api\Repositories\ApiShopRepository;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\Mission;
use Modules\Mon\Entities\News;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\UserMissionDaily;

class EloquentApiShopRepository extends ApiBaseRepository implements ApiShopRepository
{

    public function getShopNearest($lat, $lng)
    {
        $result = new Collection();
        Shop::query()->whereNotNull(['lat', 'lng'])->chunk(100, function ($shops) use ($lat, $lng, $result) {
            foreach ($shops as $shop) {
                if (map_distance_km($shop->lat, $shop->lng, $lat, $lng) <= env('SHOP_NEAREST_KM', 5)) {
                    $result->push($shop);
                }
                if ($result->count() == 20) return false;
            }
        });
        return $result;
    }

    public function getShopBaoHanh(Request $request, $user)
    {

        $result = Shop::query()->paginate($request->get('per_page', 10));
        return $result;
    }

    public function getList(Request $request)
    {

        $query = Shop::query();
        if ($keyword = $request->get('q')) {
            $query->whereRaw("MATCH (name) AGAINST (?  IN BOOLEAN MODE)", $this->fullTextWildcards($keyword));
        }
        $result = $query->paginate($request->get('per_page', 10));
        return $result;
    }

    public function detail(Request $request, $id)
    {
        return Shop::query()->find($id);
    }
    public function listSuggestion(Request $request)
    {
        $result = [];
        if ($keyword = $request->get('q')) {
            $limit = 6;
            // search in shop
            $query = Shop::query();
            $query->whereRaw("MATCH (name) AGAINST (?  IN BOOLEAN MODE)", $this->fullTextWildcards($keyword));
            $result = $query->select(['name'])->limit($limit)->get()->pluck('name')->toArray();

        }
        return $result;
    }


}
