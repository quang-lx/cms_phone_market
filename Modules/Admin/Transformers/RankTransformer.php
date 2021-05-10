<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class RankTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description'=> $this->description,
            'point' => $this->point,
            'thumbnail' => $this->thumbnail,
            'urls' => [
                'delete_url' => route('api.rank.destroy', $this->id),
            ],

        ];


        return $data;
    }
}
