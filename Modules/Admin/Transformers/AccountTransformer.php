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
            'avatar' => $this->avatar,
            'email' => $this->email,
            'phone' => $this->phone,
            'rank' => $this->rank,
            'rank_name' => $this->rank_name,
            'created_at' => $this->created_at->format('d-m-Y'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'updated_by' => optional($this->editor)->name,
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
