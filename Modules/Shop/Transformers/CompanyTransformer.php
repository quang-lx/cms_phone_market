<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class CompanyTransformer extends JsonResource
{


    public function toArray($request)
    {
        $shop = $this->shop()->get();
        foreach ($shop as $key => $value) {
            $shop[$key]['status_name'] = $value->status_name;
            $shop[$key]['status_color'] = $value->status_color;
            $shop[$key]['username'] = $this->adminUser()->username;
            $shop[$key]['thumbnail'] = $value->thumbnail;
        }
   
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
            'approve_status' => $this->approve_status,
            'branchnumber' =>$this->countShop(),
            'shop' => $shop,
            'thumbnail' => $this->thumbnail,
            'urls' => [
                'delete_url' => route('api.company.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
