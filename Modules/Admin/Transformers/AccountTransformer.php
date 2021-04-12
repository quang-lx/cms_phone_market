<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AccountTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,


             'urls' => [
                'delete_url' => route('api.account.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
