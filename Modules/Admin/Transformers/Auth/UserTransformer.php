<?php
namespace Modules\Admin\Transformers\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class UserTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'thumbnail' => $this->thumbnail,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
	        'status_name' => $this->status_name,
	        'status_color' => $this->status_color,
            'created_at' => optional($this->created_at)->format('d-m-Y'),
            'updated_at' =>optional($this->updated_at)->format('d-m-Y h:m'),
            'urls' => [
                'delete_url' => route('api.users.destroy', $this->id),
            ],
        ];


        return $data;
    }

}
