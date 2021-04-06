<?php
namespace Modules\Shop\Transformers\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class UserTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => optional($this->profile)->phone,

            'created_at' => optional($this->created_at)->format('d-m-Y'),
            'updated_at' =>optional($this->updated_at)->format('d-m-Y'),
            'urls' => [
                'delete_url' => route('api.users.destroy', $this->id),
            ],
        ];


        return $data;
    }

}
