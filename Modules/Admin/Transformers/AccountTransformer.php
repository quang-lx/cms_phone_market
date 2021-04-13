<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AccountTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at->format('d-m-Y'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'updated_by' => $this->findCreatedBy()['name'],
            'updated_at' => $this->updated_at->format('d-m-Y'),
            'create_category' => 'Tạo Chuyên mục ',
            'update_category' => 'Cập nhật',


             'urls' => [
                'delete_url' => route('api.account.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
