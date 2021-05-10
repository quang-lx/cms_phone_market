<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
	        'birthday' => $this->birthday,
	        'rank' => $this->rank,
	        'rank_point' => $this->rank_point,
	        'phone' => $this->phone,
	        'gender' => $this->gender,
	        'email' => $this->email,
	        'avatar' => $this->avatar? new MediaShortTransformer($this->avatar): null

        ];


        return $data;
    }

}
