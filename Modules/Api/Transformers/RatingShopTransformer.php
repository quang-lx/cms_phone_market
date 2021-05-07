<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingShopTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'user_name' => optional($this->user)->name,
	         'created_at'=> $this->created_at->format('H:i d/m/Y'),
	        'user_avatar' => ($this->user && $this->user->avatar ) ? new MediaShortTransformer($this->user->avatar) : null,
	        'files' => $this->files ? MediaShortTransformer::collection($this->files) : null,
        ];


        return $data;
    }

}
