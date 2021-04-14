<?php


namespace Modules\Api\Transformers\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeBannerTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,

        ];


        return $data;
    }

}
