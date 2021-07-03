<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class GiftTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'point' => $this->point,
            'amount' => $this->amount,
            'status' => $this->status,
            'description' => $this->description,
            'created_by' => optional($this->users)->name,
            'created_at' => $this->created_at->format('H:i d/m/Y'),
            'thumbnail' => $this->thumbnail,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            
             'urls' => [
                'delete_url' => route('api.gift.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
