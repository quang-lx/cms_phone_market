<?php

namespace Modules\Api\Repositories\Eloquent\Cached;

use App\Models\CacheKey;
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

    public function getDistricts() {
        return  Cache::rememberForever(CacheKey::DISTRICT_ALL, function ()  {
            return Province::query()->get();
        });
    }

    public function getPhoenixes() {
        return  Cache::rememberForever(CacheKey::PHOENIX_ALL, function ()  {
            return Province::query()->get();
        });
    }

    public function getAll() {
        return  Cache::rememberForever(CacheKey::PROVINCE_ALL, function ()  {
            $provinces = $this->getProvinces();
            $districts = $this->getDistricts();
            $phoenixes = $this->getPhoenixes();
            return [
                'provinces' => $provinces,
                'districts' => $districts,
                'phoenixes' => $phoenixes,
            ];
        });
    }
}
