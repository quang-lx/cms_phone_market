<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class BannersTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,


             'urls' => [
                'delete_url' => route('api.banners.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
