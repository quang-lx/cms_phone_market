<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProblemTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->title,

        ];


        return $data;
    }

}
