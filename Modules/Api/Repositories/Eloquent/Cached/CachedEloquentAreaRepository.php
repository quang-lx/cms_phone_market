<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\AreaRepository;
use Modules\Mon\Entities\Province;

class CachedEloquentAreaRepository implements AreaRepository
{

    public function getProvinces() {
       return  Cache::rememberForever(CacheKey::PROVINCE_ALL, function ()  {
            return Province::query()->get();
        });
    }

    public function getDistricts(Request $request) {
        $provinceId = null;
        $cacheKey = CacheKey::DISTRICT_ALL;
        if ($provinceId = $request->get('province_id')) {
            $cacheKey = sprintf(CacheKey::DISTRICT_BY_PROVINCE, $provinceId);
        }
        return  Cache::rememberForever($cacheKey, function () use ($provinceId) {
            $query = Province::query();
            if ($provinceId) {
                $query = $query->where('province_id', $provinceId);
            }
            return $query->get();
        });
    }

    public function getPhoenixes(Request $request) {

        $districtId  = null;
        $cacheKey = CacheKey::PHOENIX_ALL;
        if ($districtId = $request->get('district_id')) {
            $cacheKey = sprintf(CacheKey::PHOENIX_ALL_BY_DISTRICT, $districtId);
        }
        return  Cache::rememberForever($cacheKey, function () use ($districtId) {

            $query = Province::query();
            if ($districtId) {
                $query = $query->where('province_id', $districtId);
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
}
