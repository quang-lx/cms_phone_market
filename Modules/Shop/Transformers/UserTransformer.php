<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class UserTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'activated' => $this->activated,
            'last_login' => $this->last_login,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'status_name' =>$this->status_name,
            'status_color' =>$this->status_color,
            'status' =>$this->status,
            'updated_by' =>$this->updated_by,
            'role' =>$this->roles,
            'shop_id' =>$this->shop_id,
            'created_name' =>$this->created_name,

             'urls' => [
                'delete_url' => route('api.user.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
