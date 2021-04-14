<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ProblemTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,


             'urls' => [
                'delete_url' => route('api.problem.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
