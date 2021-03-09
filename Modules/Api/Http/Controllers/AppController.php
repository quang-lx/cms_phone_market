<?php

namespace Modules\Api\Http\Controllers;

use App\Events\VbeeTTSResponsed;
use App\Models\CacheKey;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Repositories\AlarmLevelRepository;
use Modules\Admin\Repositories\AlarmTypeRepository;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Transformers\AlarmLevelTransformer;
use Modules\Api\Transformers\AlarmTypeTransformer;
use Modules\Api\Transformers\GmapHistoryTransformer;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Services\FileService;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\GmapHistory;
use Modules\Mon\Entities\Warning;
use Modules\Mon\Http\Controllers\ApiController;
use Spatie\Permission\Models\Permission;

class AppController extends ApiController
{

    public function testlock() {
        DB::beginTransaction();

        $permission1 = Permission::query()->lockForUpdate()->first();
        Log::info($permission1->id);
        sleep(10);
        return $this->respond($permission1, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }


}
