<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class CompanyTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'username' => optional($this->adminUser())->username,
            'admin_id' => optional($this->adminUser())->id,
            'name' =>$this->name,
            'phone' =>$this->phone,
            'description' =>$this->description,
            'status' =>$this->status,
            'status_name' =>$this->status_name,
            'status_color' =>$this->status_color,
            'level' =>$this->level,
            'level_name' =>$this->level_name,
            'created_by' =>$this->created_by,
            'deleted_by' =>$this->deleted_by,
            'address' =>$this->address,

             'urls' => [
                'delete_url' => route('api.company.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
