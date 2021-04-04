<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ListCompanyTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'adminUser' => $this->adminUser(),
            'username' => optional($this->adminUser())->username,
            'admin_id' => optional($this->adminUser())->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'description' => $this->description,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'level' => $this->level,
            'level_name' => $this->level_name,
            'created_by' => $this->created_by,
            'updated_by' => optional($this->editor)->username,
            'deleted_by' => $this->deleted_by,
            'updated_at' => $this->updated_at->format('H:i d/m/Y'),
            'address' => $this->address,
            'branchnumber' =>$this->countShop(),
            'urls' => [
                'delete_url' => route('api.company.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
