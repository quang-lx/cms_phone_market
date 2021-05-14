<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\AreaRepository;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Province;

class CachedEloquentAreaRepository implements AreaRepository
{

	/**
	 * @return Collection
	 */
    public function getProvinces() {
       return  Cache::rememberForever(CacheKey::PROVINCE_ALL, function ()  {
            return Province::query()->get();
        });
    }
	/**
	 * @return Collection
	 */
    public function getDistricts(Request $request) {
        $provinceId = null;
        $cacheKey = CacheKey::DISTRICT_ALL;
        if ($provinceId = $request->get('province_id')) {
            $cacheKey = sprintf(CacheKey::DISTRICT_BY_PROVINCE, $provinceId);
        }
        return  Cache::rememberForever($cacheKey, function () use ($provinceId) {
            $query = District::query();
            if ($provinceId) {
                $query = $query->where('province_id', $provinceId);
            }
            return $query->get();
        });
    }
	/**
	 * @return Collection
	 */
    public function getPhoenixes(Request $request) {

        $districtId  = null;
        $cacheKey = CacheKey::PHOENIX_ALL;
        if ($districtId = $request->get('district_id')) {
            $cacheKey = sprintf(CacheKey::PHOENIX_ALL_BY_DISTRICT, $districtId);
        }
        return  Cache::rememberForever($cacheKey, function () use ($districtId) {

            $query = Phoenix::query();
            if ($districtId) {
                $query = $query->where('district_id', $districtId);
            }
            return $query->get();
        });
    }

    public function getAll(Request $request) {
        return  Cache::rememberForever(CacheKey::PROVINCE_ALL, function () use ($request) {
            $provinces = $this->getProvinces();
            $districts = $this->getDistricts($request);
            $phoenixes = $this->getPhoenixes($request);
            return [
                'provinces' => $provinces,
                'districts' => $districts,
                'phoenixes' => $phoenixes,
            ];
        });
    }
    public function getProvinceById($provinceId) {
    	$request = new Request();
	    $provinces = $this->getProvinces();
	    return $provinces->where('id', $provinceId)->first();
    }
	public function getDistrictById($provinceId, $districtId) {
		$request = new Request();
		$request->request->add(['province_id' => $provinceId]);
		$provinces = $this->getDistricts($request);
		return $provinces->where('id', $districtId)->first();
	}
	public function getPhoenixById($districtId, $phoenixId) {
		$request = new Request();
		$request->request->add(['district_id' => $districtId]);
		$provinces = $this->getDistricts($request);
		return $provinces->where('id', $phoenixId)->first();
	}
}
