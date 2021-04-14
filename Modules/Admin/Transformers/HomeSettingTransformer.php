<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class HomeSettingTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
	        'title' => $this->title,
	        'type' => $this->type,
	        'content' => $this->content,
	        'order_' => $this->order_,

        ];


        return $data;
    }


}
